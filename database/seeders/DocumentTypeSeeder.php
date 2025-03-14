<?php

namespace Database\Seeders;

use App\Models\DocumentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Contract',
                'description' => 'Employment contracts and agreements',
                'active' => true,
            ],
            [
                'name' => 'Invoice',
                'description' => 'Payment invoices',
                'active' => true,
            ],
            [
                'name' => 'Bill',
                'description' => 'Bills and receipts',
                'active' => true,
            ],
            [
                'name' => 'Certificate',
                'description' => 'Certificates and qualifications',
                'active' => true,
            ],
            [
                'name' => 'ID Document',
                'description' => 'Identification documents',
                'active' => true,
            ],
            [
                'name' => 'Other',
                'description' => 'Other company documents',
                'active' => true,
            ],
        ];

        foreach ($types as $type) {
            DocumentType::firstOrCreate(['name' => $type['name']], $type);
        }
    }
}
