<?php

namespace App\Services\Aliyun;

use App\Enums\HttpCodeEnum;
use Illuminate\Support\Facades\Storage;

class AliyunOSSService
{
    protected static $disk;

    protected static $allowedExt = ["png", "jpg", "gif", 'jpeg', 'pdf'];

    public function __construct()
    {
        self::$disk = Storage::disk('oss');
    }

    protected static function getImageUploadPath($file): string
    {
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';

        if ( !in_array($extension, self::$allowedExt)) {
            abort(HttpCodeEnum::HTTP_CODE_500, '上传文件格式非法');
        }

        return 'images/' . date('Y') . '/' . date('m') . '/' . date('d');
    }

    public static function upload2OSS($file): string
    {
        $uploadImageFullName = self::getImageUploadPath($file);

        $path = Storage::disk('oss')->put($uploadImageFullName, $file);

        return self::signUrl($path, 300);
    }

    public static function signUrl(string $url, int $ttl): string
    {
        return Storage::disk('oss')->signUrl($url, $ttl);
    }

    public static function getLastModifiedTime(string $file)
    {
        return Storage::disk('oss')->lastModified($file);
    }

    public static function copyFile(string $oldFileName, string $newFileName): bool
    {
        return Storage::disk('oss')->copy($oldFileName, $newFileName);
    }

    public static function moveFile(string $oldFileName, string $newFileName): bool
    {
        return Storage::disk('oss')->move($oldFileName, $newFileName);
    }

    public static function getContents(string $file): string
    {
        return Storage::disk('oss')->read($file);
    }
}
