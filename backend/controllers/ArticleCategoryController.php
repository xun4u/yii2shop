<?php

namespace backend\controllers;

use backend\models\ArticleCategory;
use yii\filters\AccessControl;

class ArticleCategoryController extends backendController
{


    public function actionIndex()
    {
        $model = ArticleCategory::find()->where(['>=','status',0])->all();

        return $this->render('index',['models'=>$model]);
    }

    //新增
    public function actionAdd(){
        $model = new ArticleCategory();

        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->save();
            \Yii::$app->session->setFlash('success','添加文章分类成功');
            return $this->redirect(['article-category/index']);

        }
        return $this->render('add',['model'=>$model]);

    }

    //修改
    public function actionEdit($id){
        $model = ArticleCategory::findOne(['id'=>$id]);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->save();
            \Yii::$app->session->setFlash('success','修改文章分类成功');
            return $this->redirect(['article-category/index']);

        }
        return $this->render('add',['model'=>$model]);
    }

    //删除
    public function actionDel($id){
        $model =ArticleCategory::findOne(['id'=>$id]);
        $model ->status = -1;
        $model->save();
        \Yii::$app->session->setFlash('success','删除分类成功');
        return $this->redirect(['article-category/index']);

    }

}
