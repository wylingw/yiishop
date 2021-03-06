<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleDetail;
use backend\models\ArticleForm;
use yii\data\Pagination;
use yii\web\Controller;

class ArticleController extends Controller
{
    public function actionIndex()
    {
        //分页
        $query = Article::find()->where(['is_deleted' => 0]);
        //实例化
        $pager = new Pagination();
        //总条数
        $pager->totalCount = $query->count();
        //每页条数
        $pager->defaultPageSize = 3;
        //获取所有数据
        $articles = $query->offset($pager->offset)->limit($pager->limit)->orderBy('id desc')->all();
        //调用视图,分配数据
        return $this->render('index', ['articles' => $articles, 'pager' => $pager]);

    }


    //添加
    public function actionAdd()
    {
        //实例化
        $request = \Yii::$app->request;
        $model = new Article();
        $articleDetail = new ArticleDetail();
        //判断
        if ($request->isPost) {
            //分开接收数据
            $model->load($request->post());
            $articleDetail->load($request->post());
            $model->is_deleted = 0;
            $model->create_time = time();
            if ($model->validate()) {//验证通过
                //分开保存
                $articleDetail->save();
                $model->save();
                //设置提示信息
                \Yii::$app->session->setFlash('success', '添加成功');
                //跳转
                return $this->redirect(['article/index']);

            } else {
                //设置错误提示信息
                var_dump($model->getErrors());
                exit();
            }
        }

        //调用视图，分配数据
        return $this->render('add', ['model' => $model, 'articleDetail' => $articleDetail]);
    }

    //修改
    public function actionEdit($id)
    {
        //实例化
        $request = \Yii::$app->request;
        $model = Article::findOne(['id' => $id]);
        $articleDetail = ArticleDetail::findOne(['article_id' => $id]);
        //判断
        if ($request->isPost) {
            //接收数据
            $model->load($request->post());
            $model->is_deleted = 0;
            $model->create_time = time();
            if ($model->validate()) {//验证通过
                //保存
                $articleDetail->save();
                $model->save();
                //设置提示信息
                \Yii::$app->session->setFlash('success', '修改成功');
                //跳转
                return $this->redirect(['article/index']);

            } else {
                //设置错误提示信息
                var_dump($model->getErrors());
                exit();
            }
        }

        //调用视图，分配数据
        return $this->render('edit', ['model' => $model, 'articleDetail' => $articleDetail]);
    }

    //删除
    public function actionDelete($id)
    {
        $model = Article::findOne(['id' => $id]);
        if ($model) {
            $model->is_deleted = 1;
            $model->save();
            echo 1;
        } else {
            echo 0;
        }


    }

    //应用ueditor
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix" => "http://admin.yiishop.com",//图片访问路径前缀
                    "imagePathFormat" => "/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}",//上传保存路径
                    "imageRoot" => \Yii::getAlias("@webroot"),
                ],
            ]
        ];
    }
}