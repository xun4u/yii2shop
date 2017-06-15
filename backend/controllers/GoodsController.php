<?php

namespace backend\controllers;

use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsGallery;
use backend\models\GoodsImages;
use backend\models\GoodsIntro;
use backend\models\GoodsSearchForm;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use xj\uploadify\UploadAction;
use yii\web\NotFoundHttpException;//使用插件-composer安装的upload插件

class GoodsController extends \yii\web\Controller
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
    public function actionIndex()
    {
        //搜索

        $query = Goods::find();
        $model = new GoodsSearchForm();//搜索表单模型

        $query = $model->search($query);

        //分页
        $pages = new Pagination(
            [
                'pageSize'=>'3',
                'totalCount'=>$query->count(),
            ]
        );

        $models = $query->orderBy('id ASC')
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('index',['models'=>$models,'pages'=>$pages,'model'=>$model]);
    }

    //新增商品
    public function actionAdd(){
        $goods = new Goods();
        $goods_intro = new GoodsIntro();

        if($goods->load(\Yii::$app->request->post())
            && $goods_intro->load(\Yii::$app->request->post())
            && $goods->validate()
            && $goods_intro->validate()){
            $goods->create_time = time();
            //sn的生成
            //先在记录表中查询当天的记录
            $count = GoodsDayCount::findOne(['day'=>date('Y-m-d')]);
            if($count != null){ //存在说明已经有商品添加的记录
                $sn_count = $count->count++; //自增
                //补零 后面还有5位
                $sn_count = str_pad($sn_count,5,"0",STR_PAD_LEFT);
                $goods->sn = date('Ymd').$sn_count; //拼好sn字符串赋值给goods的sn字段

            }else{  //不存在记录，就要创建当天的记录 并设置count为1
                $count = new GoodsDayCount();
                $count->day = date('Ymd');
                $count->count = 1;
                $sn_count = str_pad(1,5,"0",STR_PAD_LEFT);
                $goods->sn = $count->day.$sn_count;
            }

            $goods->save();
            $count->save();
            $goods_intro->goods_id = $goods->id;
            $goods_intro->save();

            \Yii::$app->session->setFlash('success','商品添加成功');
            return $this->redirect(['goods/index']);
        }
        $brands = ArrayHelper::map(Brand::find()->asArray()->all(),'id','name');
        $categories = ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0,'open'=>1]],GoodsCategory::find()->asArray()->all());

        return $this->render('add',['goods'=>$goods,'goods_intro'=>$goods_intro,'brands'=>$brands,'categories'=>$categories]);
    }

    //修改
    public function actionEdit($id){
        $goods = Goods::findOne(['id'=>$id]);
        $goods_intro = GoodsIntro::findOne(['goods_id'=>$id]);

        if($goods->load(\Yii::$app->request->post())
            && $goods_intro->load(\Yii::$app->request->post())
            && $goods->validate()
            && $goods_intro->validate()){

            $goods->save();
            $goods_intro->save();

            \Yii::$app->session->setFlash('success','商品修改成功');
            return $this->redirect(['goods/index']);
        }
        $brands = ArrayHelper::map(Brand::find()->asArray()->all(),'id','name');
        $categories = ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0,'open'=>1]],GoodsCategory::find()->asArray()->all());

        return $this->render('add',['goods'=>$goods,'goods_intro'=>$goods_intro,'brands'=>$brands,'categories'=>$categories]);
    }


    //商品相册
    public function actionGallery($id){
        $goods =GoodsGallery::findOne($id);
        if($goods==null){
            throw new NotFoundHttpException('商品不存在');
        }
        return $this->render('gallery',['goods'=>$goods]);
    }

    /*
     * AJAX删除图片
     */
    public function actionDelGallery(){
        $id = \Yii::$app->request->post('id');
        $model = GoodsGallery::findOne(['id'=>$id]);
        if($model && $model->delete()){
            return 'success';
        }else{
            return 'fail';
        }

    }

    //文件上传插件uploadify
    public function actions() {
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                /*'overwriteIfExist' => true,
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filename = sha1_file($action->uploadfile->tempName);
                    return "{$filename}.{$fileext}";
                },*/
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    $imgUrl = $action->getWebUrl();//获取上传后的图片在本地的相对路径
                    $action->output['fileUrl'] = $action->getWebUrl();

                    $action->output['fileUrl'] = $imgUrl;
                    /*$action->getFilename(); // "image/yyyymmddtimerand.jpg"
                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"*/
                },
            ],
        ];
    }

}
