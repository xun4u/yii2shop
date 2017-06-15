<?php
namespace backend\models;

use yii\base\Model;

class LoginForm extends Model {
    public $username;
    public $password;
    public $rememberMe;
    public $code;

    public function rules(){
        return [
            [['username','password'],'required'],
            ['username','validateUsername'], //自定义验证 验证登录时账号密码是否正确
            ['code','captcha'],
            ['rememberMe','boolean']
        ];

    }

    public function attributeLabels(){
        return[
            'username'=>'用户名',
            'password'=>'密码',
            'rememberMe'=>'记住我',
        ];
    }

    //自定义验证方法
    public function validateUsername(){
        $user = User::findOne(['username'=>$this->username]);
        if($user){ //分步验证，先验证数据库是否存在这个用户名，存在在验证密码的正确性
            if(\Yii::$app->security->validatePassword($this->password,$user->password_hash)){
                //如果正确 就登录，调用user组件来保存登录状态

                //自动登录,先判断是够勾选自动登录框，默认登录cookie的过期时间是0，要自动登录只需设置cookie过期时间
                $duration = $this->rememberMe ? 7*24*3600 : 0 ; //设置cookie过期时间

                \Yii::$app->user->login($user,$duration); //写入登录状态
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
