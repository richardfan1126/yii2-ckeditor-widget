<?php
namespace richardfan\ckeditor;

use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;

class CKEditor extends InputWidget
{
    use CKEditorTrait;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->initOptions();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->hasModel()) {
            echo Html::activeTextarea($this->model, $this->attribute, $this->options);
        } else {
            echo Html::textarea($this->name, $this->value, $this->options);
        }
        $this->registerPlugin();
    }

    /**
     * Registers CKEditor plugin
     * @codeCoverageIgnore
     */
    protected function registerPlugin()
    {
        $js = [];

        $view = $this->getView();

        CKEditorWidgetAsset::register($view);

        $id = $this->options['id'];
        
        $assetBundle = \Yii::$app->getAssetManager()->getBundle(KCFinderAsset::className());
        $assetUrl = $assetBundle->baseUrl;
        
        $this->clientOptions['filebrowserBrowseUrl'] = $assetUrl . '/browse.php?opener=ckeditor&type=files';
        $this->clientOptions['filebrowserImageBrowseUrl'] = $assetUrl . '/browse.php?opener=ckeditor&type=images';
        $this->clientOptions['filebrowserFlashBrowseUrl'] = $assetUrl . '/browse.php?opener=ckeditor&type=flash';
        $this->clientOptions['filebrowserUploadUrl'] = $assetUrl . '/upload.php?opener=ckeditor&type=files';
        $this->clientOptions['filebrowserImageUploadUrl'] = $assetUrl . '/upload.php?opener=ckeditor&type=images';
        $this->clientOptions['filebrowserFlashUploadUrl'] = $assetUrl . '/upload.php?opener=ckeditor&type=flash';

        $options = $this->clientOptions !== false && !empty($this->clientOptions)
            ? Json::encode($this->clientOptions)
            : '{}';

        $js[] = "CKEDITOR.replace('$id', $options);";
        $js[] = "dosamigos.ckEditorWidget.registerOnChangeHandler('$id');";

        if (isset($this->clientOptions['filebrowserUploadUrl']) || isset($this->clientOptions['filebrowserImageUploadUrl'])) {
            $js[] = "dosamigos.ckEditorWidget.registerCsrfImageUploadHandler();";
        }

        $view->registerJs(implode("\n", $js));
    }
}
