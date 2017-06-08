<?php
/* @var $this yii\web\View */
?>
<h1>文章分类列表</h1>
<p><?=\yii\bootstrap\Html::a('新增文章分类',['article-category/add'],['class'=>'btn btn-primary'])?></p>
<table class="table table-bordered table-hover">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>简介</th>
        <th>排序</th>
        <th>状态</th>
        <th>类型</th>
        <th>操作</th>
    </tr>

    <?php foreach ($models as $model):?>
    <tr>
        <td><?=$model->id?></td>
        <td><?=$model->name?></td>
        <td><?=$model->intro?></td>
        <td><?=$model->sort?></td>
        <td><?= ($model->status == 1) ? '正常':'隐藏'?></td>
        <td><?=$model->is_help?></td>
        <td>
            <?=\yii\bootstrap\Html::a('修改',['article-category/edit','id'=>$model->id],['class'=>'btn btn-warning btn-xs'])?>
            <?=\yii\bootstrap\Html::a('删除',['article-category/del','id'=>$model->id],['class'=>'btn btn-danger btn-xs'])?>
        </td>
    </tr>
    <?php endforeach;?>
</table>
