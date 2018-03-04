<?php
/**
 * @var $this \yii\web\View
 */
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model, 'name')->textInput();
echo $form->field($model, 'logo')->hiddenInput();
//************** webuploader ********************
//引入css和js
$this->registerCssFile('@web/webUploader/webuploader.css');
$this->registerJsFile('@web/webUploader/webuploader.js', [
    'depends' => \yii\web\JqueryAsset::className()
]);
//输出按钮
echo <<<html
<!--dom结构部分-->
<div id="uploader-demo">
    <!--用来存放item-->
    <div id="fileList" class="uploader-list"></div>
    <div id="filePicker">选择图片</div>
</div>
html;
////开始上传文件
$logo_upload_url = \yii\helpers\Url::to(['goods/logo-upload']);
$this->registerJs(
    <<<js
// 初始化Web Uploader
var uploader = WebUploader.create({

    // 选完文件后，是否自动上传。
    auto: true,

    // swf文件路径
    swf:  '/webUploader/Uploader.swf',

    // 文件接收服务端。
    server: "{$logo_upload_url}",

    // 选择文件的按钮。可选。
    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
    pick: '#filePicker',

    // 只允许选择图片文件。
    accept: {
        title: 'Images',
        extensions: 'gif,jpg,jpeg,bmp,png',
        mimeTypes: 'image/*'
    }
});
    //图片上传成功
 uploader.on( 'uploadSuccess', function( file,response ) {
     var imgUrl = response.url;
    // console.log(imgUrl);
     //将上传成功的文件的路径赋值给logo字段
     $("#goods-logo").val(imgUrl);
     //图片回显
     $("#logo_view").attr('src',imgUrl);
     //$( '#'+file.id ).addClass('upload-state-done');
 });
js
);
echo '<img id="logo_view" width="150px" />';


//************** webuploader ********************

echo $form->field($model, 'goods_category_id')->dropDownList(\backend\models\Goods::getGoodsCategoryOptions());
//********************* ztree ********************************
//引入css,js
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js', [
    'depends' => \yii\web\JqueryAsset::class
]);
//html代码
echo <<<HTML
<div>
    <ul id="treeDemo" class="ztree"></ul>
</div>
HTML;

//js代码
$this->registerJs(
    <<<JS
        // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
        var setting = {
        data: {
		simpleData: {
			enable: true,
			idKey: "id",
			pIdKey: "parent_id",
			rootPId: 0
		     }
	    },
	    callback: {
		onClick: function(event, treeId, treeNode) {
		  //console.log(treeNode);exit;
		  //把节点值写入id
		  $("#goods-goods_category_id").val(treeNode.id);
		}
	}
 };
       // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
        var zNodes = {$nodes};
       // zNodes = JSON.parse(zNodes);
        var zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
         //展开全部节点
        zTreeObj.expandAll(true);
         //回显选中
        zTreeObj.selectNode(zTreeObj.getNodeByParam("id", "{$model->goods_category_id}", null));
JS
);



//********************* ztree ********************************
echo $form->field($model, 'brand_id')->dropDownList(\backend\models\Goods::getBrandOptions());
echo $form->field($model, 'market_price')->textInput();
echo $form->field($model, 'shop_price')->textInput();
echo $form->field($model, 'stock')->textInput();
echo $form->field($model, 'is_on_sale', ['inline' => 1])->radioList([1 => '在售', 0 => '下架']);
echo $form->field($model, 'sort')->textInput();
echo $form->field($goodsIntro, 'content')->widget('kucha\ueditor\UEditor', []);
echo '<button class="btn btn-info">提交</button>';
\yii\bootstrap\ActiveForm::end();
