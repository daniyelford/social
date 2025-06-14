<?php

namespace App\Models;
use App\Models\Category;
use App\Models\SelectedCard;
use Illuminate\Database\Eloquent\Model;

class InstagramProfile extends Model
{
    protected $fillable = [
        'category_id',
        'username',
        'full_name',
        'followers',
        'bio',
        'profile_pic_url',
        'price',
        'error',
    ];
    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function selections(){
        return $this->morphMany(SelectedCard::class, 'cardable');
    }
}
