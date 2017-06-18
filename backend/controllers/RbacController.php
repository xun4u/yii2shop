<?php

namespace backend\controllers;

use backend\models\PermissionForm;
use backend\models\RoleForm;
use yii\web\NotFoundHttpException;

class RbacController extends \yii\web\Controller
{


    /**
     * 权限的增删改查
     */

    //添加权限
    public function actionPermissionAdd(){

        $model = new PermissionForm();

        if($model->load(\Yii::$app->request->post()) && $model->validate()){

            if($model->add()){
                //调用方法创建并添加权限,成功则返回true
                \Yii::$app->session->setFlash('success','添加权限成功');

                return $this->redirect(['permission-index']);

            }
        }

        return $this->render('permission-add',['model'=>$model]);
    }

    //显示权限列表
    public function actionPermissionIndex(){

        $models = \Yii::$app->authManager->getPermissions(); //获取所有权限数据

        return $this->render('permission-index',['models'=>$models]);
    }

    //修改权限
    public function actionPermissionEdit($name){

        //获取当前权限对象
        $permission = \Yii::$app->authManager->getPermission($name);

        if($permission == null){
            throw new NotFoundHttpException('权限不存在');
        }

        $model = new PermissionForm();

        //将权限对象的值赋值给空表单模型，这样修改的时候回显出该权限对象数据
        $model->loadData($permission);

        //修改-新的表单提交
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //调用方法修改
            if($model->edit($permission,$name)){
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['permission-index']);
            }
        }

        return $this->render('permission-add',['model'=>$model]);
    }

    //删除权限
    public function actionPermissionDel($name){
        $permission = \Yii::$app->authManager->getPermission($name);
        if($permission == null){
            throw new NotFoundHttpException('权限不存在');
        }
        \Yii::$app->authManager->remove($permission);
        \Yii::$app->session->setFlash('success','删除成功');

        return $this->redirect(['permission-index']);

    }


    /**
     * 角色的增删改查
     */

    //增加角色
    public function actionRoleAdd(){

        $model = new RoleForm();

        if($model->load(\Yii::$app->request->post()) && $model->validate()){

            if($model->add()){
                \Yii::$app->session->setFlash('success','添加角色成功');
                $this->redirect(['role-index']);
            }
        }

        return $this->render('role-add',['model'=>$model]);
    }

    //角色显示
    public function actionRoleIndex(){

        $models = \Yii::$app->authManager->getRoles();

        return $this->render('role-index',['models'=>$models]);
    }

    //修改角色
    public function actionRoleEdit($name){

        $role = \Yii::$app->authManager->getRole($name);
        if($role == null){
            throw new NotFoundHttpException('不存在的角色');
        }
        $model = new RoleForm();

        $model->loadData($role);//加载数据

        //表单提交
        if($model->load(\Yii::$app->request->post()) && $model->validate()){

            if($model->edit($role,$name)){
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['role-index']);
            }
        }

        return $this->render('role-add',['model'=>$model]);

    }

    //删除
    public function actionRoleDel($name){
        $role = \Yii::$app->authManager->getRole($name);
        if($role == null){
            throw new NotFoundHttpException('不存在的角色');
        }
        \Yii::$app->authManager->remove($role);
        \Yii::$app->session->setFlash('success','删除成功');

        return $this->redirect(['role-index']);

    }

}
