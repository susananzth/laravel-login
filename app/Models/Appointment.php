<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'user_id',
        'technician_id',
        'service_id',
        'scheduled_at',
        'finished_at',
        'status',
        'notes',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function client() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function technician() {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function service() {
        return $this->belongsTo(Service::class);
    }
}
