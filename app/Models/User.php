<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
    'name',
    'email',
    'password',
    'role',
    'email_verified_token',
];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getEmailVerifiedTokenAttribute()
    {
        return $this->attributes['email_verified_token'];
    }
    

    public function isInvigilator()
    {
        return $this->role === 'invigilator';
    }

        public function examinationTimetables()
    {
        return $this->belongsToMany(ExaminationTimetable::class, 'examination_timetable_user');
    }

        public function timetable()
    {
        return $this->belongsTo(ExaminationTimetable::class);
    }
}
