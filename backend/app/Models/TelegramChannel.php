<?php

namespace App\Models;
use App\Models\Category;
use App\Models\SelectedCard;
use Illuminate\Database\Eloquent\Model;

class TelegramChannel extends Model
{
    protected $fillable = [
        'category_id',
        'channel_id',
        'username',
        'title',
        'participants_count',
        'about',
        'photo_path',
        'admins',
        'price',
        'influencer_score',
        'payment_type',
        'insight',
    ];
    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function selections(){
        return $this->morphMany(SelectedCard::class, 'cardable');
    }

}
