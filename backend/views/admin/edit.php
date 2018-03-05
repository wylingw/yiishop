<?php
use \kucha\ueditor\UEditor;

$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model, 'username')->textInput();
echo $form->field($model, 'email')->textInput();
echo '<button class="btn btn-info">提交</button>';
\yii\bootstrap\ActiveForm::end();