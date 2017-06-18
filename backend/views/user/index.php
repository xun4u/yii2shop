<?php
/* @var $this yii\web\View */
?>
<h1>用户信息表</h1>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>用户名</th>
        <th>邮箱</th>
        <th>状态</th>
        <th>角色</th>
        <th>注册时间</th>
        <th>最近修改时间</th>
        <th>最后登录时间</th>
        <th>最后登录IP</th>
        <th>操作</th>

    </tr>

    <?php foreach ($models as $model):?>

        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->username?></td>
            <td><?=$model->email?></td>
            <td><?=$model->status?></td>
            <td>
                <?php
                foreach (Yii::$app->authManager->getRolesByUser($model->id) as $role){
                    echo $role->description;
                    echo '|';
                }
                ?>
            </td>
            <td><?=date('Y-m-d H:i',$model->created_at)?></td>
            <td><?=date('Y-m-d H:i',$model->updated_at)?></td>
            <td><?=date('Y-m-d H:i',$model->login_time)?></td>

            <td><?=$model->login_ip?></td>
            <td>
                <?=\yii\bootstrap\Html::a('基本信息/角色修改',['user/edit','id'=>$model->id],['class'=>'btn btn-info btn-xs'])?>
            </td>

        </tr>
    <?php endforeach;?>
</table>
