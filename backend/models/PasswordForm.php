<?php
namespace backend\models;
use yii\base\Model;

class PasswordForm extends Model{
    //定义表单字段
//    public $username;
    public $old_password; //旧密码
    public $new_password; //新密码
    public $re_password; //确认新密码


    public function rules(){
        return[
            [['old_password','new_password','re_password'],'required'],
            ['old_password','validatePassword'],//自定义规则，验证旧密码的存在
            ['re_password','compare','compareAttribute'=>'new_password','message'=>'两次密码必须一致']
        ];

    }


    public function attributeLabels(){
        return[
            'old_password'=>'旧密码',
            'new_password'=>'新密码',
            're_password'=>'确认密码'
        ];
    }
    //自定义的验证规则 验证密码是否正确
    public function validatePassword(){
        $passwordHash = \Yii::$app->user->identity->password_hash;//从登陆状态组件中获取当前用户密码

        $password = $this->old_password; //表单中填得旧密码

        if(!\Yii::$app->security->validatePassword($password,$passwordHash)){ //验证这2个密码是否匹配
            $this->addError('old_password','旧密码不正确');
        }

    }
}
