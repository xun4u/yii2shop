<?php
/* @var $this yii\web\View */
?>
<h1>菜单管理</h1>
<p><?=\yii\bootstrap\Html::a('添加菜单',['nav/add'],['class'=>'btn btn-primary'])?></p>
<table class="table table-bordered">
    <tr>
        <th>菜单名</th>
        <th>跳转地址</th>
        <th>父级菜单</th>
        <th>排序</th>
        <th>操作</th>
    </tr>

    <?php foreach ($models as $model):?>
    <tr>
        <td><?=$model->name?></td>
        <td><?=$model->url?></td>
        <td><?=$model->parent_id ==0 ? '顶级菜单': $model->parent_id?></td>
        <td><?=$model->sort?></td>
        <td>
            <?=\yii\bootstrap\Html::a('修改',['nav/edit','id'=>$model->id],['class'=>'btn btn-warning btn-xs'])?>
            <?=\yii\bootstrap\Html::a('删除',['nav/del','id'=>$model->id],['class'=>'btn btn-danger btn-xs'])?>
        </td>
    </tr>
    <?php endforeach;?>
</table>

<?=\yii\widgets\LinkPager::widget(['pagination'=>$pages]);