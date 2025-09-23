<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
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
        'last_login_at',
        'is_locked'

    ];
    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
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

    public function getFullNameAttribute()
    {
        return "{$this->prefix->name} {$this->firstname} {$this->lastname}";
    }
}
