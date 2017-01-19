<?php
namespace richardfan\ckeditor;

use yii\web\AssetBundle;

class CKEditorWidgetAsset extends AssetBundle
{
    public $sourcePath = '@vendor/richardfan1126/yii2-ckeditor-widget/src/assets/';

    public $depends = [
        'richardfan\ckeditor\CKEditorAsset'
    ];

    public $js = [
        'dosamigos-ckeditor.widget.js'
    ];
}
