<?php

namespace backend\controllers;


use backend\models\Brand;
use yii\web\Controller;
use yii\web\UploadedFile;

class BrandController extends Controller
{
    //列表页
    public function actionIndex()
    {
        //获取所有数据
        $brands = Brand::find()->all();
        //调用视图,分配数据
        return $this->render('index', ['brands' => $brands]);
    }

    //添加品牌
    public function actionAdd()
    {
        //实例化
        $request = \Yii::$app->request;
        $model = new Brand();
        //判断
        if ($request->isPost) {
            //接收数据
            $model->load($request->post());
            $model->is_deleted = 0;
            //实例化上传组件
            $model->logo = UploadedFile::getInstance($model, 'logo');
            if ($model->validate()) {//验证通过
                //保存上传文件
                $file = '/upload/' . uniqid() . '.' . $model->logo->extension;
                if ($model->logo->saveAs(\Yii::getAlias('@webroot') . $file, 0)) {
                    $model->logo = $file;
                }

                //保存
                $model->save();
                //设置提示信息
                \Yii::$app->session->setFlash('success', '添加成功');
                //跳转
                return $this->redirect(['brand/index']);

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
        $model = Brand::findOne(['id' => $id]);
        //判断
        if ($request->isPost) {
            //接收数据
            $model->load($request->post());
            $model->is_deleted = 0;
            //实例化上传组件
            $model->logo = UploadedFile::getInstance($model, 'logo');
            if ($model->validate()) {//验证通过
                //保存上传文件
                $file = '/upload/' . uniqid() . '.' . $model->logo->extension;
                if ($model->logo->saveAs(\Yii::getAlias('@webroot') . $file, 0)) {
                    $model->logo = $file;
                }
                //保存
                $model->save();
                //设置提示信息
                \Yii::$app->session->setFlash('success', '修改成功');
                //跳转
                return $this->redirect(['brand/index']);

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
        $request = \Yii::$app->request;
        $model = Brand::findOne(['id' => $id]);
        $model->is_deleted = 1;
        $model->save();
        return $this->redirect(['brand/index']);
    }
}