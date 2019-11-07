<?php

namespace zikwall\findyouapi;

use Yii;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        Yii::setAlias('@findyouapi', __DIR__);
    }
}
