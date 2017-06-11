<?php
/* @var $this yii\web\View */
?>
<h1>文章列表</h1>
<p>
    <?=\yii\bootstrap\Html::a('添加文章',['article/add'],['class'=>'btn btn-primary'])?>
</p>
<table class="table table-bordered table-hover">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>简介</th>
        <th>文章分类</th>
        <th>排序</th>
        <th>状态</th>
        <th>创建时间</th>
        <th>操作</th>
    </tr>

    <?php foreach ($models as $model):?>
    <tr>
        <td><?=$model->id?></td>
        <td><?=$model->name?></td>
        <td><?=$model->intro?></td>
        <td><?=$model->articleCategory->name?></td>
        <td><?=$model->sort?></td>
        <td><?= ($model->status == 1) ? '正常':'隐藏'?></td>
        <td><?=date('Y-m-d H:i',$model->create_time)?></td>
        <td>
            <?=\yii\bootstrap\Html::a('修改',['article/edit','id'=>$model->id],['class'=>'btn btn-warning btn-xs'])?>
            <?=\yii\bootstrap\Html::a('删除',['article/del','id'=>$model->id],['class'=>'btn btn-danger btn-xs'])?>
        </td>
    </tr>

    <?php endforeach;?>
</table>
