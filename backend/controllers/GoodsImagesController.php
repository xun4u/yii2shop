<?php

namespace backend\controllers;

use backend\models\GoodsImages;

class GoodsImagesController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $models = GoodsImages::find()->all();

        return $this->render('index',['models'=>$models]);
    }

}
