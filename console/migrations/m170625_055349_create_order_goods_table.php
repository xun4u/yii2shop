<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order_goods`.
 */
class m170625_055349_create_order_goods_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {

//id	primaryKey
//order_id	int	订单id
//goods_id	int	商品id
//goods_name	varchar(255)	商品名称
//logo	varchar(255)	图片
//price	decimal	价格
//amount	int	数量
//total	decimal	小计
        $this->createTable('order_goods', [
            'id' => $this->primaryKey(),
            'order_id'=>$this->integer()->comment('订单id'),
            'goods_id'=>$this->integer()->comment('商品id'),
            'goods_name'=>$this->string()->comment('商品名称'),
            'logo'=>$this->string()->comment('图片'),
            'price'=>$this->decimal(10,2)->comment('价格'),
            'amount'=>$this->integer()->comment('数量'),
            'total'=>$this->decimal(10,2)->comment('小计')

        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('order_goods');
    }
}
