<?php

declare(strict_types=1);

namespace app\modules\hosting\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\helpers\Inflector;
use yii\helpers\FileHelper;
use app\modules\hosting\models\Image;

class Gallery extends Model
{
    /**
     * @var UploadedFile[]
     */
    public $files;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                ['files'],
                'file',
                'skipOnEmpty' => false,
                'extensions' => 'png, jpg, gif',
                'maxFiles' => 5,
                //'tooMany' => 'Error'
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'files' => Yii::t('hosting', 'Select files to upload') . 
                ' (' .
                Yii::t('hosting', 'Max 5 files') .
                ')',
        ];
    }

    public function upload(): bool
    {
        if ($this->validate()) {
            foreach ($this->getFiles() as $file) {
                $this->saveUniqueImage($file);
            }

            return true;
        } else {

            return false;
        }
    }

    /**
     * @return UploadedFile[] array of uploaded files
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * @param UploadedFile[] $files array of uploaded files
     * @return void
     */
    public function setFiles(array $files): void
    {
        $this->files = $files;
    }

    /**
     * @param string $filename uploaded file name
     * @return Image
     */
    protected function getUniqueImage(string $filename): Image
    {
        $suffix = '';
        $image = new Image();
        $count = 0;

        do {
            $suffix = $count ? '-' . $count : '';
            $uniqueName = md5($filename . $suffix);
            $count = Image::find()->where(['real_name' => $uniqueName])->count('id');
        } while ($count);

        $originalName = strtolower(Inflector::transliterate($filename));

        $image = new Image();
        $image->setOriginalName($originalName . $suffix);
        $image->setRealName($uniqueName);

        return $image;
    }

    /**
     * @param UploadedFile $file uploaded file
     * @return bool
     */
    protected function saveUniqueImage(UploadedFile $file): bool
    {
        $image = $this->getUniqueImage($file->getBaseName());
        $image->setExtension($file->getExtension());

        if ($file->saveAs($image->getFullName(true))) {
            $image->save();
            $this->savePreview($image);
        }

        return false;
    }

    /**
     * Resizes current image and save it in the preview folder
     * @param Image $image source image file
     * @return bool true if the preview was saved
     * @throws \InvalidArgumentException
     * @throws \yii\web\ServerErrorHttpException
     */
    protected function savePreview(Image $image): bool
    {
        $source = $image->getFullName(true);
        $destination = Yii::$app->controller->module->params['prwDir'] . $image->getFullName();
        $ext = $image->getExtension();
        $oldSize = $image->getOriginalSize();
        $newSize =  Image::getPreviewSize($oldSize['width'], $oldSize['height']);
        $coalesce = Yii::$app->module->params['tmpDir'] . str_replace('.' . $ext, '', $source) . '-cls' . '.' . $ext;
        $from = $oldSize['width'] . 'x' . $oldSize['height'];
        $to = $newSize['width'] . 'x' . $newSize['height'];
        $res1 = '';
        $res2 = '';

        $cmd1 = "/usr/bin/convert {$source} -coalesce -background none {$coalesce}";
        $cmd2 = "/usr/bin/convert -size {$from} {$coalesce} -background none -thumbnail {$to} {$destination}";

        if (
            ($res1 = shell_exec($cmd1)) ||
            ($res2 = shell_exec($cmd2))
        ) {
            FileHelper::unlink($coalesce);

            throw new \yii\web\ServerErrorHttpException(
                'Convert to preview, execution error: ' . "<br/>\n" . $res1 . "<br/>\n" . $res2);
        }

        FileHelper::unlink($coalesce);

        return true;
    }

    public static function prepareZip(Image $image): bool
    {
        $zip = new \ZipArchive();

        if($zip->open($image->getZipName(true), \ZipArchive::CREATE) !== TRUE) {
            throw new \Exception('Cannot create a zip file');
        }

        $addResult = $zip->addFile(
            $image->getFullName(true),
            $image->getOriginalName() . '.' . $image->getExtension()
        );

        $zip->close();

        if (!$addResult) {
            FileHelper::unlink($image->getZipName(true));

            throw new \yii\web\ServerErrorHttpException(
                'ZIP archiving error: Can\' add image to zip archive');
       }

       if (file_exists($image->getZipName(true))) {
            return true;
        } else {
            FileHelper::unlink($image->getZipName(true));

            throw new \yii\web\ServerErrorHttpException(
                'ZIP archiving error: Can\' create zip archive');
        }
    }
}