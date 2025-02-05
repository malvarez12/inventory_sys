<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseDetails extends Model
{
    // contra la asignación masiva,'id' no puede ser asignado directamente
    protected $guarded = [
        'id',
    ];

    // asignación masiva, pueden ser asignados al crear o actualizar un registro
    protected $fillable = [
        'purchase_id',
        'product_id',
        'quantity',
        'unitcost',
        'total',
    ];

    // Convierte automáticamente los campos 'created_at' y 'updated_at' en fechas
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relación 'product' se cargará automáticamente cuando se consulte el modelo PurchaseDetails
    protected $with = ['product'];

    // Define una relación 'belongsTo', indicando que cada detalle de compra pertenece a un producto
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Define una relación 'belongsTo', indicando que cada detalle de compra pertenece a una compra
    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }
}
