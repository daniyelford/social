<?php
namespace App\Http\Actions\PageHandler;
use App\Models\TelegramChannel;
use App\Models\SelectedCard;
use Illuminate\Support\Facades\Auth;
class TelegramCards{
    public function handle(array $params){
        if (!empty($params['handler']) && method_exists($this, $params['handler']))
            if(!empty($params['data']))
                return $this->{$params['handler']}($params['data']);
            else
                return $this->{$params['handler']}();
        else
            return ['status'=>'error','massage'=>'invalid requst'];
    }
    private function all_telegram_cards(){
        $cards = TelegramChannel::with('category')->get();
        $cards = $cards->map(function ($card) {
            $admins = json_decode($card->admins, true);
            $selfAdmin = collect($admins)->firstWhere('is_self', true);
            $isSelected = SelectedCard::where('cardable_type', TelegramChannel::class)->where('cardable_id', $card->id)->when(Auth::id(), fn($q) => $q->where('user_id', Auth::id()))->exists();
            return [
                'id' => $card->id,
                'username' => $card->username,
                'title' => $card->title,
                'photo_path' => $card->photo_path ? asset('images/' . basename($card->photo_path)) : null,
                'category' => $card->category_id,
                'price' => $card->price,
                'followers' => $card->participants_count,
                'influencer_score' => $card->influencer_score ?? null,
                'updated_at' => $card->updated_at->format('Y-m-d H:i:s'),
                'category_name' => $card->category->name ?? 'متفرقه',
                'admin' => $selfAdmin['username']??null,
                'payment_type' => $card->payment_type,
                'insight' => (bool) $card->insight,
                'type' => (!empty($card->participants_count) && intval($card->participants_count)>10000?(!empty($card->influencer_score) && intval($card->influencer_score)>20?['very','good']:['very']):null),
                'selected' => (bool) $isSelected,
                'suitable' => (bool)($isSelected && !empty($card->participants_count) && intval($card->participants_count)>10000 && !empty($card->influencer_score) && intval($card->influencer_score)>20),
            ];
        });
        return ['status' => 'success', 'data' => $cards];
    }
    private function select_card($cart_id){
        $telegramChannel = TelegramChannel::find($cart_id);
        if (!$telegramChannel) {
            return ['status'=>'error'];
        }
        $telegramChannel->selections()->create([
            'user_id' => Auth::id(),
        ]);
        return ['status'=>'success'];
    }
}