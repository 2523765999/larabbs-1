<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Gregwar\Captcha\CaptchaBuilder;
use App\Http\Requests\Api\CaptchasRequest;
use Mews\Captcha\Captcha;

class CaptchasController extends Controller
{
    //
    /*public function store(CaptchaRequest $request, CaptchaBuilder $captchaBuilder)
    {
        $key = 'captcha-'.str_random(15);
        $phone = $request->phone;

        $captcha = $captchaBuilder->build();
        $expiredAt = now()->addMinutes(2);
        \Cache::put($key, ['phone' => $phone, 'code' => $captcha->getPhrase()], $expiredAt);

        $result = [
            'captcha_key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
            'captcha_image_content' => $captcha->inline()
        ];

        return $this->response->array($result)->setStatusCode(201);
    }*/
    public function store(CaptchasRequest $request, CaptchaBuilder $captchaBuilder)
    {
        $key = 'captche-'. \Str::random(15);
        $phone = $request->phone;

        $captcha = $captchaBuilder->build();
        $expireAt = now()->addMinutes(2);
        \Cache::put($key, ['phone' => $phone, 'code'=> $captcha->getPhrase()], $expireAt);

        $result = [
            'captcha_key' => $key,
            'expired_at' => $expireAt->toDateTimeString(),
//            'captcha_image_content' => 'http://larabbs.test/captchas/{$captcha_key}'
            'captcha_image_content' => $captcha->inline(),
        ];

//        return $this->response->array($result)->setStatusCode(201);
        return $this->response->array($result)->setStatusCode(201);
    }

}
