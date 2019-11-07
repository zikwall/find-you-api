<?php

namespace zikwall\findyouapi\controllers;

use zikwall\findyouapi\Module;
use yii\rest\Controller;

class RecognizeController extends Controller
{
    public function beforeAction($action)
    {
        foreach (Module::module()->responseHeaders as $header) {
            Yii::$app->response->headers->set($header[0], $header[1]);
        }

        return parent::beforeAction($action);
    }

    public function actionFace()
    {
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }
        
        
    }
}