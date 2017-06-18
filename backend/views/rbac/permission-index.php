<p><?=\yii\bootstrap\Html::a('添加权限',['rbac/permission-add'],['class'=>'btn btn-primary'])?></p>
<table class="table table-bordered">
    <tr>
        <th>权限名</th>
        <th>描述</th>
        <th>操作</th>
    </tr>

    <?php foreach ($models as $model):?>

     <tr>
         <td><?=$model->name?></td>
         <td><?=$model->description?></td>
         <td><?=\yii\bootstrap\Html::a('修改',['rbac/permission-edit','name'=>$model->name],['class'=>'btn btn-warning btn-xs'])?>
             <?=\yii\bootstrap\Html::a('删除',['rbac/permission-del','name'=>$model->name],['class'=>'btn btn-danger btn-xs'])?></td>
     </tr>

    <?php endforeach;?>
</table>
