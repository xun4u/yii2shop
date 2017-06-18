<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use xj\uploadify\UploadAction;//使用插件-composer安装的upload插件
use crazyfd\qiniu\Qiniu;//七牛云上传插件

class BrandController extends \yii\web\Controller
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
        $model = Brand::find()->where(['>=','status',0])->all();


        return $this->render('index',['models'=>$model]);
    }

    //新增
    public function actionAdd(){
        $model = new Brand();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());//表单模型加载post提交的数据
//            $model->imgFile = UploadedFile::getInstance($model,'imgFile');//表单模型加载文件

            if($model->validate()){
//                if($model->imgFile){ //如果存在文件就要保存文件
//                    $fileName = '/images/brand/'.uniqid().$model->imgFile->extension; //创建文件名
//                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);//保存文件
//                    $model->logo = $fileName;
//                }
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
//            $model->imgFile = UploadedFile::getInstance($model,'imgFile');//表单模型加载文件

            if($model->validate()){
                /*if($model->imgFile){ //如果存在文件就要保存文件
                    $fileName = '/images/brand/'.uniqid().$model->imgFile->extension; //创建文件名
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);//保存文件
                    $model->logo = $fileName;
                }*/
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
//                    $action->output['fileUrl'] = $action->getWebUrl();
                    //调用七牛云组件，将图片上传到七牛云
                    $qiniu = \Yii::$app->qiniu;

                    $qiniu->uploadFile(\Yii::getAlias('@webroot').$imgUrl,$imgUrl);

                    //获取图片在七牛云的地址
                    $url = $qiniu->getLink($imgUrl);
                    $action->output['fileUrl'] = $url;//将七牛云的地址输出到前端
                    /*$action->getFilename(); // "image/yyyymmddtimerand.jpg"
                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"*/
                },
            ],
        ];
    }


    //七牛云上传
    public function actionTest(){
        $ak = 'CDD4INkEdp2BT0PM4PB6U2dEEO1xVqrQX3RqdOah';
        $sk = 'Egbb9gPGRoL2kyV4Q6m8hZTVTL3P6DfSz9fDVKpQ';
        $domain = 'http://or9tmzkdz.bkt.clouddn.com';//个人七牛云域名
        $bucket = 'yii2shop';//仓库名
        $qiniu = new Qiniu($ak, $sk,$domain, $bucket);
        //要上传的文件
        $fileName = \Yii::getAlias('@webroot').'/upload/test.jpg';
        $key = 'test.jpg';//文件的别名（上传后保存在七牛云的名字）
        $re = $qiniu->uploadFile($fileName,$key); //上传
        $url = $qiniu->getLink($key); //获取外链地址

    }



}
