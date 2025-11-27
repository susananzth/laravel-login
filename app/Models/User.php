<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\Auth\ResetPasswordNotification;
use App\Notifications\Auth\VerifyEmailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'username',
        'phone',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        $firstname_initial = Str::of($this->firstname)
            ->explode(' ')
            ->take(1)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
        $lastname_initial = Str::of($this->lastname)
            ->explode(' ')
            ->take(1)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
        return $firstname_initial . $lastname_initial;
    }

    /**
     * Sobrescribe el envío de notificación de reset password.
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Sobrescribe el envío de notificación de verificación de email.
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailNotification());
    }

    // Citas como cliente
    public function appointments() {
        return $this->hasMany(Appointment::class);
    }

    // Citas asignadas como técnico
    public function assignedAppointments() {
        return $this->hasMany(Appointment::class, 'technician_id');
    }
}
