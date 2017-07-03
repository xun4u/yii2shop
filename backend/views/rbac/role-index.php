<table class="table table-responsive table-bordered">

    <tr>
        <th>名称</th>
        <th>描述</th>
        <th>权限</th>
        <th>操作</th>
    </tr>


    <?php foreach ($models as $model):?>
        <tr>
            <td><?=$model->name?></td>
            <td><?=$model->description?></td>
            <td><?php
                foreach (Yii::$app->authManager->getPermissionsByRole($model->name) as $permission){
                    echo $permission->description;
                    echo '|';
                }
                ?></td>
            <td><?=\yii\bootstrap\Html::a('修改',['rbac/role-edit','name'=>$model->name],['class'=>'btn btn-warning btn-xs'])?>
                <?=\yii\bootstrap\Html::a('删除',['rbac/role-del','name'=>$model->name],['class'=>'btn btn-danger btn-xs'])?></td>
        </tr>
    <?php endforeach;?>

</table>


