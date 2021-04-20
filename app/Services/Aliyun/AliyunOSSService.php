<?php

namespace App\Services\Aliyun;

use App\Enums\HttpCodeEnum;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AliyunOSSService
{
    protected static $disk;

    protected static $allowedExt = ['png', 'jpg', 'gif', 'jpeg', 'pdf'];

    protected static function getUploadPath(UploadedFile $file): string
    {
        static::checkFileExtension($file);

        return 'images/' . date('Y') . '/' . date('m') . '/' . date('d');
    }

    protected static function getSpecifyFilename(UploadedFile $file): string
    {
        $extension = static::checkFileExtension($file);

        return time() . random_int(10000, 99999) . '.' . $extension;
    }

    private static function checkFileExtension(UploadedFile $file): string
    {
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';

        if ( !in_array($extension, static::$allowedExt)) {
            abort(HttpCodeEnum::HTTP_CODE_500, '上传文件格式非法');
        }

        return $extension;
    }

    public static function upload2OSS(UploadedFile $file): string
    {
        $uploadPath = static::getUploadPath($file);

        return Storage::disk('oss')->put($uploadPath, $file);
    }

    public static function upload2OSSForSpecifyFilename(UploadedFile $file): string
    {
        $uploadPath = static::getUploadPath($file);
        $fileName = static::getSpecifyFilename($file);

        return Storage::disk('oss')->putFileAs($uploadPath, $file, $fileName);
    }

    public static function signUrl(string $filePath, int $ttl, array $config = []): string
    {
        return Storage::disk('oss')->signUrl($filePath, $ttl, $config);
    }

    public static function getTemporaryUrl(string $filePath, string $date): string
    {
        return Storage::disk('oss')->getTemporaryUrl($filePath, $date);
    }

    public static function exists(string $filePath): bool
    {
        return Storage::disk('oss')->has($filePath);
    }

    public static function getLastModifiedTime(string $filePath): int
    {
        return Storage::disk('oss')->lastModified($filePath);
    }

    public static function getLastModifiedTimestamp(string $filePath): string
    {
        return Storage::disk('oss')->getTimestamp($filePath);
    }

    public static function copyFile(string $oldFilePath, string $newFilePath): bool
    {
        return Storage::disk('oss')->copy($oldFilePath, $newFilePath);
    }

    public static function moveFile(string $oldFilePath, string $newFilePath): bool
    {
        return Storage::disk('oss')->move($oldFilePath, $newFilePath);
    }

    public static function getContents(string $filePath): string
    {
        return Storage::disk('oss')->read($filePath);
    }

    public static function deleteFromOSS($filePath): bool
    {
        if (self::exists($filePath)) {
            return Storage::disk('oss')->delete($filePath);
        }

        return true;
    }

    public static function downloadFromOSS($filePath): StreamedResponse
    {
        if ( !self::exists($filePath)) {
            abort(HttpCodeEnum::HTTP_CODE_500, '文件不存在');
        }

        return Storage::disk('oss')->download($filePath);
    }
}
