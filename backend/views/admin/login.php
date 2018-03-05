<?php
use \kucha\ueditor\UEditor;

$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model, 'username')->textInput();
echo $form->field($model, 'password_hash')->passwordInput();
//验证码
echo $form->field($model, 'code')->widget(\yii\captcha\Captcha::className(),[
    'captchaAction'=>'admin/captcha',
    'template'=>'<div class="row"><div class="col-xs-1">{input}</div><div class="col-xs-1">{image}</div></div> '
]);
//cookie自动登录
echo $form->field($model,'remember')->checkbox();
echo '<button class="btn btn-info">登录</button>';
\yii\bootstrap\ActiveForm::end();