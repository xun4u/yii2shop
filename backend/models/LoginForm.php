<?php
namespace backend\models;

use yii\base\Model;

class LoginForm extends Model {
    public $username;
    public $password;
    public $code;

    public function rules(){
        return [
            [['username','password'],'required'],
            ['username','validateUsername'], //自定义验证 验证登录时账号密码是否正确
            ['code','captcha']
        ];

    }

    public function attributeLabels(){
        return[
            'username'=>'用户名',
            'password'=>'密码',
        ];
    }

    //自定义验证方法
    public function validateUsername(){
        $user = User::findOne(['username'=>$this->username]);
        if($user){ //分步验证，先验证数据库是否存在这个用户名，存在在验证密码的正确性
            if(\Yii::$app->security->validatePassword($this->password,$user->password_hash)){
                //如果正确 就登录，调用user组件来保存登录状态
                \Yii::$app->user->login($user); //写入登录状态
                $user->login_time = time(); //设置登录时间
                $user->login_ip = \Yii::$app->request->userIP;
                $user->save(false);


            }else{
                $this->addError('username','账号或密码不正确');
            }
        }else{
            //账号不存在的情况
            $this->addError('username','账号或密码不正确');
        }

    }

}
