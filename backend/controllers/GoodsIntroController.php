<?php

namespace backend\controllers;

use backend\models\GoodsIntro;
use yii\filters\AccessControl;

class GoodsIntroController extends backendController
{

    public function actionIndex($id)
    {
        $model = GoodsIntro::findOne(['goods_id'=>$id]);
        return $this->render('index',['model'=>$model]);
    }

}
