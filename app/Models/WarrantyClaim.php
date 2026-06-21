<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WarrantyClaim extends Model
{
    /** @use HasFactory<\Database\Factories\WarrantyClaimFactory> */
    use HasFactory;

    public const STATUSES = [
        'pending' => 'Pending',
        'diproses' => 'Diproses',
        'disetujui' => 'Disetujui',
        'ditolak' => 'Ditolak',
        'selesai' => 'Selesai',
    ];

    protected $fillable = [
        'user_id',
        'invoice_record_id',
        'full_name',
        'email',
        'whatsapp',
        'invoice_number',
        'product_name',
        'complaint',
        'attachment',
        'status',
        'admin_note',
    ];

    /**
     * The user who submitted the claim (nullable for guests).
     *
     * @return BelongsTo<User, WarrantyClaim>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The invoice record linked to this claim (nullable).
     *
     * @return BelongsTo<InvoiceRecord, WarrantyClaim>
     */
    public function invoiceRecord(): BelongsTo
    {
        return $this->belongsTo(InvoiceRecord::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst((string) $this->status);
    }
}
