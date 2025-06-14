<?php
namespace App\Http\Actions\RunPython;
use App\Models\TelegramChannel;
use Symfony\Component\Process\Process;

class TelegramBot
{
    public function handle(array $params)
    {
        if(empty($params['id']))
            return [
                'status' => 'error',
                'message' => 'id is empty',
            ];
        $process = new Process(['python3', base_path('py/telegram/bot.py')]);
        $process->setInput(json_encode(['channel_username' => $params['id']]));
        $process->run();
        if (!$process->isSuccessful()) {
            return [
                'status' => 'error',
                'message' => 'خطا در اجرای اسکریپت',
                'error' => $process->getErrorOutput(),
            ];
        }
        $output = json_decode($process->getOutput(), true);
        if (isset($output['error'])) {
            return [
                'status' => 'error',
                'message' => $output['error'],
            ];
        }
        $channel = TelegramChannel::updateOrCreate(
            ['channel_id' => $output['id']],
            [
                'username' => $output['username'] ?? null,
                'title' => $output['title'] ?? null,
                'participants_count' => $output['participants_count'] ?? 0,
                'about' => $output['about'] ?? '',
                'photo_path' => $output['photo_path'] ?? '',
                'admins' => $output['admins'] ?? '',
                'price'=> $params['price'] ?? '',
                'payment_type'=> $params['type'] ?? '24',
                'influencer_score'=>$output['influencer_score'] ?? 0,
                'insight'=>!empty($output['participants_count']) && !empty($output['influencer_score'])
            ]
        );
        return ($channel?['status' => 'success']:['status' => 'error']);
    }
}
