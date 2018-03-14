<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model, 'name')->textInput();
echo $form->field($model, 'intro')->textarea();
echo $form->field($model, 'logo')->hiddenInput();
/**
 * @var $this \yii\web\View
 */
//引入css和js
$this->registerCssFile('@web/webUploader/webuploader.css');
$this->registerJsFile('@web/webUploader/webuploader.js',[
    'depends'=>\yii\web\JqueryAsset::className()
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
//开始上传文件
$logo_upload_url = \yii\helpers\Url::to(['brand/logo-upload']);
$this->registerJs(
  <<<js
// 初始化Web Uploader
var uploader = WebUploader.create({

    // 选完文件后，是否自动上传。
    auto: true,

    // swf文件路径
    swf:  '/webUploader/Uploader.swf',

    // 文件接收服务端。
    server: '{$logo_upload_url}',

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
    $("#brand-logo").val(imgUrl);
    //图片回显
    $("#logo_view").attr('src',imgUrl);
    //$( '#'+file.id ).addClass('upload-state-done');
});
js
);
if ($model->logo) {
    echo '<img id="logo_view" src="'.$model->logo.'" width="150px" />';
} else {
    echo '<img id="logo_view" />';
}


echo $form->field($model, 'sort')->textInput();
echo '<button class="btn btn-info">提交</button>';
\yii\bootstrap\ActiveForm::end();