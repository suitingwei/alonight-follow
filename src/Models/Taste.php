<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Taste extends Model
{
    const ACTION_TYPE_LIKE= 'LIKE';
    const ACTION_TYPE_DISLIKE= 'DISLIKE';

    public $guarded = [];
}
