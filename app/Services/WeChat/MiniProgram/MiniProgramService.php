<?php

namespace App\Services\WeChat\MiniProgram;

use Illuminate\Http\Request;
use App\Services\WeChat\WeChatService;

class MiniProgramService
{
    private $miniProgram;

    public function __construct()
    {
        $this->miniProgram = WeChatService::miniProgram();
    }

    public function decryptData(string $code, string $iv, string $encryptedData): string
    {
        $session = $this->miniProgram->auth->session($code);

        return $this->miniProgram->encryptor->decryptData($session, $iv, $encryptedData);
    }
}
