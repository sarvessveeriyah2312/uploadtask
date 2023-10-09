<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    protected $fillable = ['name', 'status', 'file_hash', 'unique_key'];
}
