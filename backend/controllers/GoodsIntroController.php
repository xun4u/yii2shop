<?php

namespace backend\controllers;

use backend\models\GoodsIntro;
use yii\filters\AccessControl;

class GoodsIntroController extends \yii\web\Controller
{
    //过滤器
    public function behaviors(){
        return [
            'acf'=>[
                'class'=>AccessControl::className(),
                'rules'=>[

                    [
                        'allow'=>true,
                        'roles'=>['@'],
                    ]
                ],
            ],


        ];


    }
    public function actionIndex($id)
    {
        $model = GoodsIntro::findOne(['goods_id'=>$id]);
        return $this->render('index',['model'=>$model]);
    }

}
