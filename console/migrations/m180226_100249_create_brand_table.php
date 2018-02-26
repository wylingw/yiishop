<?php

use yii\db\Migration;

/**
 * Handles the creation of table `brand`.
 */
class m180226_100249_create_brand_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('brand', [
            'id' => $this->primaryKey(),
            'name' => $this->string(20)->notNull()->comment('名称'),
            'intro' => $this->text()->notNull()->comment('简介'),
            'logo' => $this->string(50)->notNull()->comment('LOGO'),
            'sort' => $this->integer()->comment('排序'),
            'is_deleted' => $this->smallInteger(1)->comment('状态'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('brand');
    }
}
