<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    protected $fillable = ['name','description','status'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permissions::class);
    }

}
