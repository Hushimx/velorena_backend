<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LeadsTemplateExport implements FromArray, WithHeadings, WithStyles
{
    /**
     * @return array
     */
    public function array(): array
    {
        // Return sample data for the template
        return [
            [
                'Example Company Ltd',
                'John Doe',
                'john@example.com',
                '+1234567890',
                '123 Main Street, City, Country',
                'Initial contact made via website',
                'new',
                'medium',
                'Technology',
                'marketer@example.com',
                '2025-01-20'
            ],
            [
                'Another Company Inc',
                'Jane Smith',
                'jane@another.com',
                '+0987654321',
                '456 Business Ave, Town, Country',
                'Referred by existing client',
                'contacted',
                'high',
                'Healthcare',
                'marketer2@example.com',
                '2025-01-25'
            ]
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Company Name',
            'Contact Person',
            'Email',
            'Phone',
            'Address',
            'Notes',
            'Status',
            'Priority',
            'Category',
            'Marketer Email',
            'Next Follow Up'
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold
            1 => ['font' => ['bold' => true]],
        ];
    }
}
