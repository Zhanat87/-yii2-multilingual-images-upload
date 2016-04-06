<?php

namespace zhanat\yii2\galleryManager\migrations;

use yii\db\Schema;
use yii\db\Migration;

class m140930_003227_gallery_manager extends Migration
{

    public $tableName = '{{%gallery_image}}';

    public function up()
    {

        $this->createTable(
            $this->tableName,
            array(
                'id' => Schema::TYPE_PK,
                'type' => Schema::TYPE_STRING,
                'ownerId' => Schema::TYPE_STRING . ' NOT NULL',
                'rank' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
                'name' => Schema::TYPE_STRING . '(2000)',
                'description' => Schema::TYPE_TEXT,
                'status' => Schema::TYPE_SMALLINT . '(1)',
                'created_at' => Schema::TYPE_INTEGER,
                'updated-at' => Schema::TYPE_INTEGER,
            )
        );
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }

}