<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_detail`.
 */
class m170610_031710_create_article_detail_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        //article_detail 文章详情表
        //article_id	primaryKey	文章id
        //content	text	简介
        $this->createTable('article_detail', [
            'article_id' => $this->primaryKey()->comment('文章id'),
            'content'=>$this->text()->comment('简介')

        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article_detail');
    }
}
