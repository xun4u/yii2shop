<?php
namespace backend\models;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\rbac\Role;

class RoleForm extends Model{

    public $name;
    public $description;
    public $permissions = [];//权限名列表

    public function rules()
    {
        return [
            [['name','description'],'required'],
            ['permissions','safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'name'=>'角色名',
            'description'=>'描述',
            'permissions'=>'权限列表'
        ];
    }

    //获取权限列表
    public static function getPermissionOptions(){

        $objs = \Yii::$app->authManager->getPermissions();

        return ArrayHelper::map($objs,'name','description');//抽出数组对象，以键值对的形式返回
    }

    //添加
    public function add(){
        $authManager = \Yii::$app->authManager;

        if($authManager->getRole($this->name)){
            $this->addError('name','已经存在该角色');
        }else{

            $role = $authManager->createRole($this->name); //创建角色
            $role->description = $this->description;

            //如果角色创建成功，该角色关联权限
            if($authManager->add($role)){

                foreach ($this->permissions as $permissionName) {
                    $permission = $authManager->getPermission($permissionName);//获取权限对象
                    $authManager->addChild($role,$permission); //角色关联权限

                }
            }
            return true;
        }
        return false;

    }

    //表单加载数据
    public function loadData(Role $role){
        $this->name = $role->name;
        $this->description = $role->description;
        //获取该角色的权限对象
        $permissions = \Yii::$app->authManager->getPermissionsByRole($role->name);

        foreach ($permissions as $permission){
            $this->permissions[] = $permission->name;

        }
    }

    //修改
    public function edit(Role $role,$name){

        $authManager = \Yii::$app->authManager;

        if($name != $this->name && $authManager->getRole($this->name)){
            $this->addError('name','角色名已存在');

        }else{
            //更新
            $role->name = $this->name;
            $role->description = $this->description;

            if($authManager->update($name,$role)){
                //清除该角色权限
                $authManager->removeChildren($role);

                //重新赋予权限

                foreach ($this->permissions as $permissionName) {
                    $permission = $authManager->getPermission($permissionName);//获取权限对象
                    $authManager->addChild($role,$permission); //角色关联权限
                }
                return true;
            }

        }
        return false;

    }

}
