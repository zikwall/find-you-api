<?php


namespace zikwall\findyouapi\controllers;

use Yii;
use yii\rest\Controller;

class History extends Controller
{
    public function actionPut()
    {
        if (Yii::$app->request->isPost) {
            // save
        }
    }

    public function actionGet($user)
    {
        // get history for user
    }
}
