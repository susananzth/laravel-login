<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
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
