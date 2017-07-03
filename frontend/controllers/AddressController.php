<?php
namespace frontend\controllers;

use chenkby\region\RegionAction;
use frontend\models\Locations;
use frontend\models\ShippingAddress;
use yii\web\Controller;

class AddressController extends Controller{

    //指定布局文件
    public $layout = 'member';


    public function actions()
    {
        $actions=parent::actions();
        $actions['get-region']=[
                'class'=>RegionAction::className(),
                'model'=>Locations::className(),
        ];
        return $actions;
    }


    //展示地址列表和新增地址表单
    public function actionIndex(){

        $model = new ShippingAddress();

        $member_id = \Yii::$app->user->identity->getId();//获取当前登录用户的id

        $infos = ShippingAddress::find()->where(['member_id'=>$member_id])->all(); //查询出该id下的所有地址信息

//        var_dump($infos);exit;
        if($model->load(\Yii::$app->request->post()) && $model->validate()){

            $model->member_id = $member_id;
            //如果勾选了默认地址，就要先将该id的所有默认地址设置为0，这个数据不影响表单
            if($model->select ==1){
                foreach ($infos as $info){
                    $info->select =0;
                    $info->save();
                }
            }

            $model->save();
            return $this->redirect(['address/index']);

        }

        return $this->render('address',['model'=>$model,'infos'=>$infos]);
    }


    //删除
    public function actionDel($id){
        $model = ShippingAddress::findOne(['id'=>$id]);
        $model->delete();

        return $this->redirect(['address/index']);

    }

    //设置默认地址
    public function actionSelect($id){
        $member_id = \Yii::$app->user->identity->getId();
        //先将全部地址默认取消
        $infos = ShippingAddress::find()->where(['member_id'=>$member_id])->all();
        foreach ($infos as $info){
            $info->select =0;
            $info->save();
        }
        //设置默认地址
        $model = ShippingAddress::findOne(['id'=>$id]);
        $model->select=1;
        $model->save();

        return $this->redirect(['address/index']);

    }

    //修改
    public function actionEdit($id){
        $model = ShippingAddress::findOne(['id'=>$id]);

        $member_id = \Yii::$app->user->identity->getId();//获取当前登录用户的id

        $infos = ShippingAddress::find()->where(['member_id'=>$member_id])->all(); //查询出该id下的所有地址信息

        if($model->load(\Yii::$app->request->post()) && $model->validate()){

            $model->member_id = $member_id;
            //如果勾选了默认地址，就要先将该id的所有默认地址设置为0，这个数据不影响表单
            if($model->select ==1){
                foreach ($infos as $info){
                    $info->select =0;
                    $info->save();
                }
            }

            $model->save();
            return $this->redirect(['address/index']);

        }

        return $this->render('address',['model'=>$model,'infos'=>$infos]);

    }



}
