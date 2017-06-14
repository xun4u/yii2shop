<?php
/**
 * Created by PhpStorm.
 * User: lixun
 * Date: 2017/6/13
 * Time: 22:21
 */

namespace backend\models;


use yii\base\Model;
use yii\db\ActiveQuery;

class GoodsSearchForm extends Model
{
    public $name;
    public $sn;
    public $minPrice;
    public $maxPrice;

    public function rules(){

        return [
            [['name','sn'],'string'],
            [['minPrice','maxPrice'],'double']
        ];
    }

    //搜索功能
    public function search(ActiveQuery $query){
        $this->load(\Yii::$app->request->get());//表单模型加载get数据

        if($this->name){
            $query->andWhere(['like','name',$this->name]);
        }
        if($this->sn){
            $query->andWhere(['like','sn',$this->sn]);
        }
        if($this->minPrice){
            $query->andWhere(['>=','shop_price',$this->minPrice]);
        }
        if($this->maxPrice){
            $query->andWhere(['<=','shop_price',$this->maxPrice]);
        }
        return $query;
    }
}