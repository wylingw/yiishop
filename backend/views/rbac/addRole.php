<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model, 'name')->textInput();
echo $form->field($model, 'description')->textInput();
echo $form->field($model, 'permissions',['inline'=>true])->checkboxList(\backend\models\Role::getPermission());
echo '<button class="btn btn-info">提交</button>';
\yii\bootstrap\ActiveForm::end();