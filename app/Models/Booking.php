<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone_number',
        'guests',
        'user_id',
        'service_id',
        'booking_date',
        'track_id',
        'note',
    ];

    /**
     * Get the service that the booking belongs to.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

     /**
     * Get the service that the booking belongs to.
     */
    public function track()
    {
        return $this->belongsTo(Track::class);
    }

     /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'service_id' => 'array',
    ];
    
}
