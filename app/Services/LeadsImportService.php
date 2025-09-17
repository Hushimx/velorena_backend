<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\Category;
use App\Models\Marketer;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;

class LeadsImportService
{
    /**
     * Store uploaded file temporarily and return the path
     */
    private function storeFileTemporarily(UploadedFile $file): string
    {
        try {
            // Store the file temporarily
            $tempPath = $file->store('temp', 'public');
            $fullPath = storage_path('app/public/' . $tempPath);
            
            // Verify the file was stored
            if (!file_exists($fullPath)) {
                throw new \Exception('Failed to store file temporarily');
            }
            
            Log::info('File stored temporarily at: ' . $fullPath);
            return $fullPath;
        } catch (\Exception $e) {
            Log::error('Failed to store file temporarily: ' . $e->getMessage());
            throw new \Exception('Failed to store file temporarily: ' . $e->getMessage());
        }
    }
    
    /**
     * Clean up temporary file
     */
    private function cleanupTempFile(string $tempPath): void
    {
        try {
            if (file_exists($tempPath)) {
                unlink($tempPath);
                Log::info('Temporary file cleaned up: ' . $tempPath);
            }
        } catch (\Exception $e) {
            Log::warning('Failed to clean up temporary file: ' . $e->getMessage());
        }
    }
    
    /**
     * Validate uploaded file
     */
    private function validateFile(UploadedFile $file): void
    {
        if (!$file->isValid()) {
            throw new \Exception('Invalid file uploaded');
        }
        
        if ($file->getSize() === 0) {
            throw new \Exception('File is empty');
        }
        
        if ($file->getSize() > 10 * 1024 * 1024) { // 10MB limit
            throw new \Exception('File size exceeds 10MB limit');
        }
    }
    /**
     * Import leads from CSV file
     */
    public function importFromCsv(UploadedFile $file): array
    {
        $results = [
            'success' => 0,
            'skipped' => 0,
            'errors' => 0,
            'error_messages' => []
        ];

        try {
            // Validate the file first
            $this->validateFile($file);
            
            // Get the file path safely
            $filePath = $file->getPathname();
            $tempFile = null;
            
            // Validate file path
            if (empty($filePath) || !file_exists($filePath)) {
                // Try storing temporarily as fallback
                $filePath = $this->storeFileTemporarily($file);
                $tempFile = $filePath;
            }
            
            $handle = fopen($filePath, 'r');
            if (!$handle) {
                throw new \Exception('Could not open file for reading');
            }

            // Read header row
            $headers = fgetcsv($handle);
            if (!$headers) {
                throw new \Exception('Could not read file headers');
            }

            // Normalize headers (remove spaces, convert to lowercase)
            $headers = array_map(function($header) {
                return strtolower(trim($header));
            }, $headers);

            $rowNumber = 1;
            while (($row = fgetcsv($handle)) !== false) {
                $rowNumber++;
                
                try {
                    // Convert row to associative array
                    $data = array_combine($headers, $row);
                    
                    // Skip empty rows
                    if (empty($data['company_name']) || empty($data['contact_person']) || empty($data['email'])) {
                        $results['skipped']++;
                        continue;
                    }

                    // Validate email format
                    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                        $results['errors']++;
                        $results['error_messages'][] = "Row {$rowNumber}: Invalid email format - {$data['email']}";
                        continue;
                    }

                    // Check if lead already exists
                    $existingLead = Lead::where('email', $data['email'])->first();
                    if ($existingLead) {
                        $results['skipped']++;
                        Log::info('Lead already exists, skipping: ' . $data['email']);
                        continue;
                    }

                    // Find category by name
                    $category = null;
                    if (!empty($data['category'])) {
                        $category = Category::where('name', $data['category'])->first();
                    }

                    // Find marketer by email
                    $marketer = null;
                    if (!empty($data['marketer_email'])) {
                        $marketer = Marketer::where('email', $data['marketer_email'])->first();
                    }

                    // Create the lead
                    Lead::create([
                        'company_name' => trim($data['company_name']),
                        'contact_person' => trim($data['contact_person']),
                        'email' => trim($data['email']),
                        'phone' => !empty($data['phone']) ? trim($data['phone']) : null,
                        'address' => !empty($data['address']) ? trim($data['address']) : null,
                        'notes' => !empty($data['notes']) ? trim($data['notes']) : null,
                        'status' => !empty($data['status']) ? $data['status'] : 'new',
                        'priority' => !empty($data['priority']) ? $data['priority'] : 'medium',
                        'category_id' => $category ? $category->id : null,
                        'marketer_id' => $marketer ? $marketer->id : null,
                        'next_follow_up' => !empty($data['next_follow_up']) ? $data['next_follow_up'] : null,
                    ]);

                    $results['success']++;

                } catch (\Exception $e) {
                    $results['errors']++;
                    $results['error_messages'][] = "Row {$rowNumber}: " . $e->getMessage();
                    Log::error("Error processing row {$rowNumber}: " . $e->getMessage());
                }
            }

            fclose($handle);
            
            // Clean up temporary file if created
            if ($tempFile) {
                $this->cleanupTempFile($tempFile);
            }

        } catch (\Exception $e) {
            // Clean up temporary file if created
            if (isset($tempFile) && $tempFile) {
                $this->cleanupTempFile($tempFile);
            }
            
            Log::error('CSV Import error: ' . $e->getMessage());
            throw $e;
        }

        return $results;
    }

    /**
     * Import leads from Excel file using PhpSpreadsheet
     */
    public function importFromExcel(UploadedFile $file): array
    {
        $results = [
            'success' => 0,
            'skipped' => 0,
            'errors' => 0,
            'error_messages' => []
        ];

        try {
            // Validate the file first
            $this->validateFile($file);
            
            // Check if PhpSpreadsheet is available
            if (!class_exists(\PhpOffice\PhpSpreadsheet\IOFactory::class)) {
                throw new \Exception('PhpSpreadsheet library is not available. Please convert your Excel file to CSV format and try again.');
            }
            
            // Check if ZipArchive is available (required for Excel files)
            if (!class_exists('ZipArchive')) {
                throw new \Exception('ZipArchive extension is not available. Please convert your Excel file to CSV format and try again.');
            }

            // Get the file path safely
            $filePath = $file->getPathname();
            $tempFile = null;
            
            // Validate file path
            if (empty($filePath) || !file_exists($filePath)) {
                // Try storing temporarily as fallback
                $filePath = $this->storeFileTemporarily($file);
                $tempFile = $filePath;
            }

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            if (empty($rows)) {
                throw new \Exception('Excel file is empty');
            }

            // Get headers from first row
            $headers = array_map(function($header) {
                return strtolower(trim($header));
            }, array_shift($rows));

            foreach ($rows as $rowNumber => $row) {
                $rowNumber += 2; // +2 because we removed header row and arrays are 0-indexed
                
                try {
                    // Convert row to associative array
                    $data = array_combine($headers, $row);
                    
                    // Skip empty rows
                    if (empty($data['company_name']) || empty($data['contact_person']) || empty($data['email'])) {
                        $results['skipped']++;
                        continue;
                    }

                    // Validate email format
                    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                        $results['errors']++;
                        $results['error_messages'][] = "Row {$rowNumber}: Invalid email format - {$data['email']}";
                        continue;
                    }

                    // Check if lead already exists
                    $existingLead = Lead::where('email', $data['email'])->first();
                    if ($existingLead) {
                        $results['skipped']++;
                        Log::info('Lead already exists, skipping: ' . $data['email']);
                        continue;
                    }

                    // Find category by name
                    $category = null;
                    if (!empty($data['category'])) {
                        $category = Category::where('name', $data['category'])->first();
                    }

                    // Find marketer by email
                    $marketer = null;
                    if (!empty($data['marketer_email'])) {
                        $marketer = Marketer::where('email', $data['marketer_email'])->first();
                    }

                    // Create the lead
                    Lead::create([
                        'company_name' => trim($data['company_name']),
                        'contact_person' => trim($data['contact_person']),
                        'email' => trim($data['email']),
                        'phone' => !empty($data['phone']) ? trim($data['phone']) : null,
                        'address' => !empty($data['address']) ? trim($data['address']) : null,
                        'notes' => !empty($data['notes']) ? trim($data['notes']) : null,
                        'status' => !empty($data['status']) ? $data['status'] : 'new',
                        'priority' => !empty($data['priority']) ? $data['priority'] : 'medium',
                        'category_id' => $category ? $category->id : null,
                        'marketer_id' => $marketer ? $marketer->id : null,
                        'next_follow_up' => !empty($data['next_follow_up']) ? $data['next_follow_up'] : null,
                    ]);

                    $results['success']++;

                } catch (\Exception $e) {
                    $results['errors']++;
                    $results['error_messages'][] = "Row {$rowNumber}: " . $e->getMessage();
                    Log::error("Error processing row {$rowNumber}: " . $e->getMessage());
                }
            }

        } catch (\Exception $e) {
            // Clean up temporary file if created
            if (isset($tempFile) && $tempFile) {
                $this->cleanupTempFile($tempFile);
            }
            
            Log::error('Excel Import error: ' . $e->getMessage());
            throw $e;
        }
        
        // Clean up temporary file if created
        if (isset($tempFile) && $tempFile) {
            $this->cleanupTempFile($tempFile);
        }

        return $results;
    }
}
