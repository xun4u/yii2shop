<?php

namespace backend\controllers;

use backend\components\RbacFilter;
use backend\models\LoginForm;
use backend\models\PasswordForm;
use backend\models\User;
use backend\models\UserEditForm;
use yii\filters\AccessControl;

class UserController extends \yii\web\Controller
{

    //过滤器
    public function behaviors(){
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
                'only'=>['index','add','reset-pwd','edit','del']
            ],

        ];


    }
    //用户列表显示
    public function actionIndex()
    {

        $models = User::find()->all();


        return $this->render('index',['models'=>$models]);
    }

    //用户的添加
    public function actionAdd(){
        $model = new User();


        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //保存之前在活动记录中有行为实现了添加时间和密码加密
            $model->save(false);
            return $this->redirect(['user/login']);

        }
        return $this->render('add',['model'=>$model]);
    }

    //密码修改
    public function actionResetPwd(){
        $model = new PasswordForm();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $user = \Yii::$app->user->identity;//当前用户的登录信息
            $user->password_hash = \Yii::$app->security->generatePasswordHash($model->new_password);
            //将其密码设置为表单中的新密码

            if($user->save(false)){
                \Yii::$app->session->setFlash('success','密码修改成功');
                return $this->redirect(['user/index']);
            }else{
                var_dump($user->getErrors());exit;
            }
        }

        return $this->render('passwd',['model'=>$model]);

    }
    //用户的角色修改
    public function actionEdit($id){
        $model = new UserEditForm();

        $model->loadData($id);//表单加载回显数据

        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //提交保存
            if($model->edit($id)){
                //修改执行成功
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['user/index']);

            }

        }

        return $this->render('user-edit',['model'=>$model]);

    }

    //删除
    public function actionDel($id){
        $model = User::findOne(['id'=>$id]);
        $model->delete();
    }

    //登陆
    public function actionLogin(){
        $model = new LoginForm();
        if($model->load(\Yii::$app->request->post())){
            if($model->validate()){
                //因为验证账号和密码是在表单模型中进行的，验证成功后表单模型已经进行了登录并保存了登录状态
                //到这一步说明已经验证并登录，直接设置提示信息即可
                \Yii::$app->session->setFlash('success','登录成功');
                return $this->redirect(['user/home']);

            }
        }

        return $this->render('login',['model'=>$model]);

    }

    //用户注销
    public function actionLogout(){
        \Yii::$app->user->logout();
        \Yii::$app->session->setFlash('success','注销成功');
        return $this->redirect(['user/login']);
    }


    //登录后的主页
    public function actionHome(){
        return $this->render('home');

    }

}
