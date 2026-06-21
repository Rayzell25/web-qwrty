<?php

namespace Database\Seeders;

use App\Models\InvoiceRecord;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class InvoiceRecordSeeder extends Seeder
{
    public function run(): void
    {
        $invoices = [
            [
                'invoice_number' => 'INV-2024-0001',
                'customer_name' => 'Andi Pratama',
                'customer_email' => 'andi@example.com',
                'customer_whatsapp' => '081234567890',
                'product_name' => 'Smartphone Pro X',
                'purchase_date' => Carbon::now()->subMonths(2),
                'warranty_status' => 'Aktif',
                'invoice_status' => 'Lunas',
                'notes' => 'Garansi resmi 12 bulan.',
            ],
            [
                'invoice_number' => 'INV-2024-0002',
                'customer_name' => 'Budi Santoso',
                'customer_email' => 'budi@example.com',
                'customer_whatsapp' => '081234567891',
                'product_name' => 'Laptop UltraBook 14',
                'purchase_date' => Carbon::now()->subMonths(5),
                'warranty_status' => 'Aktif',
                'invoice_status' => 'Lunas',
                'notes' => null,
            ],
            [
                'invoice_number' => 'INV-2024-0003',
                'customer_name' => 'Citra Dewi',
                'customer_email' => 'citra@example.com',
                'customer_whatsapp' => '081234567892',
                'product_name' => 'Wireless Earbuds Air',
                'purchase_date' => Carbon::now()->subYear(),
                'warranty_status' => 'Kedaluwarsa',
                'invoice_status' => 'Lunas',
                'notes' => 'Garansi telah berakhir.',
            ],
        ];

        foreach ($invoices as $invoice) {
            InvoiceRecord::updateOrCreate(
                ['invoice_number' => $invoice['invoice_number']],
                $invoice
            );
        }
    }
}
