<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model, 'name')->textInput();
echo $form->field($model, 'description')->textInput();
echo '<button class="btn btn-info">提交</button>';
\yii\bootstrap\ActiveForm::end();