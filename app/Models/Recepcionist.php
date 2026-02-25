<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Recepcionist extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = ['user_id', 'employee_number', 'shift'];
}
