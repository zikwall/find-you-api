<?php

namespace zikwall\findyouapi\controllers;

use Yii;
use zikwall\findyouapi\models\Upload;
use zikwall\findyouapi\Module;
use yii\rest\Controller;
use yii\web\UploadedFile;

class RecognizeController extends Controller
{
    public function beforeAction($action)
    {
        foreach (Module::module()->responseHeaders as $header) {
            Yii::$app->response->headers->set($header[0], $header[1]);
        }

        return parent::beforeAction($action);
    }

    public function actionConfirm($face)
    {
        return $this->asJson([
            'res' => self::confirm($face)
        ]);
    }

    public function actionFace()
    {
        if (Yii::$app->request->getIsOptions()) {
            return true;
        }

        $model = new Upload();

        if (Yii::$app->request->isPost) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');

            $targetFile = sprintf('%s/%s.%s',
                Yii::getAlias(Module::module()->imageUploadPath),
                $model->imageFile->baseName,
                $model->imageFile->extension
            );

            if ($model->upload($targetFile)) {

                $res = self::send($model->imageFile, $targetFile, [
                    'ext' => $model->imageFile->getExtension(),
                ]);

                return $this->asJson(['file' => $targetFile, 'res' => $res]);
            }

            return $this->asJson(['not uploaded', 'file' => $targetFile, 'error' => $model->getErrors(), 'post' => $_POST]);
        }

        return $this->asJson([
            'not POST'
        ]);
    }

    public static function confirm($id)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, sprintf('%s/upload3?id=%d', Module::module()->handleUrl, $id));
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT ,550000000);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5500000000); //timeout in seconds
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.101 Safari/537.36',
            'Expect:',
            'Content-Type: multipart/form-data',
            sprintf('session-key: %s', Module::module()->securityToken),
            sprintf('user-id: %d', Module::module()->userId)
        ]);

        $response = curl_exec($curl);

        if($errno = curl_errno($curl)) {
            $error_message = curl_strerror($errno);
            echo "cURL error ({$errno}):\n {$error_message}";
            exit;
        }

        $response = json_decode($response, true);

        curl_close($curl);

        return $response;
    }

    public static function send($filename, $targetFile, $options = [])
    {
        if(!is_file($targetFile)) {
            throw new \Exception('Target file is not a file or not found!');
        }

        $uploadRequest = [
            'uploaded_photo' => new \cURLFile($targetFile, $options['ext'], $filename),
        ];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, sprintf('%s/%s', Module::module()->handleUrl, 'upload2'));
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT ,550000000);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5500000000); //timeout in seconds
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $uploadRequest);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.101 Safari/537.36',
            'Expect:',
            'Content-Type: multipart/form-data',
            sprintf('session-key: %s', Module::module()->securityToken),
            sprintf('user-id: %d', Module::module()->userId)
        ]);

        $response = curl_exec($curl);

        if($errno = curl_errno($curl)) {
            $error_message = curl_strerror($errno);
            echo "cURL error ({$errno}):\n {$error_message}";
            exit;
        }

        $response = json_decode($response, true);

        curl_close($curl);

        return $response;
    }
}
