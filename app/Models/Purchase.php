<?php

namespace App\Models;

use App\Enums\PurchaseStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'supplier_id',
        'date',
        'purchase_no',
        'status',
        'total_amount',
        'created_by',
        'updated_by',
        "user_id",
        "uuid"
    ];

    protected $casts = [
        'date'       => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'status'     => PurchaseStatus::class
    ];

 // Relación de pertenencia: Una compra pertenece a un proveedor
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    // Relación de pertenencia: Una compra fue creada por un usuario (created_by)
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    // Relación de pertenencia: Una compra fue actualizada por un usuario (updated_by)
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    // Relación de uno a muchos: Una compra tiene muchos detalles de compra
    public function details(): HasMany
    {
        return $this->hasMany(PurchaseDetails::class);
    }


    // Condición de búsqueda a la consulta actual con el 'purchase_no'
    // o con'status', con like.
    public function scopeSearch($query, $value): void
    {
        $query->where('purchase_no', 'like', "%{$value}%")
            ->orWhere('status', 'like', "%{$value}%")
        ;
    }


    // Relación de pertenencia: Una compra pertenece a un usuario (user_id)
     /**
     * Get the user that owns the Category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
