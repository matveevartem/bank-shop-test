<?php

declare(strict_types=1);

namespace app\modules\api;

use Yii;
use yii\base\Module as BaseModule;

class Module extends BaseModule
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        Yii::configure($this, require __DIR__ . DIRECTORY_SEPARATOR  . 'config' . DIRECTORY_SEPARATOR  . 'settings.php');
    }
}
