<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Batch extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'max_seat',
        'course_id',
        'number',
        'max_seat',
        'enrolled_students',
        'last_ennrollment_date',
        'class_starting_date',
        'status'

    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // public function students()
    // {
    //     return $this->hasMany(EnrollmentItem::class,'batch_id','id')
    //     ->join('users','enrollment_items.student_id','users.id');
    // }

    // public function lessons()
    // {
    //     return $this->hasMany(Lesson::class,'batch_id','id');
    // }
}
