<?php

namespace frontend\controllers;

use frontend\models\Cart;
use frontend\models\LoginForm;
use frontend\models\Member;

use Flc\Alidayu\Client;
use Flc\Alidayu\App;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;
use Flc\Alidayu\Requests\IRequest;
use yii\helpers\Url;

class MemberController extends \yii\web\Controller
{
//    public $layout = false; //取消布局文件
    public $layout = 'login';//定义当前的布局文件


    public function actionIndex()
    {
        return $this->render('index');
    }

    //用户注册
    public function actionRegister(){
        $model = new Member();
        $model->scenario = Member::SCENARIO_REGISTER;
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->save(false);
            \Yii::$app->session->setFlash('success','注册成功');
            return $this->redirect(['member/login']);
        }

        return $this->render('register',['model'=>$model]);
    }

    //用户登录
    public function actionLogin(){

        $model = new LoginForm();

        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //验证，写入状态，保存登录时间等（方法：checkLogin()）
             if($model->checkLogin()){

                 \Yii::$app->session->setFlash('success','登录成功');
                 //登录成功后将cookie中的购物车数据同步到数据表,并清空cookie
                 $member_id = \Yii::$app->user->getId();
                 $cookies = \Yii::$app->request->cookies;
                 $cookie_cart = $cookies->get('cart');
                 if($cookie_cart ==null){
                     $cart = [];
                 }else{
                     $cart = unserialize($cookie_cart->value);
                 }
                 //购物车缓存不为空才执行,遍历缓存,对应查找数据库的商品id
                 //如果数据没该商品记录就是新增，有则是追加数量
                 if(!empty($cart)){
                     foreach ($cart as $goods_id=>$amount){
                         $obj = Cart::findOne(['member_id'=>$member_id,'goods_id'=>$goods_id]);
                         if($obj == null){
                             $obj = new Cart();
                             $obj->member_id = $member_id;
                             $obj->goods_id = $goods_id;
                             $obj->amount = $amount;
                             $obj->save();
                         }else{
                             $obj->amount += $amount;
                             $obj->save();
                         }
                     }
                     //清除cookie
                     \Yii::$app->response->cookies->remove($cookie_cart);
                 }

                 return $this->redirect(['index/index']);
             }
             //登录后跳回之前页面
//            var_dump(Url::previous());
//             var_dump(\Yii::$app->user);exit;


        }

        return $this->render('login',['model'=>$model]);

    }

    //注销登录
    public function actionLogout(){
        \Yii::$app->user->logout();
        return $this->redirect(['member/login']);

    }

    //测试短信发送
    public function actionSendSms(){

        /*// 配置信息
        $config = [
            'app_key'    => '24485762',
            'app_secret' => 'b3e205196ab1ad483eca51202acce011',
            // 'sandbox'    => true,  // 是否为沙箱环境，默认false
        ];

        // 使用方法一
        $client = new Client(new App($config));
        $req    = new AlibabaAliqinFcSmsNumSend;

        $code = rand(1000,9999);
        $req->setRecNum('18200571481')  //短信发送给谁-号码
            ->setSmsParam([
                'code'=>$code //对应阿里大于模板里面的${code}
            ])
            ->setSmsFreeSignName('精彩商城') //设置短信签名，必须是已审核的签名
            ->setSmsTemplateCode('SMS_71875243'); //设置短信模板id，必须审核通过

        $resp = $client->execute($req);*/


        //接收前端ajax传过来的参数
        $tel = \Yii::$app->request->post('tel');
        if(!preg_match('/^1[34578]\d{9}$/',$tel)){
            echo '电话号码不正确';
            exit;

        }
        //直接调用Yii配置app来发送短信
        $code = rand(1000,9999);

        $result = \Yii::$app->sms->setNum($tel)->setParam(['code'=>$code])->send();
        if($result){

            //保存当前的验证码 这样好对比发送的验证码和与用户输入的验证
//            \Yii::$app->session->set('tel_'.$tel,$code); // 方法一：电话号码和短信验证码配套保存,配套验证
            \Yii::$app->cache->set('tel_'.$tel,$code,10*60); //方法二：yii2的缓存技术,这样可以设置过期时间

            echo 'success';
        }else{
            echo '发送失败';
        }


    }




}
