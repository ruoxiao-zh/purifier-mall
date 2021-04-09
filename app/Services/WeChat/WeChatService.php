<?php

namespace App\Services\WeChat;

use EasyWeChat\Factory;
use Illuminate\Http\Request;
use EasyWeChat\MiniProgram\Application;

class WeChatService
{
    private static $miniProgram;

    private function __construct()
    {
        //...
    }

    private function __clone()
    {
        //...
    }

    public static function miniProgram(): Application
    {
        if (is_null(self::$miniProgram)) {
            $config = [
                'app_id' => env('WECHAT_MINI_PROGRAM_APPID'),
                'secret' => env('WECHAT_MINI_PROGRAM_SECRET'),
            ];

            self::$miniProgram = Factory::miniProgram($config);
        }

        return self::$miniProgram;
    }
}
