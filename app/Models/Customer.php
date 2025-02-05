<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'photo',
        'account_holder',
        'account_number',
        'bank_name',
        "user_id",
        "uuid"
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

     // Relación de uno a muchos: Un cliente puede tener muchos pedidos (orders)
     public function orders(): HasMany
     {
         return $this->hasMany(Order::class);
     }
 
     //Un cliente puede tener muchas cotizaciones (quotations)
     public function quotations(): HasMany
     {
         return $this->hasMany(Quotation::class);
     }
 
     // Scope local para buscar clientes por nombre, correo electrónico o teléfono
     public function scopeSearch($query, $value): void
     {
         // Añade condiciones de búsqueda con LIKE para coincidencias parciales
         $query->where('name', 'like', "%{$value}%")
             ->orWhere('email', 'like', "%{$value}%")
             ->orWhere('phone', 'like', "%{$value}%");
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
