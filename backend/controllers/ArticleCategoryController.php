<?php

namespace backend\controllers;

use backend\models\ArticleCategory;
use yii\data\Pagination;
use yii\web\Controller;

class ArticleCategoryController extends Controller
{
    //列表页
    public function actionIndex()
    {
        //分页
        $query = ArticleCategory::find()->where(['is_deleted'=>0]);
        //实例化
        $pager = new Pagination();
        //总条数
        $pager->totalCount = $query->count();
        //每页条数
        $pager->defaultPageSize = 2;
        //获取所有数据
        $arts = $query->offset($pager->offset)->limit($pager->limit)->orderBy('id desc')->all();
        //调用视图,分配数据
        return $this->render('index', ['arts' => $arts, 'pager' => $pager]);
    }

    public function actionAdd()
    {
        //实例化
        $request = \Yii::$app->request;
        $model = new ArticleCategory();
        //判断
        if ($request->isPost) {
            //接收数据
            $model->load($request->post());
            $model->is_deleted = 0;
            if ($model->validate()) {//验证通过
                //保存
                $model->save();
                //设置提示信息
                \Yii::$app->session->setFlash('success', '添加成功');
                //跳转
                return $this->redirect(['article-category/index']);

            } else {
                //设置错误提示信息
                var_dump($model->getErrors());
                exit();
            }
        }

        //调用视图，分配数据
        return $this->render('add', ['model' => $model]);
    }

    //修改
    public function actionEdit($id)
    {
        //实例化
        $request = \Yii::$app->request;
        $model = ArticleCategory::findOne(['id' => $id]);
        //判断
        if ($request->isPost) {
            //接收数据
            $model->load($request->post());
            $model->is_deleted = 0;
            if ($model->validate()) {//验证通过
                //保存
                $model->save();
                //设置提示信息
                \Yii::$app->session->setFlash('success', '修改成功');
                //跳转
                return $this->redirect(['article-category/index']);

            } else {
                //设置错误提示信息
                var_dump($model->getErrors());
                exit();
            }
        }

        //调用视图，分配数据
        return $this->render('edit', ['model' => $model]);
    }

    //删除
    public function actionDelete($id)
    {
        $model = ArticleCategory::findOne(['id' => $id]);
        if ($model) {
            $model->is_deleted = 1;
            $model->save();
            echo 1;
        } else {
            echo 0;
        }

    }
}