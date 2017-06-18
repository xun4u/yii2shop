<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username')->textInput(['readonly'=>true]);
echo $form->field($model,'email');
echo $form->field($model,'status');
echo $form->field($model,'roles',['inline'=>true])->checkboxList(\backend\models\User::getRoleOptions());
echo \yii\bootstrap\Html::submitInput('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
