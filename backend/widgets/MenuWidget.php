<?php
namespace backend\widgets;
use yii\bootstrap\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\bootstrap\Widget;
use Yii;
//将菜单栏做成一个小部件,模板页面通过调用组件来显示菜单
class MenuWidget extends Widget
{
    //小部件实例化后要执行的代码
    public function init()
    {
        parent::init();
    }

    //小部件被调用时执行的代码,也就是菜单栏
    public function run()
    {
        NavBar::begin([
            'brandLabel' => '后台管理',
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]);

        $leftMenuItems = [];
        $rightMenuItems = [];

        if (Yii::$app->user->isGuest) {

            $rightMenuItems[] = ['label' => '登录', 'url' => Yii::$app->user->loginUrl];
        } else {

            //不是游客
            $rightMenuItems = [
                ['label'=>'注销('.Yii::$app->user->identity->username.')','url'=>['user/logout']],
                ['label'=>'修改密码','url'=>['user/reset-pwd']],

            ];

            /*$leftMenuItems = [
                ['label'=>'商品管理','items'=>[
                    ['label'=>'商品列表','url'=>['goods/index']],
                    ['label'=>'添加商品','url'=>['goods/add']],
                ]],
                ['label'=>'品牌管理','items'=>[
                    ['label'=>'品牌列表','url'=>['brand/index']],
                    ['label'=>'添加品牌','url'=>['brand/add']],
                ]],
                ['label'=>'商品分类','items'=>[
                    ['label'=>'分类列表','url'=>['goods-category/index']],
                    ['label'=>'添加分类','url'=>['goods-category/add']],
                ]],
                ['label'=>'文章管理','items'=>[
                    ['label'=>'文章列表','url'=>['article/index']],
                    ['label'=>'添加文章','url'=>['article/add']],
                ]],
                ['label'=>'用户管理','items'=>[
                    ['label'=>'用户列表','url'=>['user/index']],
                    ['label'=>'添加用户','url'=>['user/add']],
                ]]
            ];*/

            //动态显示菜单

            $menus = \backend\models\Nav::findAll(['parent_id'=>0]);
            foreach ($menus as $menu){
                $item = ['label'=>$menu->name,'items'=>[]];//拼凑一级菜单
                //获取二级菜单遍历
                foreach ($menu->children as $child){

                    //根据用户权限判断，该菜单是否显示，二级菜单正好对应了 控制器/操作 和权限名称对应，也就是url
                    if(Yii::$app->user->can($child->url)){
                        $item['items'][]=  ['label'=>$child->name,'url'=>[$child->url]];//拼凑二级菜单
                    }

                }
                //如果一级菜单没有子菜单，就不显示
                if(!empty($item['items'])){
                    $leftMenuItems[] = $item;
                }

            }

        }


        echo Nav::widget([
            'options' => ['class' => 'navbar-nav'],
            'items' => $leftMenuItems,
        ]);

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => $rightMenuItems,
        ]);

        NavBar::end();

    }


}