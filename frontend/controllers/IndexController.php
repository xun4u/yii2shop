<?php
/**
 * Created by PhpStorm.
 * User: lixun
 * Date: 2017/6/21
 * Time: 19:36
 */

namespace frontend\controllers;



use backend\models\Goods;
use backend\models\GoodsCategory;
use frontend\components\AccessFilter;
use frontend\components\SphinxClient;
use frontend\models\Cart;
use frontend\models\Order;
use frontend\models\OrderGoods;
use frontend\models\ShippingAddress;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;

class IndexController extends Controller
{

    public function behaviors(){

        return [
            'acf'=>[
                'class'=>AccessFilter::className(),
                'only'=>['order','order-list','pay','order-save','confirm'],

                ]
            ];

    }


//    public $layout ='index'; //整个控制器都享受
    //首页显示
    public function actionIndex()
    {

        $this->layout = 'index'; //只有这个操作享受
        return $this->render('index');

    }


    //商品列表页
    public function actionList($cate_id)
    {

        $this->layout = 'member';

        /*$cate = GoodsCategory::findOne(['id'=>$cate_id]);//先找出商品分类表里的分类
        if($cate->depth ==2){
            $models = Goods::findAll(['goods_category_id'=>$cate_id]);//找到3级分类商品
        }else{
            $leaves_all = $cate->leaves()->all();//找到该分类的所有叶分类
            $cate_ids =[];
            foreach ($leaves_all as $leaves){
                $cate_ids[]=$leaves->id; //所有叶分类的id
            }
            $models = Goods::find()->where(['in','goods_category_id',$cate_ids])->all();//商品列表直接查分类id
        }*/

        //优化分类，一共三级分类，只有在第三级分类下才有商品
        $cate = GoodsCategory::findOne(['id' => $cate_id]);//先找出商品分类表里的分类
        $leaves_all = $cate->leaves()->all();//找到该分类的所有叶分类(最底层的分类)
        $cate_ids[] = $cate_id;
        foreach ($leaves_all as $leaves) {
            $cate_ids[] = $leaves->id; //所有叶分类的id
        }
        $models = Goods::find()->where(['in', 'goods_category_id', $cate_ids])->all();//商品列表直接查分类id
        return $this->render('list', ['models' => $models]);

    }

    //商品详情
    public function actionDetail($id)
    {
        $this->layout = 'member';
        $model = Goods::findOne(['id' => $id]);
        return $this->render('goods-detail', ['model' => $model]);

    }


    //添加到购物车
    public function actionAddToCart()
    {
        //获取前台提交的商品id和购买数量

        $goods_id = \Yii::$app->request->post('goods_id');
        $amount = \Yii::$app->request->post('amount');

        //容错判断，数据库中是否有这个商品
        $goods = Goods::findOne(['id' => $goods_id]);
        if ($goods == null) {
            throw new NotFoundHttpException('商品不存在');
        }
        //获取购物车中的数据 从cookie到$cart
        $cookies = \Yii::$app->request->cookies;
        $cookie_cart = $cookies->get('cart');
        if ($cookie_cart == null) {
            $cart = [];
        } else {
            $cart = unserialize($cookie_cart->value);
        }

        //判断是否登录 分2种方式存存购物车数据
        if (\Yii::$app->user->isGuest) {  //游客的购物车数据存cookie

            //将商品id和数量存到cookie中
            //商品id和数量以键值对形式数组保存 并序列化保存于cookie中
            //模拟数据：$cart = [3=>4] 3是商品id 4是数量

            //检查购物车数组有没有该商品，有-数量叠加，没有-新增键值对
            if (key_exists($goods->id, $cart)) {
                $cart[$goods_id] += $amount; //找出该商品，对应值叠加新amount
            } else {
                $cart[$goods_id] = $amount;
            }
            $cookies = \Yii::$app->response->cookies;
            $cookie_cart = new Cookie([
                'name' => 'cart',
                'value' => serialize($cart),
            ]);

            $cookies->add($cookie_cart);


        } else {  //会员的购物车数据存数据库
            $member_id = \Yii::$app->user->getId();
            //登录后，添加商品是直接写入到数据库，先判断数据库是否有重名商品，做新增和修改数量的操作
            $obj = Cart::findOne(['member_id' => $member_id, 'goods_id' => $goods_id]);
            if ($obj == null) {
                $model = new Cart();
                $model->member_id = $member_id;
                $model->goods_id = $goods_id;
                $model->amount = $amount;
                $model->save();
            } else {
                $obj->amount += $amount;
                $obj->save();
            }

        }
        return $this->redirect(['index/cart']);


    }


    //购物车页面显示
    public function actionCart()
    {

        $this->layout = 'cart';
        if (\Yii::$app->user->isGuest) {
            //游客取cookie中的购物车数据
            $cookies = \Yii::$app->request->cookies;
            $cookie_cart = $cookies->get('cart');
            if ($cookie_cart == null) {
                $cart = [];
            } else {
                $cart = unserialize($cookie_cart);
            }
            $models = [];
            foreach ($cart as $goods_id => $amount) {
//            $goods = Goods::find()->where(['id'=>$goods_id])->asArray()->one();
                $goods = Goods::findOne(['id' => $goods_id])->attributes;
                $goods['amount'] = $amount;
                $models[] = $goods;
            }
//            var_dump($models);exit;
        } else {
            //不是游客 从数据库读数据
            $datas = Cart::find()->where(['member_id' => \Yii::$app->user->getId()])->asArray()->all();
            $cart = ArrayHelper::map($datas, 'goods_id', 'amount');
            $models = [];
            foreach ($cart as $goods_id => $amount) {
                $goods = Goods::findOne(['id' => $goods_id])->attributes;
                $goods['amount'] = $amount;
                $models[] = $goods;
            }

        }
        return $this->render('cart', ['models' => $models]);
    }


    //购物车修改数据
    public function actionUpdateCart()
    {

        $goods_id = \Yii::$app->request->post('goods_id');
        $amount = \Yii::$app->request->post('amount');

        $goods = Goods::findOne(['id' => $goods_id]);
        if ($goods == null) {
            throw new NotFoundHttpException('商品不存在');
        }
        if (\Yii::$app->user->isGuest) {
            $cookies = \Yii::$app->request->cookies;
            $cookie_cart = $cookies->get('cart');
            if ($cookie_cart == null) {
                $cart = [];
            } else {
                $cart = unserialize($cookie_cart->value);
            }

            if ($amount) {
                $cart[$goods_id] = $amount;
            } else {
                if (key_exists($goods->id, $cart)) unset($cart[$goods_id]);
            }
            $cookies = \Yii::$app->response->cookies;
            $cookie_cart = new Cookie([
                'name' => 'cart',
                'value' => serialize($cart)
            ]);

            $cookies->add($cookie_cart);


        } else {
            $member_id = \Yii::$app->user->getId();
            $model = Cart::findOne(['member_id' => $member_id, 'goods_id' => $goods_id]);
            if ($amount) {
                $model->amount = $amount;
                $model->save();
            } else {
                $model->delete();
            }


        }

    }


    //生成订单页面
    public function actionOrder()
    {
        $this->layout = 'cart';
        $member_id = \Yii::$app->user->getId();

        //获取用户收货地址
        $addresses = ShippingAddress::findAll(['member_id' => $member_id]);
        //送货地址直接视图静态读取
        //支付方式直接视图静态读取

        //获取订单商品
        $datas = Cart::findAll(['member_id' => $member_id]);
        $cart = ArrayHelper::map($datas, 'goods_id', 'amount');
        $models = [];
        foreach ($cart as $goods_id => $amount) {
            $goods = Goods::findOne(['id' => $goods_id])->attributes;
            $goods['amount'] = $amount;
            $models[] = $goods;
        }


        return $this->render('order', ['addresses' => $addresses, 'models' => $models]);

    }

    //订单保存
    public function actionOrderSave()
    {
        //var_dump(\Yii::$app->request->post());exit;

        $address_id = \Yii::$app->request->post('address_id'); //获取地址id
        $delivery_id = \Yii::$app->request->post('delivery_id');//获取送货方式id
        $payment_id = \Yii::$app->request->post('payment_id');//获取付款方式id
        $total = \Yii::$app->request->post('total');//获取总金额
        //根据各种id查询order表需要的数据
        $address = ShippingAddress::findOne(['id' => $address_id]);
        //var_dump($address_id);exit;

        //新建订单对象
        $order = new Order();
        //给字段赋值
        $order->member_id = \Yii::$app->user->getId();
        $order->name = $address->name;
        $order->province = $address->province->name;
        $order->city = $address->city->name;
        $order->area = $address->area->name;
        $order->address = $address->address;
        $order->tel = $address->tel;
        $order->total = $total;
        //客户提交的数据都需要验证，服务端保存的静态数据不需要验证
        if ($order->validate()) {
            $order->delivery_id = $delivery_id;
            $order->delivery_name = Order::$deliveries[$delivery_id]['delivery_name'];
            $order->delivery_price = Order::$deliveries[$delivery_id]['delivery_price'];
            $order->payment_id = $payment_id;
            $order->payment_name = Order::$payments[$payment_id]['payment_name'];
        }
        $order->create_time = time();
        //4种状态 订单状态（0已取消1待付款2待发货3待收货4完成）
        //除了货到付款 其余的都是待付款
        $payment_id == 1 ? $order->status = 2 : $order->status = 1;

        //并发处理-保存成功的时候同时保存数据到订单详情表
        //开启事物
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $order->save();
            //如果订单表保存成功，就开始保存到订单详情表
            //查询购物车
            $carts = Cart::findAll(['member_id' => \Yii::$app->user->getId()]);
            foreach ($carts as $cart) {
                $order_goods = new OrderGoods();
                $order_goods->order_id = $order->id;
                $order_goods->goods_id = $cart->goods_id;
                $goods = Goods::findOne(['id' => $cart->goods_id]);
                if ($goods->stock < $cart->amount) {
                    throw new Exception('已经没货了');
                }
                $order_goods->amount = $cart->amount;
                //商品的名称价格图片有可能变动不能从商品表中读取，应当从订单表中读取?未实现
                $order_goods->goods_name = $goods->name;
                $order_goods->logo = $goods->logo;
                $order_goods->price = $goods->shop_price;
                $order_goods->total = $cart->amount * $goods->shop_price;
                $order_goods->save();
                //减少库存商品
                $goods->stock -= $cart->amount;
                $goods->save();
                //清空购物车
                $cart->delete();

            }
            $transaction->commit();//提交事务
        } catch (Exception $e) {
            $transaction->rollBack();//回滚
        }

        //返回订单的id 给下一级查询做为参数
        return $order->id;

    }

    //订单提交后中转页面
    public function actionPay()
    {

        $this->layout = 'cart';
        return $this->render('pay');

    }

    //查看订单列表
    public function actionOrderList()
    {
        $this->layout = 'member';

        //查该用户的所有订单
        $orders = Order::findAll(['member_id' => \Yii::$app->user->id]);
        $ids = [];
        foreach ($orders as $order) {
            $ids[] = $order->id;
        }
        $models = OrderGoods::find()->where(['in', 'order_id', $ids])->all();
//        var_dump($models);exit;

        return $this->render('order-list', ['models' => $models]);

    }

    //确认收货
    public function actionConfirm($id)
    {

        $order = Order::findOne(['id' => $id]);
        $order->status = 4;
        $order->save();
        return $this->redirect(['index/order-list']);

    }


    //全文检索 coreseek
    public function actionTest()
    {
        $cl = new SphinxClient();
        $cl->SetServer('127.0.0.1', 9312);
//$cl->SetServer ( '10.6.0.6', 9312);
//$cl->SetServer ( '10.6.0.22', 9312);
//$cl->SetServer ( '10.8.8.2', 9312);
        $cl->SetConnectTimeout(10);
        $cl->SetArrayResult(true);
// $cl->SetMatchMode ( SPH_MATCH_ANY);
        $cl->SetMatchMode(SPH_MATCH_ALL);//查询模式
        $cl->SetLimits(0, 1000);
        $info = '惠普电脑';//查询的关键字
        $res = $cl->Query($info, 'goods');//shopstore_search配置中设置的索引名称
//print_r($cl);
        var_dump($res);
    }

    //首页搜索
    public function actionSearch()
    {
        $this->layout ='member';

        $query = Goods::find();
        if ($keywords = \Yii::$app->request->get('keywords')) {
            $cl = new SphinxClient();
            $cl->SetServer('127.0.0.1', 9312);
            $cl->SetConnectTimeout(10);
            $cl->SetArrayResult(true);
            $cl->SetMatchMode(SPH_MATCH_ALL);//查询模式
            $cl->SetLimits(0, 1000);
//            $info = '惠普电脑';//查询的关键字
            $res = $cl->Query($keywords, 'goods');//shopstore_search配置中设置的索引名称

            if (!isset($res['matches'])) {
                $query->where(['id' => 0]);
            } else {
                //获取商品id
                $ids = ArrayHelper::map($res['matches'], 'id', 'id');
                $models = $query->where(['in', 'id', $ids])->all();

//                var_dump($models);exit;
                $keywords = array_keys($res['words']);
                $options = array(
                    'before_match' => '<span style="color:red;">',
                    'after_match' => '</span>',
                    'chunk_separator' => '...',
                    'limit' => 80, //如果内容超过80个字符，就使用...隐藏多余的的内容
                );
            //关键字高亮
//        var_dump($models);exit;

                foreach ($models as $index => $item) {
                    $name = $cl->BuildExcerpts([$item->name], 'goods', implode(',', $keywords), $options); //使用的索引不能写*，关键字可以使用空格、逗号等符号做分隔，放心，sphinx很智能，会给你拆分的
                    $models[$index]->name = $name[0];
                }
            }


        }
        return $this->render('list',['models'=>$models]);
    }
}