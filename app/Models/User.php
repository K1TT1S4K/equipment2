<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\CustomResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'prefix_id',
        'lastname',
        'firstname',
        'user_type',
        'email',
        'password',
    ];
    protected $dates = ['deleted_at'];

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
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn(string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    /**
     * คืนค่า email สำหรับใช้รีเซ็ตรหัสผ่าน
     *
     * @return string|null
     */
    public function getEmailForPasswordReset()
    {
        return $this->email;
    }


    public function prefix()
    {
        return $this->belongsTo(Prefix::class, 'prefix_id'); // แก้ไขชื่อคอลัมน์ให้ตรงกับฐานข้อมูล
    }

    public function userType()
    {
        return $this->belongsTo(UserType::class, 'user_type'); // แก้ไขชื่อคอลัมน์ให้ตรงกับฐานข้อมูล
    }
    // public function equipments() : HasMany {
    //     return $this->hasMany(Equipment::class);
    // }

    public function logs(): HasMany
    {
        return $this->hasMany(Equipment_log::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token));
    }
}
