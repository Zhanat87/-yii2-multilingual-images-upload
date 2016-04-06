<?php

namespace zhanat\yii2\galleryManager;

use Yii;
use yii\base\Exception;
use yii\base\Widget;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * Widget to manage gallery.
 * Requires Twitter Bootstrap styles to work.
 *
 * @author Iskakov Zhanat <iskakov_zhanat@mail.ru>
 */
class GalleryManager extends Widget
{

    /** @var ActiveRecord */
    public $model;

    /** @var string */
    public $behaviorName;

    /** @var GalleryBehavior Model of gallery to manage */
    protected $behavior;

    /** @var string Route to gallery controller */
    public $apiRoute = FALSE;

    public $options = [];

    public function init()
    {
        parent::init();
        $this->behavior = $this->model->getBehavior($this->behaviorName);
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        $i18n = Yii::$app->i18n;
        $i18n->translations['galleryManager/*'] = [
            'class'          => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath'       => '@zhanat/yii2/galleryManager/messages',
            'fileMap'        => [],
        ];
    }

    /** Render widget */
    public function run()
    {
        if ($this->apiRoute === NULL) {
            throw new Exception('$apiRoute must be set.', 500);
        }

        $images = [];
        foreach ($this->behavior->getImages() as $image) {
            $images[] = [
                'id'          => $image->id,
                'rank'        => $image->rank,
                'name'        => $image->name ? unserialize($image->name) : NULL,
                'names'       => $image->name ? unserialize($image->name) : NULL,
                'description' => (string)$image->description,
                'preview'     => $image->getUrl('preview'),
            ];
        }

        $baseUrl = [
            $this->apiRoute,
            'type'         => $this->behavior->type,
            'behaviorName' => $this->behaviorName,
            'galleryId'    => $this->behavior->getGalleryId(),
        ];

        $opts = [
            'hasName'          => $this->behavior->hasName ? TRUE : FALSE,
            'hasDesc'          => $this->behavior->hasDescription ? TRUE : FALSE,
            'uploadUrl'        => Url::to($baseUrl + ['action' => 'ajaxUpload']),
            'deleteUrl'        => Url::to($baseUrl + ['action' => 'delete']),
            'updateUrl'        => Url::to($baseUrl + ['action' => 'changeData']),
            'arrangeUrl'       => Url::to($baseUrl + ['action' => 'order']),
            'nameLabel'        => Yii::t('galleryManager/main', 'Name'),
            'descriptionLabel' => Yii::t('galleryManager/main', 'Description'),
            'photos'           => $images,
            'languages'        => $this->behavior->languages,
            'language'         => mb_substr(Yii::$app->language, 0, 2),
        ];

        $opts = Json::encode($opts);
        $view = $this->getView();
        GalleryManagerAsset::register($view);
        $view->registerJs("$('#{$this->id}').galleryManager({$opts});");

        $this->options['id'] = $this->id;
        $this->options['class'] = 'gallery-manager';

        return $this->render('galleryManager');
    }

}