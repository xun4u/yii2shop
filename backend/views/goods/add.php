<?php
use yii\web\JsExpression;
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($goods,'name');

echo $form->field($goods,'logo')->textInput(['style'=>'width:30%','readonly'=>true]);
//上传框开始--------------------------------
echo \yii\bootstrap\Html::fileInput('test',null,['id'=>'test']);
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
        $("#goods-logo").val(data.fileUrl);
    }
}
EOF
        ),
    ]
]);
if($goods->logo){ //表单模型返回的数据（数据库中的数据）
    echo \yii\bootstrap\Html::img('@web'.$goods->logo,['id'=>'img_logo','height'=>'50']);//有图片直接显示
}else{
    echo \yii\bootstrap\Html::img('',['style'=>'display:none','id'=>'img_logo','height'=>'50']);//让图片框隐藏
}

//上传框结束-------------------------------------


echo $form->field($goods,'goods_category_id')->textInput(['style'=>'width:20%','readonly'=>true]);//商品分类 要用到ztree
echo '<ul id="treeDemo" class="ztree"></ul>';
echo $form->field($goods,'brand_id')->dropDownList($brands,['prompt'=>'请选择商品品牌']);//品牌分类 下拉菜单

echo $form->field($goods,'market_price');
echo $form->field($goods,'shop_price');

echo $form->field($goods,'stock');
//echo $form->field($goods_intro,'content')->textarea();
echo $form->field($goods_intro,'content')->widget('kucha\ueditor\UEditor',[]);
echo $form->field($goods,'is_on_sale',['inline'=>true])->radioList($goods::$sale_options);
echo $form->field($goods,'status',['inline'=>true])->radioList($goods::$status_options);
echo $form->field($goods,'sort');
echo \yii\bootstrap\Html::submitInput('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();

//注册静态资源
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);

$zNodes = \yii\helpers\Json::encode($categories);
$js = new \yii\web\JsExpression(
    <<<JS
         var zTreeObj;
            // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
        var setting = {
            data: {
                simpleData: {
                    enable: true,
                    idKey: "id",
                    pIdKey: "parent_id",
                    rootPId: 0
                    }
            },
            callback: {
		    onClick: function(event, treeId, treeNode) {
                console.log(treeNode.id);
                //将选中节点的id赋值给表单parent_id
                $("#goods-goods_category_id").val(treeNode.id);
                }
	        }
        };
        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
        var zNodes = {$zNodes};
        
        zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
        zTreeObj.expandAll(true);//展开所有节点
        //获取当前节点的父节点（根据id查找）
        var node = zTreeObj.getNodeByParam("id", $("#goods-goods_category_id").val(), null);
        zTreeObj.selectNode(node);//修改的时候，选中当前节点的父节点
JS
);

$this->registerJs($js);
