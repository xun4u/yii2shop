<?php
/* @var $this yii\web\View */
?>
<h1>品牌信息表</h1>
<p><?=\yii\bootstrap\Html::a('添加品牌',['brand/add'],['class'=>'btn btn-primary'])?></p>

<table class="table table-bordered table-hover " >
    <tr>
        <th>ID</th>
        <th>品牌名称</th>
        <th>品牌Logo</th>
        <th>品牌简介</th>
        <th>排序</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
    <tr>
        <td><?=$model->id?></td>
        <td><?=$model->name?></td>
        <td><?=\yii\bootstrap\Html::img($model->logo,['width'=>50])?></td>
        <td><?=$model->intro?></td>
        <td><?=$model->sort?></td>
        <td><?=($model->status == 1)? '正常':'隐藏'?></td>
        <td>
            <?=\yii\bootstrap\Html::a('修改',['brand/edit','id'=>$model->id],['class'=>'btn btn-warning btn-xs'])?>
            <?=\yii\bootstrap\Html::a('删除',['brand/del','id'=>$model->id],['class'=>'btn btn-danger btn-xs'])?>
        </td>

    </tr>
    <?php endforeach;?>
</table>
