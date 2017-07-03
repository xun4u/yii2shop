<?php
namespace frontend\models;

use yii\base\Model;

class LoginForm extends Model{

    public $username;
    public $password;
    public $code;
    public $remember;

    public function rules()
    {
        return [
            [['username','password'],'required'],
            ['code','captcha'],
            ['remember','boolean']
        ];
    }

    public function attributeLabels()
    {
        return [
            'username'=>'用户名：',
            'password'=>'密码：',
            'code'=>'验证码：',
            'remember'=>' '
        ];
    }


    //登录的验证
    public function checkLogin(){
        //先验证账号
        $member = Member::findOne(['username'=>$this->username]);
        if($member){
            //再验证密码
            if(\Yii::$app->security->validatePassword($this->password,$member->password_hash)){


                //先判断保存登录的复选框是否勾选，然后设置cookie过期时间
                $duration = $this->remember ? 7*24*3600 : 0;

                //写入登录状态
                \Yii::$app->user->login($member,$duration);

                $member->last_login_time = time();
                $member->last_login_ip = \Yii::$app->request->userIP;

                $member->save(false);

                return true;
            }else{
                $this->addError('username','用户名不存在或密码错误');
            }
        }else{
            $this->addError('username','用户名不存在或密码错误');

        }
        return false;
    }




}
