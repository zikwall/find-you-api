<?php

namespace zikwall\findyouapi;

use Yii;

class Module extends \yii\base\Module
{
    /**
     * @var string DNS link to the handler service
     */
    public $handleUrl = '';
    /**
     * @var string Access token, in the future will be generated and taken from the database
     */
    public $securityToken = '';
    /**
     * @var string
     */
    public $imageUploadPath = '';
    /**
     * @var array
     */
    public $responseHeaders = [];

    public function init()
    {
        parent::init();

        if (count($this->responseHeaders) == 0) {
            $this->responseHeaders = $this->getHeaders();
        }

        if (Yii::$app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'zikwall\vktv\commands';
        }
    }

    public function getHeaders()
    {
        return [
            ['Access-Control-Allow-Methods', ['POST', 'OPTIONS']],
            ['Access-Control-Allow-Origin', '*'],
            ['Access-Control-Allow-Credentials', true],
            ['Access-Control-Max-Age', 86400],
            ['Access-Control-Allow-Methods', $this->allowHeaders()]
        ];
    }

    public function allowHeaders() : array
    {
        return [
            "Accept", "Origin", "X-Auth-Token", "content-type",
            "Content-Type", "Authorization", "X-Requested-With",
            "Accept-Language", "Last-Event-ID", "Accept-Language",
            "Cookie", "Content-Length", "WWW-Authenticate", "X-XSRF-TOKEN",
            "withcredentials", "x-forwarded-for", "x-real-ip",
            "user-agent", "keep-alive", "host",
            "connection", "upgrade", "dnt", "if-modified-since", "cache-control",
            "x-compress"
        ];
    }

    public static function get()
    {
        return Yii::$app->getModule('findyouapi');
    }
}