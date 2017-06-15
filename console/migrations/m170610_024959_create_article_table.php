<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article`.
 */
class m170610_024959_create_article_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        //id	primaryKey
        //name	varchar(50)	名称
        //intro	text	简介
        //article_category_id	int()	文章分类id
        //sort	int(11)	排序
        //status	int(2)	状态(-1删除 0隐藏 1正常)
        //create_time	int(11)	创建时间
        $this->createTable('article', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->notNull()->comment('名称'),
            'intro'=>$this->text()->comment('简介'),
            'article_category_id'=>$this->integer()->notNull()->comment('文章分类id'),
            'sort'=>$this->integer(11)->comment('排序'),
            'status'=>$this->integer(2)->comment('状态'),
            'create_time'=>$this->integer(11)->comment('创建时间')

        ]);

        //创建索引
        $this->createIndex(
            'idx-article-article_category_id',
            'article',
            'article_category_id'
        );

        //创建外键约束
        $this->addForeignKey(
            'fk-article-article_category_id',
            'article',
            'article_category_id',
            'article_category',
            'id',
            'CASCADE'
        );

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        //先删除外键
        $this->dropForeignKey(
            'fk-article-article_category_id',
            'article'
        );
        //再删除表
        $this->dropTable('article');
    }
}
