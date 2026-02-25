<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasUuids, SoftDeletes;
    
    protected $fillable = ['user_id', 'document_number', 'birth_date', 'phone_number', 'gender'];
}
