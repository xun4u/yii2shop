<?php
/* @var $this yii\web\View */


?>
<h1>商品列表显示</h1>
<p><?=\yii\bootstrap\Html::a('新增商品',['goods/add'],['class'=>'btn btn-primary'])?></p>

<!--搜索表单开始-->
<?php $form = \yii\bootstrap\ActiveForm::begin([
        'method'=>'get',
        'action'=>\yii\helpers\Url::to(['goods/index']),
        'options' => ['class' => 'form-inline']
]);
echo $form->field($model,'name')->textInput(['placeholder'=>'商品名'])->label(false);
echo $form->field($model,'sn')->textInput(['placeholder'=>'货号'])->label(false);
echo $form->field($model,'minPrice')->textInput(['placeholder'=>'最低价格'])->label(false);
echo $form->field($model,'maxPrice')->textInput(['placeholder'=>'最高价格'])->label('--');
echo \yii\bootstrap\Html::submitInput('搜索',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end()
?>
<!--搜索表单结束-->

<table class="table table-bordered table-responsive table-hover table-striped"">
    <tr class="info">
        <th>id</th>
        <th>商品名</th>
        <th>货号</th>
        <th>logo图片</th>
        <th>商品所属分类</th>
        <th>品牌</th>
        <th>市场价格</th>
        <th>商品价格</th>
        <th>库存</th>
        <th>在售状态</th>
        <th>商品状态</th>
        <th>排序</th>
        <th>添加时间</th>
        <th>操作</th>
    </tr>

    <?php foreach ($models as $model):?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->name?></td>
            <td><?=$model->sn?></td>
            <td>
                <?=\yii\bootstrap\Html::img($model->logo,['style'=>'max-height:50px'])?>
            </td>
            <td><?=$model->goodsCategory->name?></td>
            <td><?=$model->brand->name?></td>
            <td><?=$model->market_price?></td>
            <td><?=$model->shop_price?></td>
            <td><?=$model->stock?></td>
            <td><?=\backend\models\Goods::$sale_options[$model->is_on_sale]?></td>
            <td><?=\backend\models\Goods::$status_options[$model->status]?></td>
            <td><?=$model->sort?></td>
            <td><?=date('Ymd H:i',$model->create_time)?></td>
            <td>
                <?=\yii\bootstrap\Html::a('图片',['goods/image','id'=>$model->id],['class'=>'btn btn-success btn-xs'])?>
                <?=\yii\bootstrap\Html::a('详细',['goods-intro/index','id'=>$model->id],['class'=>'btn btn-info btn-xs'])?>
                <?=\yii\bootstrap\Html::a('修改',['goods/edit','id'=>$model->id],['class'=>'btn btn-warning btn-xs'])?>
                <?=\yii\bootstrap\Html::a('删除',['goods/del','id'=>$model->id],['class'=>'btn btn-danger btn-xs'])?>
            </td>

        </tr>
    <?php endforeach;?>
</table>

<?=\yii\widgets\LinkPager::widget(['pagination'=>$pages])?>