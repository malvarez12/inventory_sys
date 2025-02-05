<?php

namespace App\Models;

// Importación de clases necesarias
use App\Enums\OrderStatus; // Enum para manejar estados de órdenes.
use Illuminate\Database\Eloquent\Model; // Clase base para modelos de Eloquent.
use Illuminate\Database\Eloquent\Relations\HasMany; // Relación "has many".
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Relación "belongs to".

// Definición del modelo Order
class Order extends Model
{
    // Protege el campo 'id' para que no pueda ser asignado masivamente
    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'customer_id',
        'order_date',
        'order_status',
        'total_products',
        'sub_total',
        'vat',
        'total',
        'invoice_no',
        'payment_type',
        'pay',
        'due',
        "user_id",
        "uuid"
    ];

    // Define cómo se deben transformar los atributos al acceder a ellos
    protected $casts = [
        'order_date'    => 'date',           // Convierte 'order_date' a un objeto de tipo fecha.
        'created_at'    => 'datetime',       // Convierte 'created_at' a un objeto de tipo datetime.
        'updated_at'    => 'datetime',       // Convierte 'updated_at' a un objeto de tipo datetime.
        'order_status'  => OrderStatus::class // Interpreta 'order_status' como un valor del enum OrderStatus.
    ];
    
    // Relación: Una orden pertenece a un cliente
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    // Relación: Una orden tiene muchos detalles de orden (productos asociados)
    public function details(): HasMany
    {
        return $this->hasMany(OrderDetails::class);
    }

    // Método de consulta para realizar búsquedas por campos específicos
    public function scopeSearch($query, $value): void
    {
        $query->where('invoice_no', 'like', "%{$value}%") // Busca por número de factura.
            ->orWhere('order_status', 'like', "%{$value}%") // Busca por estado de la orden.
            ->orWhere('payment_type', 'like', "%{$value}%"); // Busca por tipo de pago.
    }

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
