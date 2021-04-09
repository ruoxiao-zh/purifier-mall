<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * http 状态码
 *
 * Class HttpCodeEnum
 * @package App\Enums
 */
final class HttpCodeEnum extends Enum
{
    /**
     * 200 OK - 对成功的 GET、PUT、PATCH 或 DELETE 操作进行响应, 也可以被用在不创建新资源的 POST 操作上
     */
    public const HTTP_CODE_200 = 200;

    /**
     * 201 Created - 对创建新资源的 POST 操作进行响应
     */
    public const HTTP_CODE_201 = 201;

    /**
     * 202 Accepted - 服务器接受了请求，但是还未处理，响应中应该包含相应的指示信息，
     * 告诉客户端该去哪里查询关于本次请求的信息
     */
    public const HTTP_CODE_202 = 202;

    /**
     * 204 No Content - 对不会返回响应体的成功请求进行响应（比如 DELETE 请求）
     */
    public const HTTP_CODE_204 = 204;

    /**
     * 304 Not Modified - HTTP 缓存 header 生效的时候用
     */
    public const HTTP_CODE_304 = 304;

    /**
     * 400 Bad Request - 请求异常，比如请求中的 body 无法解析
     */
    public const HTTP_CODE_400 = 400;

    /**
     * 401 Unauthorized - 没有进行认证或者认证非法
     */
    public const HTTP_CODE_401 = 401;

    /**
     * 403 Forbidden - 服务器已经理解请求，但是拒绝执行它, 用户授权失败抛出 authorizationexception
     */
    public const HTTP_CODE_403 = 403;

    /**
     * 404 Not Found - 请求一个不存在的资源
     */
    public const HTTP_CODE_404 = 404;

    /**
     * 405 Method Not Allowed - 所请求的 HTTP 方法不允许当前认证用户访问
     */
    public const HTTP_CODE_405 = 405;

    /**
     * 409 Conflict - 请求冲突（一般是重复请求造成的错误）
     */
    public const HTTP_CODE_409 = 409;

    /**
     * 410 Gone - 表示当前请求的资源不再可用。当调用老版本 API 的时候很有用
     */
    public const HTTP_CODE_410 = 410;

    /**
     * 415 Unsupported Media Type - 如果请求中的内容类型是错误的
     */
    public const HTTP_CODE_415 = 415;

    /**
     * 422 Unprocessable Entity - 用来表示校验错误
     */
    public const HTTP_CODE_422 = 422;

    /**
     * 423 Request Locked, please retry after waiting a time.
     */
    public const HTTP_CODE_423 = 423;

    /**
     * 429 Too Many Requests - 由于请求频次达到上限而被拒绝访问
     */
    public const HTTP_CODE_429 = 429;

    /**
     * 服务器内部错误
     */
    public const HTTP_CODE_500 = 500;
}
