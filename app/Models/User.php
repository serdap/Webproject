<?php

namespace App\Models;

use App\Models\Authenication\Permissions;
use App\Models\Authenication\Role;
use App\Models\Cart\Cart;
use App\Models\Order\Order;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject,MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = "users";
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'phone',
        'address',
        'verification_code',
        'token_expires_at',
        'point',
        'coupon'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'token_expires_at' => 'datetime'
    ];

    protected $visible = [
        'name',
        'email',
        'phone',
        'address',
    ];
    protected $guarded = ['id'];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
    public function hasPermission($permission): bool
    {
        $hasPermission = Permissions::where('name', $permission)->exists();

        // Check if any of the user's roles have the specified permission
        foreach ($this->roles as $role) {
            // Assuming 'permissions' is the relationship between Role and Permission
            if ($role->permissions()->exists() && $hasPermission) {
                return true;
            }
        }
        return false;
    }



    public function review()
    {
        return $this->hasMany(Review::class);
    }


    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
