<?php
/**
 * @var $model \frontend\models\Order;
 */
?>
<h1>订单列表显示</h1>
<table class="table table-hover table-bordered table-responsive table-striped">
 <tr>
     <th>订单号</th>
     <th>用户</th>
     <th>收货人</th>
     <th>地址</th>
     <th>电话</th>
     <th>邮寄方式</th>
     <th>付款方式</th>
     <th>订单总价</th>
     <th>状态</th>
     <th>时间</th>
     <th>操作</th>
 </tr>
    <?php foreach ($models as $model):?>
    <tr>
        <td><?=$model->id?></td>
        <td><?=$model->member->username?></td>
        <td><?=$model->name?></td>
        <td><?=$model->province.' '.$model->city.' '.$model->area.' '.$model->address?></td>
        <td><?=$model->tel?></td>
        <td><?=$model->delivery_name?></td>
        <td><?=$model->payment_name?></td>
        <td><?=$model->total?></td>
        <td><?=\frontend\models\OrderGoods::$orderstatus[$model->status] ?></td>
        <td><?=date('Y-m-d H:i',$model->create_time)?></td>
        <td>
            <?=\yii\bootstrap\Html::a('发货',['order/deliver','id'=>$model->id],['class'=>'btn btn-success btn-xs'])?>
            <?=\yii\bootstrap\Html::a('取消订单',['order/del','id'=>$model->id],['class'=>'btn btn-danger btn-xs'])?>
        </td>
    </tr>
    <?php endforeach;?>
</table>
