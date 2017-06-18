<?php
/**
 * Created by PhpStorm.
 * User: lixun
 * Date: 2017/6/18
 * Time: 23:40
 */

namespace backend\controllers;


use backend\components\RbacFilter;
use yii\web\Controller;

class backendController extends Controller
{
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
            ]
        ];
    }


}