<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InvoiceRecord extends Model
{
    /** @use HasFactory<\Database\Factories\InvoiceRecordFactory> */
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'customer_name',
        'customer_email',
        'customer_whatsapp',
        'product_name',
        'purchase_date',
        'warranty_status',
        'invoice_status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'purchase_date' => 'date',
        ];
    }

    /**
     * The warranty claims linked to this invoice.
     *
     * @return HasMany<WarrantyClaim>
     */
    public function warrantyClaims(): HasMany
    {
        return $this->hasMany(WarrantyClaim::class);
    }
}
