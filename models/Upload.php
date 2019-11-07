<?php

namespace zikwall\findyouapi\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class Upload extends Model
{
    public function formName()
    {
        return '';
    }
    /**
     * @var UploadedFile
     */
    public $imageFile;

    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
        ];
    }

    public function upload($uploadFilePath)
    {
        if ($this->validate()) {
            return $this->imageFile->saveAs($uploadFilePath);
        } else {
            return false;
        }
    }
}