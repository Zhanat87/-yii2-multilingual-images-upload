<?php
/**
* Date: 06.04.2016
* Time: 13:58
*
* @author Iskakov Zhanat <iskakov.zhanat@gmail.com>
*/

namespace zhanat\yii2\galleryManager;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%gallery_image}}".
 *
 * @property integer $id
 * @property string $type
 * @property integer $ownerId
 * @property integer $rank
 * @property string $name
 * @property string $description
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class GalleryImageModel extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%gallery_image}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ownerId'], 'required'],
            [['rank', 'status', 'created_at', 'updated_at', 'ownerId'], 'integer'],
            [['description'], 'string'],
            [['type'], 'string', 'max' => 255],
            [['name'], 'string', 'max' => 2000],
            [['rank'], 'default', 'value' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('galleryManager/main', 'field_id'),
            'type' => Yii::t('galleryManager/main', 'field_type'),
            'ownerId' => Yii::t('galleryManager/main', 'field_ownerId'),
            'rank' => Yii::t('galleryManager/main', 'field_rank'),
            'name' => Yii::t('galleryManager/main', 'field_name'),
            'description' => Yii::t('galleryManager/main', 'field_description'),
            'status' => Yii::t('galleryManager/main', 'field_status'),
            'created_at' => Yii::t('galleryManager/main', 'field_created_at'),
            'updated_at' => Yii::t('galleryManager/main', 'field_updated_at'),
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => ['created_at'],
                    self::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($this->rank = 0) {
            $this->rank = $this->id;
            $this->save(false);
        }
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            /**
             * @fixme:
             * значение статуса по-умолчанию при создании
             */
            return true;
        }
        return false;
    }

}