<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Overtrue\EasySms\EasySms;
use App\Http\Requests\Api\VerificationCodeRequest;

class VerificationCodesController extends Controller
{
    public function store(VerificationCodeRequest $request, EasySms $easySms)
    {
//        return $this->response()->array(['test_message' => 'store verification code']);
        $phone = $request->phone;

//        if (!app()->environment('production')) {//app 要加（）,其次是没有 $ 美元符号
//            $code = '12345';
//        } else {
            //生成随机4位数，左侧补零
            $code = str_pad(random_int(1,9999), 4, 0, STR_PAD_LEFT);
            try {
                $result = $easySms->send($phone,[
                    'content' => "【行者漫步】您的注册验证码：{$code}，如非本人操作，请忽略本短信！"
                ]);
            } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception) {
                $message = $exception->getException('qcloud')->getMessage();
                return $this->response->errorInternal($message ?: '短信发送异常');
            }
//        }
//        $key = 'verificationCode_'.str_random(15);//Call to undefined function App\\Http\\Controllers\\Api\\str_random()
        $key = 'verificationCode_'. \Str::random(15);
        $expireAt = now()->addMinutes(10);
        //缓存验证码，10分钟过期
        \Cache::put($key, ['code' => $code, 'phone' => $phone], $expireAt);

        return $this->response()->array([
            'key' => $key,
            'expireAt' => $expireAt->toDateTimeString()
        ])->setStatusCode(201);
    }
}
