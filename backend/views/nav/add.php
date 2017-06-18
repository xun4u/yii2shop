<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'url')->dropDownList($model::getUrl());
echo $form->field($model,'parent_id')->dropDownList($model::getParentOptions());
echo $form->field($model,'sort');
echo \yii\bootstrap\Html::submitInput('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
