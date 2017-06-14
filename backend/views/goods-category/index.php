<?php
/* @var $this yii\web\View */
?>
<h1>商品分类显示</h1>

<table class="table table-bordered table-responsive table-hover" >
    <tr>
        <th>ID</th>
        <th style="width: 60%">名称</th>
        <th>操作</th>
    </tr>

    <?php foreach ($models as $model):?>

        <tr>
            <td><?=$model->id?></td>
            <td><?=str_repeat('——',$model->depth).$model->name?></td>
            <td>
                <?=\yii\bootstrap\Html::a('修改',['goods-category/edit','id'=>$model->id],['class'=>'btn btn-warning btn-xs'])?>
                <?=\yii\bootstrap\Html::a('删除',['goods-category/del','id'=>$model->id],['class'=>'btn btn-danger btn-xs'])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
