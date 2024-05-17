<?php

declare(strict_types=1);

namespace app\modules\hosting\models;

use Yii;

/**
 * This is the model class for table "image".
 *
 * @property int $id
 * @property string $original_name
 * @property string $real_name
 * @property string $extension
 * @property string $created_at
 */
class Image extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'image';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['original_name', 'real_name', 'extension'], 'required'],
            [['created_at'], 'safe'],
            [['original_name', 'real_name'], 'string', 'max' => 255],
            [['extension'], 'string', 'max' => 4],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'original_name' => Yii::t('hosting', 'Original file name'),
            'real_name' => Yii::t('hosting', 'Real file name'),
            'extension' => 'File extension',
            'created_at' => 'Upload time',
        ];
    }

    /**
     * @return int $id DB row identifier
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string original transliterated file name
     */
    public function getOriginalName(): string
    {
        return $this->original_name;
    }

    /**
     * @param string $filename original transliterated file name
     * @return void
     */
    public function setOriginalName(string $filename): void
    {
        $this->original_name = $filename;
    }

    /**
     * @return string real file name
     */
    public function getRealName(): string
    {
        return $this->real_name;
    }

    /**
     * @param string $filename real file name
     * @return void
     */
    public function setRealName(string $filename): void
    {
        $this->real_name = $filename;
    }

    /**
     * @return string file extension
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * @param string $extension file extension
     * @return void
     */
    public function setExtension(string $extension): void
    {
        $this->extension = $extension;
    }

    public function getTimestamp(): string
    {
        return $this->created_at;
    }

    /**
     * Returns file name with extension (and with path)
     * @param bool $withPath true if return filename with path
     * @return string
     */
    public function getFullName(bool $withPath = false): string
    {
        $filename = $this->getRealName()
            . '.'
            . $this->getExtension();

        return $withPath ? Yii::$app->controller->module->params['imgDir'] . $filename : $filename;
    }

    /**
     * Returns zip file name (and with path)
     * @param bool $withPath true if return zip fila name with path
     * @return string
     */
    public function getZipName(bool $withPath = false): string
    {
        $filename = $this->getOriginalName()
            . '.'
            . 'zip';

        return $withPath ? Yii::$app->controller->module->params['tmpDir'] . $filename : $filename;
    }

    /**
     * Returns web path to image
     * @return string
     */
    public function getUrl(): string
    {
        $filename = $this->getFullName();

        return '/' . Yii::$app->controller->module->params['imgWeb'] . $filename;
    }

    /**
     * Returns web path to preview
     * @return string
     */
    public function getPreviewUrl(): string
    {
        $filename = $this->getRealName()
            . '.'
            . $this->getExtension();

        return '/' . Yii::$app->controller->module->params['prwWeb'] . $filename;
    }

    /**
     * @return array original size array
     * @throws \InvalidArgumentException
     */
    public function getOriginalSize(): array
    {
        $size = getimagesize($this->getFullName(true));
        if ($size === false) {
            throw new \InvalidArgumentException('Original size error: Invalid original image file');
        }

        return [
            'width' => intval($size[0]),
            'height' => intval($size[1]),
        ];
    }

    /**
     * @param int $originWidth original image width
     * @param int $originHeight original image height
     * @return array preview size array
     * @throws \InvalidArgumentException
     */
    public static function getPreviewSize(int $originWidth, int $originHeight): array
    {
        if (!$originWidth || !$originHeight) {
            throw new \InvalidArgumentException('Preview size error: Invalid original size value');
        }

        $prwWidth = Yii::$app->controller->module->params['prwWidth'];
        $prwHeight = Yii::$app->controller->module->params['prwHeight'];
        $ratio = $originWidth / $originHeight;
        //$prwWidth = floor($prwHeight * $ratio);

        return [
            'width' => $prwWidth,
            'height' => $prwHeight,
        ];
    }
}
