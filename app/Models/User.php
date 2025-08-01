<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    //  public function scopeFilter($query, array $filters){
    //     if($filters['search'] ?? false){
    //         $query->where('name', 'like' , '%' . request('search') . '%')
    //             ->orwhere('username', 'like' , '%' . request('search') . '%')
    //             ->orwhere('email', 'like', '%' . request('search') . '%')
    //             ->orwhere('phone', 'like', '%' . request('search') . '%');
    //     }
    //  }

     protected $table = 'auth';
     protected $primaryKey = 'id';
     public $incrementing = true;
    
    protected $fillable = [
        'user_id',
        'username',
        'password',
        'company_id',
        'role_id',
        'login_attempts',
        'blocked_at',
        'is_online',
        'status',
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
}
