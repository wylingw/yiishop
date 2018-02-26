<?php

namespace backend\models;

use yii\db\ActiveRecord;

class ArticleCategory extends ActiveRecord
{
    public function rules()
    {
        return [
            [['name', 'intro', 'sort'], 'required'],
            [['intro'], 'string'],
            [['is_deleted', 'sort'], 'integer'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'intro' => '简介',
            'sort' => '排序',
            'is_deleted' => '状态',
        ];
    }
}