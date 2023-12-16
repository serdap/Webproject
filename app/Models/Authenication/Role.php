<?php

namespace app\Models\Authenication;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];

    public function permissions()
    {
        return $this->hasMany(Permissions::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
