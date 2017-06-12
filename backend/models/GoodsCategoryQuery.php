<?php
/**
 * Created by PhpStorm.
 * User: lixun
 * Date: 2017/6/11
 * Time: 14:20
 */

namespace backend\models;
use yii\db\ActiveQuery;
use creocoder\nestedsets\NestedSetsQueryBehavior;

class GoodsCategoryQuery extends ActiveQuery
{
    public function behaviors() {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }
}