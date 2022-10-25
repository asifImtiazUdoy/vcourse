<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'thumbnail',
        'instructor',
        'category',
        'price',
        'old_price',
        'status',
        'type',
        'time_duration',
        'media_link',
        'rating_number',
        'rating_quantity',
        'number_of_lessons',
        'student_enrolled',
        'discount',
        'timing',
        'venu',
        'description',
        'requirments',
        'forwho',
        'what_will_learn'
    ];
}
