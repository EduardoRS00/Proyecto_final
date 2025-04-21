<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Booking extends Model
{
    //use HasFactory;

    // por si decido usar create() en el futuro ->
    protected $fillable = [
        'restaurant_id',
        'customer_name',
        'customer_lastname',
        'contact_phone',
        'contact_email',
        'booking_date',
        'booking_time',
        'num_people',
        'table_type',
        'menu',
        'comments',
        'allergies',
        'baby_stroller',
        'high_chair',
        'wheelchair',
        'promo_opt_in',
        'terms_accepted'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'restaurant_id');
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'restaurant_id');
    }
}
