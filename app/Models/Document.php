<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;
    public $timestamps = true;

    protected $fillable = [
        'name',
        'size',
        'location',
        'location_s3',
        'created_by',
        'modified_by',
        'created_at'
    ];
}
