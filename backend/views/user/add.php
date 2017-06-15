<h1>用户注册</h1>
<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username');
echo $form->field($model,'password_hash')->passwordInput();
echo $form->field($model,'email')->textInput(['placeholder'=>'邮箱地址 例如：123@qq.com']);

echo $form->field($model,'code')->widget(\yii\captcha\Captcha::className());
echo \yii\bootstrap\Html::submitInput('提交',['class'=>'btn btn-info']);

\yii\bootstrap\ActiveForm::end();
