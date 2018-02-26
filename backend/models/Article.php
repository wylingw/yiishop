<?php

namespace backend\models;

use yii\db\ActiveRecord;

class Article extends ActiveRecord
{
    //设置标签的名称
    public function attributeLabels()
    {
        return [
            'name' => '名称',
            'intro' => '简介',
            'article_category_id' => '文章分类',
            'sort' => '排序',
        ];
    }

    //设置验证规则
    public function rules()
    {
        return [
            [['name', 'intro', 'sort','article_category_id'], 'required'],
            [['intro'], 'string'],
            [['is_deleted', 'sort','article_category_id'], 'integer'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    //连表查询
    public function getArticleCategory()
    {
        return $this->hasOne(ArticleCategory::className(), ['id' => 'article_category_id']);
    }

    public static function getArticleCategoryOptions()
    {
        $rows = ArticleCategory::find()->all();
        $tmp = [];
        foreach ($rows as $row) {
            $tmp[$row->id] = $row->name;
        }
        return $tmp;
    }
}