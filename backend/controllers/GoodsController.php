<?php

namespace backend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use yii\data\Pagination;
use yii\web\UploadedFile;

class GoodsController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        //搜索
        //实例化
        $request = \Yii::$app->request;
        $message = $request->get();
   // var_dump($message);exit();
      //var_dump($message['Goods']['name']);exit();
        $query = Goods::find()->where(['status' => 1]);
        if (!empty($message)) {

            if (isset($message['Goods']['name'])) {
                $query->andFilterWhere(['like', 'name', $message['Goods']['name']]);
            }
            if (isset($message['Goods']['sn'])) {
                $query->andFilterWhere(['like', 'sn', $message['Goods']['sn']]);
            }
            if (isset($message['Goods']['min'])) {
                $query->andFilterWhere(['<=', 'min', $message['Goods']['min']]);
            }
            if (isset($message['Goods']['max'])) {
                $query->andFilterWhere(['>=', 'max', $message['Goods']['max']]);
            }

        }

        //分页
        $pager = new Pagination();
        //总条数
        $pager->totalCount = $query->count();
        $pager->defaultPageSize = 3;
        //获取所有数据
        $goods = $query->offset($pager->offset)->limit($pager->limit)->orderBy('id desc')->all();
        $model = new Goods();
        //调用视图,分配数据
        return $this->render('index', ['goods' => $goods, 'pager' => $pager, 'model' => $model]);
    }

    //添加
    public function actionAdd()
    {
        //实例化
        $request = \Yii::$app->request;
        $model = new Goods();
        $goodsIntro = new GoodsIntro();
        $count = new GoodsDayCount();
        if ($request->isPost) {
            //接收数据
            $model->load($request->post());
            $goodsIntro->load($request->post());
            //处理数据
            $model->status = 1;
            $model->create_time = time();
            $count->day = date('Y-m-d', time());
            $num = GoodsDayCount::find()->where(['day' => $count->day])->count();
            $count->count = $num + 1;
            $number = sprintf("%05d", $count->count);//生成5位数，不足前面补0
            $model->sn = date('Ymd', $model->create_time) . $number;
            if ($model->validate()) {
                //保存
                $model->save();
                $goodsIntro->save();
                $count->save();
                //设置提示信息
                \Yii::$app->session->setFlash('success', '添加成功');
                //跳转
                return $this->redirect(['goods/index']);
            }
        }
        //查询所有节点数据
        $nodes = GoodsCategory::find()->asArray()->all();
        $nodes[] = ['id' => 0, 'parent_id' => 0, 'name' => '顶级分类'];
        //调用视图,分配数据
        return $this->render('add', ['model' => $model, 'goodsIntro' => $goodsIntro, 'nodes' => json_encode($nodes)]);

    }

    //修改
    public function actionEdit($id)
    {
        //实例化
        $request = \Yii::$app->request;
        $model = Goods::findOne(['id' => $id]);
        $goodsIntro = GoodsIntro::findOne(['goods_id' => $id]);
        if ($request->isPost) {
            //接收数据
            $model->load($request->post());
            $goodsIntro->load($request->post());
            //处理数据
            if ($model->validate()) {
                //保存
                $model->save();
                $goodsIntro->save();
                //设置提示信息
                \Yii::$app->session->setFlash('success', '修改成功');
                //跳转
                return $this->redirect(['goods/index']);
            }
        }
        //查询所有节点数据
        $nodes = GoodsCategory::find()->asArray()->all();
        $nodes[] = ['id' => 0, 'parent_id' => 0, 'name' => '顶级分类'];

        //调用视图,分配数据
        return $this->render('edit', ['model' => $model, 'goodsIntro' => $goodsIntro, 'nodes' => json_encode($nodes)]);

    }

    //删除
    public function actionDelete($id)
    {
        $model = Goods::findOne(['id' => $id]);
        if ($model->validate()){
            $model->status = 0;
            $model->save();
            return $this->redirect(['goods/index']);
        }

    }

    //webuploader图片上传
    public function actionLogoUpload()
    {
        //实例化上传组件
        $uploadedFile = UploadedFile::getInstanceByName('file');
        //保存上传文件路径
        $fileName = '/upload/' . uniqid() . '.' . $uploadedFile->extension;
        $res = $uploadedFile->saveAs(\Yii::getAlias('@webroot') . $fileName);
        if ($res) {
            echo json_encode([
                'url' => $fileName
            ]);
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


    //相册上传图片
    public function actionGallery($id)
    {
        //实例化
        $request = \Yii::$app->request;
        $model = new GoodsGallery();
        $goodsPhoto = GoodsGallery::find()->where(['goods_id' => $id])->all();
        if ($request->isPost) {
            //接收数据
            $model->load($request->post());
            //处理数据
            $model->goods_id = $id;
            if ($model->validate()) {

                //保存
                $model->save();
                //提示信息
                \Yii::$app->session->setFlash('success', '上传成功');
                //跳转
                return $this->redirect(['goods/gallery', 'id' => $id]);
            }
        }

        //调用页面,分配数据
        return $this->render('gallery', ['model' => $model, 'goodsPhoto' => $goodsPhoto]);
    }

    //相册删除
    public function actionRecyle($id, $goods_id)
    {
        $model = GoodsGallery::findOne(['id' => $id]);
        $model->delete();
        //跳转
        return $this->redirect(['goods/gallery', 'id' => $goods_id]);

    }


}
