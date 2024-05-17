<?php

declare(strict_types=1);

namespace app\modules\hosting\controllers;

use Yii;
use yii\web\UploadedFile;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use app\controllers\BaseController;
use app\modules\hosting\models\Image;
use app\modules\hosting\models\Gallery;

class DefaultController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['contentNegotiator'] = [
            'class' => \yii\filters\ContentNegotiator::class,
            'only' => ['download'],
            'formatParam' => '_format',
            'formats' => [
                'application/zip' => Response::FORMAT_RAW,
            ],
        ];

        return $behaviors;
    }

    /**
     * Redirects to /hosting/gallery
     */
    public function actionIndex(): Response
    {
        return $this->redirect(Url::toRoute(['/hosting/gallery']));
    }

    public function actionGallery(): string
    {
        $formModel = new Gallery();
        $galleryDataProvider = new ActiveDataProvider([
            'query' => Image::find(),
            'sort' => [
                'attributes' => [
                    'original_name',
                    'created_at'
                ],
                'defaultOrder' => [
                    Yii::$app->controller->module->params['defaultSortField'] => Yii::$app->controller->module->params['defaultSortDirection'],
                ],
            ],
            'pagination' => [
                'pageSize' => Yii::$app->controller->module->params['pageSize'],
            ],
        ]);

        return $this->render('gallery', [
            'formModel' => $formModel,
            'galleryDataProvider' => $galleryDataProvider,
        ]);
    }

    public function actionUpload(): Response
    {
        if (Yii::$app->request->isPost) {
            $model = new Gallery();
            $model->setFiles(UploadedFile::getInstances($model, 'files'));
            if ($model->upload()) {
                Yii::$app->getSession()->setFlash('message', 'Post published successfull');

                return $this->redirect([Url::toRoute('/hosting')]);
            }
        }

        throw new BadRequestHttpException("Upload error: Only the POST method is allowed");
    }

    /**
     * @param int $id image id
     */
    public function actionDownload(int $id): void
    {
        $image = Image::findOne($id);

        if (Gallery::prepareZip($image)) {
            Yii::$app->response->sendFile($image->getZipName(true), $image->getZipName());
            FileHelper::unlink($image->getZipName(true));
        }
    }
}