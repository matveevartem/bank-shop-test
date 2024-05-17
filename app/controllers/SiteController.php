<?php

declare(strict_types=1);

namespace app\controllers;

use Yii;
use yii\web\Response;
use yii\web\Cookie;
use yii\helpers\Url;
use app\controllers\BaseController;

class SiteController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => yii\web\ErrorAction::class,
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Redirect to gallery.
     *
     * @return string
     */
    public function actionIndex(): Response
    {
        return $this->redirect(Url::toRoute(['/hosting/gallery']));
    }

    /**
     * Switches language
     */
    public function actionLanguage(): Response
    {
        if (Yii::$app->request->isPost && Yii::$app->request->post('Language')) {
            Yii::$app->response->cookies->add(new Cookie([
                'name' => '_language',
                'value' => Yii::$app->request->post('Language'),
                'path' => '/',
                'expire' => time() + 3600,
            ]));

        }

        return $this->goBack();
    }
}
