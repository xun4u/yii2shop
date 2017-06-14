<?php
/* @var $this yii\web\View */
?>
<h1>商品图片</h1>

<table>
    <tr>
        <th>id</th>
        <th>图片</th>
        <th>排序</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model):?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->url?></td>
            <td><?=$model->sort?></td>
            <td>
                修改排序
                删除
            </td>
        </tr>
    <?php endforeach;?>
</table>
