<?php

namespace frontend\models;

use frontend\models\Locations;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "shipping_address".
 *
 * @property integer $id
 * @property integer $member_id
 * @property string $name
 * @property integer $p_id
 * @property integer $c_id
 * @property integer $a_id
 * @property string $address
 * @property string $tel
 * @property integer $select
 */
class ShippingAddress extends \yii\db\ActiveRecord
{



    //建立关系 分别对应location表中的省 市 县
    public function getProvince(){
       return $this->hasOne(Locations::className(),['id'=>'p_id']);

    }
    public function getCity(){
        return $this->hasOne(Locations::className(),['id'=>'c_id']);
    }
    public function getArea(){
        return $this->hasOne(Locations::className(),['id'=>'a_id']);
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shipping_address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'p_id', 'c_id', 'a_id', 'select'], 'integer'],
            [['name', 'address'], 'string', 'max' => 255],
            [['tel'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'name' => '收货人：',
            'p_id' => '所在地区：',
            'c_id' => 'C ID',
            'a_id' => 'A ID',
            'address' => '详细地址：',
            'tel' => '手机号码：',
            'select' => '设为默认地址',
        ];
    }
}
