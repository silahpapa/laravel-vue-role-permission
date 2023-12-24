<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permissions extends Model
{
    use HasFactory;

    protected $fillable = ['controller','method','name'];

    public function departments()
    {
        return $this->belongsToMany(Department::class);
    }
}
