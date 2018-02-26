<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_category`.
 */
class m180226_113914_create_article_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article_category', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull()->comment('名称'),
            'intro' => $this->text()->notNull()->notNull()->comment('简介'),
            'sort' => $this->integer()->notNull()->comment('排序'),
            'is_deleted' => $this->smallInteger(1)->comment('状态0正常 1删除'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article_category');
    }
}
