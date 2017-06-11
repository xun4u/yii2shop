<?php

namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\ArticleDetail;

class ArticleController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = Article::find()->all();

        return $this->render('index',['models'=>$model]);
    }

    //新增
    public function actionAdd(){
        $model = new Article();
        $cate = ArticleCategory::find()->all();//文章分类

        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->create_time = time();
            $model->save();
            $detail = new ArticleDetail();
            $detail->content = $model->content;
            $detail->save();

            \Yii::$app->session->setFlash('success','添加成功');
            return $this->redirect(['article/index']);
        }

        return $this->render('add',['model'=>$model,'cate'=>$cate]);
    }

    //修改
    public function actionEdit($id){
        $model = Article::findOne(['id'=>$id]);
        $model->content = ArticleDetail::findOne(['article_id'=>$id])->content;
        $cate = ArticleCategory::find()->all();//文章分类

        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->save();
            \Yii::$app->session->setFlash('success','修改成功');
            return $this->redirect(['article/index']);
        }

        return $this->render('add',['model'=>$model,'cate'=>$cate]);
    }

    //删除
    public function actionDel($id){
        $model =Article::findOne(['id'=>$id]);
        $model->status = -1;
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['article/index']);

    }

}
