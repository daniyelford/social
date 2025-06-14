<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelectedCard extends Model
{
    protected $fillable = ['user_id'];

    public function cardable()
    {
        return $this->morphTo();
    }
}

