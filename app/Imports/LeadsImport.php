<?php

namespace App\Imports;

use App\Models\Lead;
use App\Models\Category;
use App\Models\Marketer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LeadsImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        try {
            foreach ($collection as $row) {
                // Skip empty rows
                if (empty($row['company_name']) || empty($row['contact_person']) || empty($row['email'])) {
                    continue;
                }

                // Find category by name
                $category = null;
                if (!empty($row['category'])) {
                    $category = Category::where('name', $row['category'])->first();
                }

                // Find marketer by email
                $marketer = null;
                if (!empty($row['marketer_email'])) {
                    $marketer = Marketer::where('email', $row['marketer_email'])->first();
                }

                // Validate email format
                if (!filter_var($row['email'], FILTER_VALIDATE_EMAIL)) {
                    Log::warning('Invalid email format in import: ' . $row['email']);
                    continue;
                }

                // Check if lead already exists
                $existingLead = Lead::where('email', $row['email'])->first();
                if ($existingLead) {
                    Log::info('Lead already exists, skipping: ' . $row['email']);
                    continue;
                }

                // Create the lead
                Lead::create([
                    'company_name' => trim($row['company_name']),
                    'contact_person' => trim($row['contact_person']),
                    'email' => trim($row['email']),
                    'phone' => !empty($row['phone']) ? trim($row['phone']) : null,
                    'address' => !empty($row['address']) ? trim($row['address']) : null,
                    'notes' => !empty($row['notes']) ? trim($row['notes']) : null,
                    'status' => !empty($row['status']) ? $row['status'] : 'new',
                    'priority' => !empty($row['priority']) ? $row['priority'] : 'medium',
                    'category_id' => $category ? $category->id : null,
                    'marketer_id' => $marketer ? $marketer->id : null,
                    'next_follow_up' => !empty($row['next_follow_up']) ? $row['next_follow_up'] : null,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('LeadsImport error: ' . $e->getMessage());
            Log::error('LeadsImport stack trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }
}
