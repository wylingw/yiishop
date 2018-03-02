<?php

namespace backend\controllers;


use backend\models\Brand;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\UploadedFile;
// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;

class BrandController extends Controller
{
    public $enableCsrfValidation = false;

    //列表页
    public function actionIndex()
    {
        //分页
        $query = Brand::find();
        //实例化
        $pager = new Pagination();
        //总条数
        $pager->totalCount = $query->count();
        //每页条数
        $pager->defaultPageSize = 3;
        //获取所有数据
        $brands = $query->offset($pager->offset)->limit($pager->limit)->orderBy('id desc')->all();
        //调用视图,分配数据
        return $this->render('index', ['brands' => $brands, 'pager' => $pager]);
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
//            var_dump($request->post());
            $model->load($request->post());
            $model->is_deleted = 0;
            if ($model->validate()) {//验证通过
                //保存
//                var_dump($model->logo);die();
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
            if ($model->validate()) {//验证通过
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
        $model = Brand::findOne(['id' => $id]);
        $model->is_deleted = 1;
        $model->save();
        return $this->redirect(['brand/index']);
    }

    //处理webuploader上传图片
    public function actionLogoUpload()
    {
        //实例化上传图片
        $uploadedFile = UploadedFile::getInstanceByName('file');
        //保存文件
        $fileName = '/upload/' . uniqid() . '.' . $uploadedFile->extension;
        $res = $uploadedFile->saveAs(\Yii::getAlias('@webroot') . $fileName);
        if ($res) {
            //图片上传到七牛云
            // 需要填写你的 Access Key 和 Secret Key
            $accessKey = "IUIg3xvfZl9XNi-VOVc36zOFD5q2vFBYvQHef4gY";
            $secretKey = "ZnfIqCNx1sPF4J75Tbmg8yZKriqbStB--eOJ41XY";
            $bucket = "yiishop";
            // 构建鉴权对象
            $auth = new Auth($accessKey, $secretKey);
            // 生成上传 Token
            $token = $auth->uploadToken($bucket);
            // 要上传文件的本地路径
            $filePath = \Yii::getAlias('@webroot') . $fileName;
            // 上传到七牛后保存的文件名
            $key = $fileName;
            // var_dump($key);
            // 初始化 UploadManager 对象并进行文件的上传。
            $uploadMgr = new UploadManager();
            // 调用 UploadManager 的 putFile 方法进行文件的上传。
            list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
            if ($err == null) {
                //上传七牛云成功
                //访问地址格式http://<domain>/<key>
                return json_encode([

                    'url' => "http://p4t1ymvs7.bkt.clouddn.com/{$key}"
                ]);
            }

        }
    }

    //图片上传到七牛云
//    public function actionQi()
//    {
//
//        // 需要填写你的 Access Key 和 Secret Key
//        $accessKey = "IUIg3xvfZl9XNi-VOVc36zOFD5q2vFBYvQHef4gY";
//        $secretKey = "ZnfIqCNx1sPF4J75Tbmg8yZKriqbStB--eOJ41XY";
//        $bucket = "yiishop";
//        // 构建鉴权对象
//        $auth = new Auth($accessKey, $secretKey);
//        // 生成上传 Token
//        $token = $auth->uploadToken($bucket);
//        // 要上传文件的本地路径
//        $filePath = './php-logo.png';
//        // 上传到七牛后保存的文件名
//        $key = './php-logo.png';
//        // 初始化 UploadManager 对象并进行文件的上传。
//        $uploadMgr = new UploadManager();
//        // 调用 UploadManager 的 putFile 方法进行文件的上传。
//        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
//        echo "\n====> putFile result: \n";
//        if ($err !== null) {
//            var_dump($err);
//        } else {
//            var_dump($ret);
//        }
//    }
}