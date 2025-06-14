<?php
namespace App\Http\Actions\RunPython;
use App\Models\InstagramProfile;
use Symfony\Component\Process\Process;

class InstagramBot
{
    public function handle(array $params)
    {
        if(empty($params['id']))
            return [
                'status' => 'error',
                'message' => 'id is empty',
            ];
        $process = new Process(['python', base_path('py/instagram/bot.py')]);
        $process->setInput(json_encode(['username' => $params['id']]));
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
        $profile = InstagramProfile::updateOrCreate(
            ['username' => $params['id']],
            [
                'full_name' => $output['full_name'] ?? null,
                'followers' => $output['followers'] ?? null,
                'bio' => $output['bio'] ?? null,
                'profile_pic_url' => $output['profile_pic_url'] ?? null,
                'price'=> $params['price'] ?? '',
                'error' => $output['error'] ?? null,
            ]
        );
        return [
            'status' => 'success',
        ];
    }
}
