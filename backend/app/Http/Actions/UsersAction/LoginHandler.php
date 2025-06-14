<?php
namespace App\Http\Actions\UsersAction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class LoginHandler
{
    public function handle(array $params){
        if (!empty($params['handler']) && method_exists($this, $params['handler']))
            if(!empty($params['data']))
                return $this->{$params['handler']}($params['data']);
            else
                return $this->{$params['handler']}();
        else
            return ['status'=>'error','massage'=>'invalid requst'];
    }
    private function get_info(){
        if (session()->has('id') && Auth::check()) {
            $user = Auth::user();
            if ($user) {
                return ['status'=>'success','name'=>$user->name,'data'=>$user];
            }
        }
        return ['status' => 'error'];
    }
    private function logout(){
        if (!session()->has('id')) {
            return ['status' => 'success'];
        }
        session()->forget('id');
        Auth::logout();
        return ['status' => 'success'];
    }
    private function check_auth(){
        if (!session()->has('id') || !Auth::check()) {
            return ['status' => 'error'];
        }
        return ['status' => 'success'];
    }
    private function login($data){
        $validator = Validator::make($data, [
            'phone' => ['required', 'regex:/^09[0-9]{9}$/'],
            'password' => 'required|min:6',
        ]);
        if ($validator->fails()) {
            return [
                'status' => 'error',
                'message' => 'validation failed',
                'errors' => $validator->errors(),
            ];
        }
        $exists=User::where('phone', $data['phone'])->exists();
        if(!$exists) return ['status' => 'error', 'message' => 'شماره تماس ثبت نشده است است'];
        if (Auth::attempt(['phone' => $data['phone'], 'password' => $data['password']])) {
            session()->regenerate();
            session(['id' => Auth::id()]);
            return ['status' => 'success'];
        }
        return ['status' => 'error', 'message' => 'رمز عبور اشتباه است'];
    }
    private function sendResetCode($data){
        $validator=Validator::make($data,[
            'phone' => 'required|exists:users,phone',
        ]);
        if ($validator->fails())
            return [
                'status' => 'error',
                'message' => 'شماره موبایل در سیستم ثبت نشده',
                'errors' => $validator->errors(),
            ];
        $token = Str::random(6);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['phone' => $data['phone']],
            ['token' => $token, 'created_at' => Carbon::now()]
        );
        $this->send_sms($data['phone'], $token);
        return ['status' => 'success'];
    }
    private function verifyResetCode($data){
        $validator=Validator::make($data,[
            'phone' => 'required',
            'token' => 'required'
        ]);
        if ($validator->fails())
            return [
                'status' => 'error',
                'message' => 'کد ارسالی صحیح نیست',
                'errors' => $validator->errors(),
            ];
        $record = DB::table('password_reset_tokens')->where('phone', $data['phone'])->first();
        if (!$record || $record->token !== $data['token']) return ['status' => 'error', 'message' => 'کد اشتباه است'];
        if (Carbon::parse($record->created_at)->addMinutes(10)->isPast()) return ['status' => 'error', 'message' => 'کد منقضی شده است'];
        return ['status' => 'success'];
    }
    private function resetPassword($data){
        $validator=Validator::make($data,[
            'phone' => 'required|exists:users,phone',
            'token' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);
        if ($validator->fails())
            return [
                'status' => 'error',
                'message' => 'رمز عبور ضعیف است',
                'errors' => $validator->errors(),
            ];
        $record = DB::table('password_reset_tokens')->where('phone', $data['phone'])->first();
        if (!$record || $record->token !== $data['token']) return ['status' => 'error', 'message' => 'کد اشتباه است'];
        User::where('phone', $data['phone'])->update(['password' => Hash::make($data['password'])]);
        DB::table('password_reset_tokens')->where('phone', $data['phone'])->delete();
        return ['status' => 'success'];
    }
    private function send_sms($mobile, $code){
        Log::info("در حال ارسال SMS به $mobile با کد $code");
        $response = Http::withToken(config('services.smsir.api_key'))->post('https://api.sms.ir/v1/send/verify', [
            'mobile' => $mobile,
            'templateId' => config('services.smsir.template_id'),
            'parameters' => [
                ['name' => 'CODE', 'value' => $code],
            ],
        ]);
        Log::info($response);
    }
}