<?php

namespace App\Services\WeChat;

use EasyWeChat\Factory;
use Illuminate\Http\Request;

class WeChatService
{
    private static $miniProgram = null;

    private function __construct()
    {
        //...
    }

    private function __clone()
    {
        //...
    }

    public static function miniProgram()
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
