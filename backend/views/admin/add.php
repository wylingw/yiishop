<?php
use \kucha\ueditor\UEditor;

$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model, 'username')->textInput();
echo $form->field($model, 'password')->passwordInput();
echo $form->field($model, 'email')->textInput();
echo $form->field($model, 'roles',['inline'=>1])->checkboxList(\backend\models\Admin::getRoles());
echo '<button class="btn btn-info">提交</button>';
\yii\bootstrap\ActiveForm::end();