

<p><?=\yii\bootstrap\Html::a('添加权限',['rbac/permission-add'],['class'=>'btn btn-primary'])?></p>
<table class="table table-bordered">
    <thead>
    <tr>
        <th>权限名</th>
        <th>描述</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($models as $model):?>

     <tr>
         <td><?=$model->name?></td>
         <td><?=$model->description?></td>
         <td><?=\yii\bootstrap\Html::a('修改',['rbac/permission-edit','name'=>$model->name],['class'=>'btn btn-warning btn-xs'])?>
             <?=\yii\bootstrap\Html::a('删除',['rbac/permission-del','name'=>$model->name],['class'=>'btn btn-danger btn-xs'])?></td>
     </tr>

    <?php endforeach;?>
    </tbody>
</table>

<?php
/**
 * @var $this \yii\web\View
 */
$this->registerJsFile('//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js',['depends'=>\yii\web\JqueryAsset::className()]);

$this->registerCssFile('//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css');

$this->registerJs('$(".table").DataTable({});');

