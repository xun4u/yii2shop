
<?php
use yii\helpers\Html;

foreach ($categories as $k=>$cate1)://遍历一级分类?>
    <div class="cat <?=($k==0)? 'item1':''?>" >
        <h3><?=Html::a($cate1->name,['index/list','cate_id'=>$cate1->id])?><b></b></h3>
        <div class="cat_detail">
            <?php foreach ($cate1->children as $k2=>$cate2)://遍历二级分类?>
                <dl class="<?=($k2==0)? 'dl_1st':''?>">
                    <dt><?=Html::a($cate2->name,['index/list','cate_id'=>$cate2->id])?></dt>
                    <dd>
                        <?php foreach ($cate2->children as $cate3)://遍历三级分类?>
                            <?=Html::a($cate3->name,['index/list','cate_id'=>$cate3->id])?>
                        <?php endforeach;?>
                    </dd>
                </dl>
            <?php endforeach;?>
        </div>
    </div>
<?php endforeach;?>
