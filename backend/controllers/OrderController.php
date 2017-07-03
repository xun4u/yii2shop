<?php

namespace backend\controllers;

use frontend\models\Order;

class OrderController extends \yii\web\Controller
{
    //显示订单列表
    public function actionIndex()
    {
        $models = Order::find()->all();
//        var_dump($models);exit;
        return $this->render('index',['models'=>$models]);
    }

    //后台发货
    public function actionDeliver($id){
        $model = Order::findOne(['id'=>$id]);
        $model->status = 3;
        $model->save();
        return $this->redirect(['order/index']);
    }

    //后台取消订单
    public function  actiondel($id){
        $model = Order::findOne(['id'=>$id]);
        $model->status = 0;
        $model->save();
        return $this->redirect(['order/index']);
    }
}
