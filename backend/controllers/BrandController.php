<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\web\UploadedFile;

class BrandController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = Brand::find()->where(['>=','status',0])->all();


        return $this->render('index',['models'=>$model]);
    }

    //新增
    public function actionAdd(){
        $model = new Brand();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());//表单模型加载post提交的数据
            $model->imgFile = UploadedFile::getInstance($model,'imgFile');//表单模型加载文件

            if($model->validate()){
                if($model->imgFile){ //如果存在文件就要保存文件
                    $fileName = '/images/brand/'.uniqid().$model->imgFile->extension; //创建文件名
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);//保存文件
                    $model->logo = $fileName;
                }
                $model->save();
                \Yii::$app->session->setFlash('success','添加品牌成功');
                return $this->redirect(['brand/index']);
            }
        }

        return $this->render('add',['model'=>$model]);
    }

    //修改
    public function actionEdit($id){
        $model = Brand::findOne(['id'=>$id]);
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());//表单模型加载post提交的数据
            $model->imgFile = UploadedFile::getInstance($model,'imgFile');//表单模型加载文件

            if($model->validate()){
                if($model->imgFile){ //如果存在文件就要保存文件
                    $fileName = '/images/brand/'.uniqid().$model->imgFile->extension; //创建文件名
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);//保存文件
                    $model->logo = $fileName;
                }
                $model->save();
                \Yii::$app->session->setFlash('success','修改品牌成功');
                return $this->redirect(['brand/index']);
            }
        }

        return $this->render('add',['model'=>$model]);
    }

    //删除
    public function actionDel($id){
        $model = Brand::findOne(['id'=>$id]);

        $model->status = -1;
        $model->save();

        \Yii::$app->session->setFlash('success','删除品牌成功');
        return $this->redirect(['brand/index']);

    }

}
