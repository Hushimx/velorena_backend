<?php

namespace App\Imports;

use App\Models\Lead;
use App\Models\Category;
use App\Models\Marketer;
use Illuminate\Support\Collection;
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

                // Create the lead
                Lead::create([
                    'company_name' => $row['company_name'],
                    'contact_person' => $row['contact_person'],
                    'email' => $row['email'],
                    'phone' => $row['phone'] ?? null,
                    'address' => $row['address'] ?? null,
                    'notes' => $row['notes'] ?? null,
                    'status' => $row['status'] ?? 'new',
                    'priority' => $row['priority'] ?? 'medium',
                    'category_id' => $category ? $category->id : null,
                    'marketer_id' => $marketer ? $marketer->id : null,
                    'next_follow_up' => !empty($row['next_follow_up']) ? $row['next_follow_up'] : null,
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('LeadsImport error: ' . $e->getMessage());
            throw $e;
        }
    }
}
