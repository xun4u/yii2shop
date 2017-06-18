<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "nav".
 *
 * @property integer $id
 * @property string $name
 * @property string $url
 * @property integer $parent_id
 * @property integer $sort
 */
class Nav extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'nav';
    }

    //自我关联：父级菜单
    public function getChildren(){
        return $this->hasMany(self::className(),['parent_id'=>'id']);
    }


    public static function getParentOptions(){


        $options =['0'=>'顶级菜单'] + ArrayHelper::map(Nav::find()->where(['parent_id'=>0])->asArray()->all(),'id','name');

    // $options = ArrayHelper::merge($parent,$models);
        return $options;

    }

    public static function getUrl(){

        $permisions = Yii::$app->authManager->getPermissions();
        $options = [];
        foreach ($permisions as $permision){

            $options[$permision->name] =$permision->name ;
        }

        $options = [''=>'无跳转']+$options;
        return $options;

    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['parent_id', 'sort'], 'integer'],

            [['name', 'url'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '菜单名称',
            'url' => '菜单跳转路由',
            'parent_id' => '父级菜单',
            'sort' => '排序',
        ];
    }
}
