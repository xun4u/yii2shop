<?php

use yii\db\Migration;

/**
 * Handles the creation of table `nav`.
 */
class m170618_034552_create_nav_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('nav', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->notNull()->comment('菜单名称'),
            'url'=>$this->string(50)->comment('菜单跳转地址'),
            'parent_id'=>$this->integer()->comment('菜单父级id'),
            'sort'=>$this->integer()->comment('排序')

        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('nav');
    }
}
