<?php

namespace backend\controllers;

use backend\models\Nav;
use yii\data\Pagination;

class NavController extends backendController
{
    //显示菜单列表
    public function actionIndex()
    {
        $query = Nav::find();

        $pages = new Pagination(
            [
                'pageSize'=>5,
                'totalCount'=>$query->count(),
            ]
        );

        $models = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();


        return $this->render('index',['models'=>$models,'pages'=>$pages]);
    }

    //添加菜单
    public function actionAdd(){

        $model = new Nav();

        if($model->load(\Yii::$app->request->post()) && $model->validate()){

            $model->save();

            return $this->redirect(['nav/index']);
        }

        return $this->render('add',['model'=>$model]);
    }

    //修改菜单
    public function actionEdit(){


    }

    //删除菜单
    public function actionDel(){

    }

}
