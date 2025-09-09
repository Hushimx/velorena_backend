<?php

namespace App\Imports;

use App\Models\Lead;
use App\Models\Category;
use App\Models\Marketer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Validation\Rule;

class LeadsImport implements ToModel, WithHeadingRow, WithValidation
{
    use Importable;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
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

        return new Lead([
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

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
            'status' => 'nullable|in:new,contacted,qualified,proposal_sent,negotiation,closed_won,closed_lost',
            'priority' => 'nullable|in:low,medium,high',
            'category' => 'nullable|string',
            'marketer_email' => 'nullable|email',
            'next_follow_up' => 'nullable|date',
        ];
    }
}
