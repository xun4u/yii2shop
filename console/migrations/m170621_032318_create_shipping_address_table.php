<?php

use yii\db\Migration;

/**
 * Handles the creation of table `shipping_address`.
 */
class m170621_032318_create_shipping_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('shipping_address', [
            'id' => $this->primaryKey(),
            'member_id'=>$this->integer()->comment('用户的ID'),
            'name'=>$this->string()->comment('收货人姓名'),
            'p_id'=>$this->integer()->comment('省id'),
            'c_id'=>$this->integer()->comment('市id'),
            'a_id'=>$this->integer()->comment('县id'),
            'address'=>$this->string()->comment('详细地址'),
            'tel'=>$this->string(20)->comment('联系电话'),
            'select'=>$this->integer(1)->comment('是否默认收货地址'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('shipping_address');
    }
}
