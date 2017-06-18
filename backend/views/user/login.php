<h1>登录&注册</h1>
<?php
$form =\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username');
echo $form->field($model,'password')->passwordInput();
echo $form->field($model,'code')->widget(\yii\captcha\Captcha::className());
echo $form->field($model,'rememberMe')->checkbox();
echo \yii\bootstrap\Html::submitInput('登录',['class'=>'btn btn-info'])/*.' '.\yii\bootstrap\Html::a('注册',['user/add'],['class'=>'btn btn-default']);*/;
\yii\bootstrap\ActiveForm::end();
