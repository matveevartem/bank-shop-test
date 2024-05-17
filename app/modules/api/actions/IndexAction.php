<?php

declare(strict_types=1);

namespace app\modules\api\actions;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

class IndexAction extends \yii\rest\IndexAction
{
    public $modelClass = ActiveRecord::class;

    /**
     * @return ActiveDataProvider
     */
    public function run()
    {
        $page = intval(Yii::$app->request->get('page', 1));

        $dataProvider = new ActiveDataProvider( [
            'query' => $this->modelClass::find(),
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
                'page' => --$page,
            ],
        ]);

        return $dataProvider;
    }
}