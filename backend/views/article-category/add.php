<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'intro')->textarea();
echo $form->field($model,'sort');
echo $form->field($model,'status',['inline'=>true])->radioList([1=>'正常',2=>'隐藏']);
echo $form->field($model,'is_help');
echo \yii\bootstrap\Html::submitInput('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
