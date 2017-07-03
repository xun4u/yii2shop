<?php

use yii\db\Migration;

/**
 * Handles the creation of table `cart`.
 */
class m170623_081655_create_cart_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
//id	primaryKey
//goods_id	int	商品id
//amount	int	商品数量
//member_id	int	用户id
        $this->createTable('cart', [
            'id' => $this->primaryKey(),
            'goods_id'=>$this->integer()->comment('商品id'),
            'amount'=>$this->integer()->comment('商品数量'),
            'member_id'=>$this->integer()->comment('用户id'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('cart');
    }
}
