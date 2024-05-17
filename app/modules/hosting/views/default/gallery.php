<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\file\FileInput;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use app\modules\hosting\ModuleAsset;

ModuleAsset::register($this);

Yii::$app->params['bsVersion'] = '5';
?>

<span id='image-size'></span>

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="container">
                <div class="row">
                    <h5><?= Yii::t('hosting', 'Upload form') ?></h5>
                </div>
                <div class="row mb-1 upload-form">
                    <?php $form = ActiveForm::begin([
                        'action' => Url::toRoute(['/hosting/upload']),
                        'options' => [
                            'enctype' => 'multipart/form-data'
                        ],
                    ]) ?>
                    <?= $form->field($formModel, 'files[]')->fileInput(['multiple' => true]) ?>
                    <button><?= Yii::t('hosting', 'Upload') ?></button>
                    <?php ActiveForm::end() ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="container">
                <div class="row pt-5">
                    <h5><?= Yii::t('hosting', 'Gallery') ?></h5>
                </div>
                <div class="row">
                    <?= GridView::widget([
                        'dataProvider' => $galleryDataProvider,
                        'layout' => "{summary}\n{items}\n{pager}",
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'class' => 'yii\grid\DataColumn',
                                'format' => 'raw',
                                'label' => Yii::t('hosting', 'Preview'),
                                'value' => function ($img) {
                                    return Html::a(
                                        Html::img($img->getPreviewUrl(), [
                                            'id' => 'thumb-' . $img->getId(),
                                            'class' => 'thumb',
                                            'alt' => $img->getOriginalName() . '.' . $img->getExtension(),
                                            'title' => Yii::t('hosting', 'Click to open full size'),
                                        ]),
                                        $img->getUrl(),
                                        [
                                            'target' => '_blank',
                                            'data-pjax' => 0,
                                        ]
                                    );
                                },
                            ],
                            [
                                'class' => yii\grid\DataColumn::class,
                                'label' => Yii::t('hosting', 'Original file name'),
                                'attribute' => 'original_name',
                                'value' => function ($img) {
                                    return $img->getOriginalName() . '.' . $img->getExtension();
                                },
                            ],
                            [
                                'attribute' => 'created_at',
                                'label' => Yii::t('hosting', 'Created at'),
                                'format' => ['date', 'php:d.m.Y H:i:s']
                            ],
                            [
                                'class' => yii\grid\ActionColumn::class,
                                'template' => '{download}',

                                'buttons' => [

                                    'download' => function ($url, $img) {

                                        return Html::a(Yii::t('hosting', 'Download as ZIP'), $url, [
                                            'title' => Yii::t('yii', 'Download'),
                                        ]);
                                    }
                                ],
                            ]
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>