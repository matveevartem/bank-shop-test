<?php

namespace app\modules\hosting;

use yii\web\AssetBundle;
use yii\web\AssetManager;

class ModuleAsset extends AssetBundle
{
    public $sourcePath = '@moduleName/assets';

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
        'yii\web\JqueryAsset',
    ];

    public $css = [
        'css/style.css',
    ];

    public $js = [
        'js/script.js',
    ];

    public $jsOptions = [
        'position' => \yii\web\View::POS_HEAD,
    ];

    public $publishOptions = [
        'only' => [
            'css/*',
            'js/*',
        ]
    ];

    public function init()
    {
        parent::init();
    }
}