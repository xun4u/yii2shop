<?php
namespace backend\models;
use yii\base\Model;
use yii\rbac\Permission;

class PermissionForm extends Model{

    public $name;//权限名称
    public $description;//权限描述


    public function rules(){

        return[
            [['name','description'],'required']
        ];
    }

    public function attributeLabels()
    {
        return [
            'name'=>'权限名称',
            'description'=>'描述',
        ];
    }

    //添加权限
    public function add(){
        $authManager = \Yii::$app->authManager;
        //权限存在性的判断
        if($authManager->getPermission($this->name)){
            $this->addError('name','名称已经存在');
        }else{
            $permission =  $authManager->createPermission($this->name);//创建权限
            $permission->description = $this->description;

            return $authManager->add($permission);//权限添加到数据库

        }
        return false;

    }

    //表单加载权限数据
    public function loadData(Permission $permission){
        $this->name = $permission->name;
        $this->description = $permission->description;

    }

    //修改
    public function edit(Permission $permission,$name){
        $authManager = \Yii::$app->authManager;

        //原对象数据和表单的数据进行比对,判断新修改的是否数据库中已经存在
        if($name != $this->name && $authManager->getPermission($this->name)){

            $this->addError('name','权限已存在');
        }else{

            $permission->name = $this->name;
            $permission->description = $this->description;
            //更新（老名字，新对象）
            return $authManager->update($name,$permission);
        }
        return false;
    }
}
