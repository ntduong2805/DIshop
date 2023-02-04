<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as ModelsRole;

use function PHPUnit\Framework\at;

class Role extends ModelsRole
{
    use HasFactory;
    protected $fillable = [
        'name',
        'display_name',
        'group',
        'guard_name'
    ];
}
