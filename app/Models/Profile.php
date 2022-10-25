<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'profile_picture',
        'affiliate_link',
        'facebook',
        'linkedin',
        'applied',
        'designation',
        'experties',
        'cv',
        'about_me',
        'note'
    ];
}
