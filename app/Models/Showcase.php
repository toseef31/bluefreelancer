<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Showcase extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'cate',
        'currency',
        'amt',
        'img',
        'status',
        'user_id',
    ];
}