<?php

namespace backend\controllers;

use backend\models\GoodsCategory;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;


class GoodsCategoryController extends backendController
{


    public function actionIndex()
    {
        $models = GoodsCategory::find()->orderBy('tree,lft')->all();

        return $this->render('index',['models'=>$models]);
    }

    //添加商品分类
    public function actionAdd(){
        $model = new GoodsCategory();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //添加的时候判断是否是根分类，因为根分类和子分类的添加方式不同
            if($model->parent_id){ //parent_id=0就是根分类 !=0的时候 if为真 就是子分类
                //添加子分类
                $parent = GoodsCategory::findOne(['id'=>$model->parent_id]);//找到父分类
                $model->prependTo($parent);
            }else{
                //添加根分类
                $model->makeRoot();
            }
            \Yii::$app->session->setFlash('success','添加分类成功');
        }
        //分类查询出来是个2维数组，需要添加一个0分类的二维数组和他合并

        $categories = ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0,'open'=>1]],GoodsCategory::find()->asArray()->all());
        return $this->render('add',['model'=>$model,'categories'=>$categories]);
    }


    //修改
    public function actionEdit($id){
        $model = GoodsCategory::findOne(['id'=>$id]);
        if($model == null){
            throw new NotFoundHttpException('分类不存在');
        }
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //添加的时候判断是否是根分类，因为根分类和子分类的添加方式不同
            if($model->parent_id){ //parent_id=0就是根分类 !=0的时候 if为真 就是子分类
                //添加子分类
                $parent = GoodsCategory::findOne(['id'=>$model->parent_id]);//找到父分类
                $model->prependTo($parent);
            }else{
                //添加根分类
                $model->makeRoot();
            }
            \Yii::$app->session->setFlash('success','添加分类成功');
        }
        //分类查询出来是个2维数组，需要添加一个0分类的二维数组和他合并

        $categories = ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0,'open'=>1]],GoodsCategory::find()->asArray()->all());
        return $this->render('add',['model'=>$model,'categories'=>$categories]);
    }









    //测试下插件的功能
    public function actionTest(){
        //创建根分类
//        $jydq = new GoodsCategory(['name'=>'家用电器','parent_id'=>0]);
//        $jydq->makeRoot();
        //创建子分类
//        $parent = GoodsCategory::findOne(['id'=>1]);
//        $djd = new GoodsCategory(['name'=>'小家电','parent_id'=>$parent->id]);
//        $djd->prependTo($parent);
        //获取所有根分类
//        $roots = GoodsCategory::find()->roots()->all();
//        var_dump($roots);exit;
        //获取该分类下所有子分类
//        $parent =GoodsCategory::findOne(['id'=>1]);
//        $children = $parent->leaves()->all();
//        var_dump($children);exit;

    }
    //测试ztree的前端显示
    public function actionZtree(){
        $models = GoodsCategory::find()->asArray()->all();

        return $this->renderPartial('ztree',['models'=>$models]);//不加载布局文件
    }



}
