<?php

namespace backend\controllers;

use app\models\ArticleCategory;

class ArticleCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //获取所有数据
        $arts = ArticleCategory::find()->all();
        //列表页
        return $this->render('index', ['arts' => $arts]);
    }
    //添加
    

}
