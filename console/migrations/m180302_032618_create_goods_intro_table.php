<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_intro`.
 */
class m180302_032618_create_goods_intro_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('goods_intro', [
            'goods_id' => $this->primaryKey(),
            'content' => $this->text()->notNull()->comment('商品描述'),

//            字段名	类型	注释
//goods_id	int	商品id
//content	text	商品描述
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('goods_intro');
    }
}
