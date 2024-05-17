<?php

declare(strict_types=1);

namespace app\controllers;

use Yii;
use yii\web\Controller;

class BaseController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        
        Yii::$app->language = Yii::$app->request->cookies->getValue('_language','en-US');
    }
}
