<?php

declare(strict_types=1);

namespace app\modules\api\controllers;

use yii\rest\ActiveController;
use app\modules\api\actions\IndexAction;
use app\modules\hosting\models\Image;

class DefaultController extends ActiveController
{    
    public $modelClass = Image::class;

    /**
     * {@inheritdoc}
     */
    public function actions()
    {

        $actions = parent::actions();

        // Созласно ТЗ оставляем только index, view
        unset(
            $actions['create'],
            $actions['update'],
            $actions['delete']
        );

        // Переопределяем action, что бы добавить пагинацию и сортировку
        $actions['index'] = IndexAction::class;

        return $actions;
    }

    /**
     * {@inheritdoc}
     */
    public function beforeAction($action)
    {
        $parent = parent::beforeAction($action);

        if ($action instanceof IndexAction) {
            $action->modelClass = Image::class;
        }

        return $parent;
    }
}
