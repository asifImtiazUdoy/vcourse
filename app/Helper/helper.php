<?php

use Illuminate\Support\Str;



// Generate unique slug
function generateSlug($model, $name)
{
    $slug = Str::slug($name);

    if ($model::whereSlug($slug)->exists()) {
        $max = $model::whereName($name)->latest()->value('slug');
        if (isset($max[-1]) && is_numeric($max[-1])) {
            return preg_replace_callback('/(\d+)$/', function ($mathces) {
                return $mathces[1] + 1;
            }, $max);
        }
        return "{$slug}-2";
    }
    return $slug;
}
