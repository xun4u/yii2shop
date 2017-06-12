<?php
use yii\web\JsExpression;


$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'intro')->textarea();
echo $form->field($model,'logo')->hiddenInput();//隐藏域保存图片上传的地址

echo \yii\bootstrap\Html::fileInput('test',null,['id'=>'test']);//上传框
echo \xj\uploadify\Uploadify::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'width' => 120,
        'height' => 40,
        'onUploadError' => new JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadSuccess' => new JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
            console.log(data.fileUrl);
        //上传成功后回显，通过jq将图片地址写入img,不管之前数据库有无图片地址
        $("#img_logo").attr("src",data.fileUrl).show();
        //将上传地址写入logo字段
        $("#brand-logo").val(data.fileUrl);
    }
}
EOF
        ),
    ]
]);
if($model->logo){ //表单模型返回的数据（数据库中的数据）
    echo \yii\bootstrap\Html::img('@web'.$model->logo,['id'=>'img_logo','height'=>'50']);//有图片直接显示
}else{
    echo \yii\bootstrap\Html::img('',['style'=>'display:none','id'=>'img_logo','height'=>'50']);//让图片框隐藏
}



echo $form->field($model,'sort');
echo $form->field($model,'status',['inline'=>true])->radioList([1=>'正常',0=>'隐藏']);
echo \yii\bootstrap\Html::submitInput('提交',['class'=>'btn btn-info']);

\yii\bootstrap\ActiveForm::end();
