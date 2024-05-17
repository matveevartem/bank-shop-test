<?php

declare(strict_types=1);

namespace app\modules\hosting;

use Yii;

class Module extends \yii\base\Module
{
    public function init()
    {
        parent::init();

        $this->setAliases([
            '@moduleName' => __DIR__,
        ]);

        Yii::configure($this, require __DIR__ . DIRECTORY_SEPARATOR  . 'config' . DIRECTORY_SEPARATOR  . 'settings.php');

        $this->registerTranslations();
    }

    
    public function registerTranslations(): void
    {
        Yii::$app->i18n->translations['hosting*'] = [
            'class'          => \yii\i18n\PhpMessageSource::class,
            'basePath'       => '@app/modules/hosting/messages',
            'fileMap'        => [
                'hosting/gallery' => 'hosting.php',
            ],
        ];
    }
}
