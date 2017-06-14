<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "goods_images".
 *
 * @property integer $id
 * @property integer $goods_id
 * @property integer $url
 * @property integer $sort
 */
class GoodsImages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_images';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'url', 'sort'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'goods_id' => '商品ID',
            'url' => '图片地址',
            'sort' => '排序',
        ];
    }
}
