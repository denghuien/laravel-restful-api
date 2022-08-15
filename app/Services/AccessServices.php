<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2021/3/17
 * Time: 14:21
 */

namespace App\Services;

use App\Models\AccessLog;

class AccessServices
{
    /**
     * @var
     */
    private $method;

    /**
     * @var
     */
    private $status;

    /**
     * @var
     */
    private $endTime;

    /**
     * @var
     */
    private $tokenId;

    /**
     * @var
     */
    private $ip;

    /**
     * @var
     */
    private $action;

    /**
     * @var
     */
    private $path;

    /**
     * @var array
     */
    private $parameter;

    /**
     * @var array
     */
    private $header;

    /**
     * @param $method
     * @param $endTime
     * @param $ip
     * @param $action
     * @param $path
     * @param array $parameter
     * @param array $header
     * @param int $tokenId
     * @param int $status
     */
    public function __construct($method, $endTime, $ip, $action, $path, array $parameter = [], array $header = [], $tokenId = '0', int $status = 200)
    {
        $this->method = $method;
        $this->status = $status;
        $this->endTime = $endTime;
        $this->tokenId = $tokenId;
        $this->ip = $ip;
        $this->action = $action;
        $this->path = $path;
        $this->parameter = $parameter;
        $this->header = $header;
    }

    /**
     * @return AccessLog
     */
    public function create(): AccessLog
    {
        $startTime = LARAVEL_START;
        $date = date('Y-m-d H:i:s', $this->endTime);
        list($controller, $action) = explode('@', $this->action['controller']);
        $controller = str_replace('Controller', '', str_replace($this->action['namespace'] . '\\', '', $controller));
        $namespace = $this->action['namespace'];
        $parameter = json_encode($this->parameter, JSON_UNESCAPED_UNICODE);
        $header = json_encode($this->header, JSON_UNESCAPED_UNICODE);
        $requestId = $this->header['request-id'][0] ?? 0;
        $model = new AccessLog;
        $model->method = $this->method;
        $model->ip = $this->ip;
        $model->request_id = $requestId;
        $model->token_id = $this->tokenId;
        $model->url = config('app.url');
        $model->path = $this->path;
        $model->header = $header;
        $model->parameter = $parameter;
        $model->namespace = $namespace;
        $model->controller = $controller;
        $model->action = $action;
        $model->start_at = $startTime;
        $model->end_at = $this->endTime;
        $model->status = $this->status;
        $model->setCreatedAt($date);
        $model->setUpdatedAt($date);
        $model->save();

        return $model;
    }
}
