<?php
namespace backend\models;

use yii\base\Model;
use yii\rbac\Role;

class UserEditForm extends Model{
    public $username;
    public $email;
    public $status;
    public $roles = [];

    public function rules()
    {
        return [
          [['username','email'],'required'],
            ['email','email'],
            ['status','number'],
            ['roles','safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'email'=>'邮箱',
            'status'=>'状态',
            'roles'=>'角色分配'
        ];
    }

    //加载表单数据
    public function loadData($id){

        $obj = User::findOne(['id'=>$id]);//查出数据库的该id数据
        $roles = \Yii::$app->authManager->getRolesByUser($id);//查出该id的角色

        $this->username = $obj->username;
        $this->email = $obj->email;
        $this->status = $obj->status;

        foreach ($roles as $role){
            $this->roles[] = $role->name;
        }

    }

    //修改
    public function edit($id){

        $obj = User::findOne(['id'=>$id]); //数据库对象
        $authManager = \Yii::$app->authManager;
        $roles = $authManager->getRolesByUser($id); //对应角色对象

        //排除数据库同名冲突,因为用户名不可修改 所以以下判断省略
        /*if($this->username != $obj->username && User::findOne(['username'=>$this->username])){

            $this->addError('username','已存在的用户名');
        }else{
            //修改数据
            $obj->username = $this->username;

        }*/

        //修改数据
        $obj->email = $this->email;
        $obj->status = $this->status;
        //数据保存
        if($obj->save()){
            //清除对应角色
            $authManager->revokeAll($id);

            //循环添加新的角色
            foreach ($this->roles as $roleName){

                $role = $authManager->getRole($roleName);
                $authManager->assign($role,$id);
            }
            return true;

        }
        return false;


    }

}
