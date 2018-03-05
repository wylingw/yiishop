<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($admin, 'old_password')->passwordInput();
echo $form->field($admin, 'new_password')->passwordInput();
echo $form->field($admin, 're_password')->passwordInput();
echo '<button class="btn btn-info">提交</button>';
\yii\bootstrap\ActiveForm::end();