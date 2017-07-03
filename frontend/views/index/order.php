<?php
/**
 * @var $this \yii\web\View
 */
use yii\helpers\Html;
$this->registerCssFile('@web/style/fillin.css');

?>
<!-- 页面头部 start -->
<div class="header w990 bc mt15">
    <div class="logo w990">
        <h2 class="fl"><?=\yii\helpers\Html::a(Html::img('@web/images/logo.png'),['index/index'])?></h2>
        <div class="flow fr flow2">
            <ul>
                <li>1.我的购物车</li>
                <li class="cur">2.填写核对订单信息</li>
                <li>3.成功提交订单</li>
            </ul>
        </div>
    </div>
</div>
<!-- 页面头部 end -->
<div style="clear:both;"></div>
<!-- 主体部分 start -->
<div class="fillin w990 bc mt15">
<!--    <form >-->
    <div class="fillin_hd">
        <h2>填写并核对订单信息</h2>
    </div>

    <div class="fillin_bd">
        <!-- 收货人信息  start-->

        <div class="address">
            <h3>收货人信息</h3>
            <div class="address_info">
                <?php foreach ($addresses as $address):?>
                <p>
                    <input type="radio" value="<?=$address->id?>" name="address_id" <?=$address->select==1? 'checked': ''?>/><?=$address->name.' '.$address->province->name.' '.$address->city->name.' '.$address->area->name.' '.$address->address?> </p>
                <?php endforeach;?>
            </div>


        </div>
        <!-- 收货人信息  end-->

        <!-- 配送方式 start -->
        <div class="delivery">
            <h3>送货方式 </h3>


            <div class="delivery_select">
                <table>
                    <thead>
                    <tr>
                        <th class="col1">送货方式</th>
                        <th class="col2">运费</th>
                        <th class="col3">运费标准</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach (\frontend\models\Order::$deliveries as $k1=>$d):?>
                    <tr class="cur">
                        <td>
                            <input type="radio" name="delivery_id"  value="<?=$k1?>" /><?=$d['delivery_name']?>

                        </td>
                        <td class="price"><?=$d['delivery_price']?></td>
                        <td><?=$d['detail']?></td>
                    </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>

            </div>
        </div>
        <!-- 配送方式 end -->

        <!-- 支付方式  start-->
        <div class="pay">
            <h3>支付方式 </h3>


            <div class="pay_select">
                <table>
                    <?php foreach (\frontend\models\Order::$payments as $k2=>$payment):?>
                    <tr class="cur">
                        <td class="col1"><input type="radio" name="payment_id" value="<?=$k2?>"/><?=$payment['payment_name']?></td>
                        <td class="col2"><?=$payment['detail']?></td>
                    </tr>
                    <?php endforeach;?>
                </table>

            </div>
        </div>
        <!-- 支付方式  end-->



        <!-- 商品清单 start -->
        <div class="goods">
            <h3>商品清单</h3>
            <table>
                <thead>
                <tr>
                    <th class="col1">商品</th>
                    <th class="col3">价格</th>
                    <th class="col4">数量</th>
                    <th class="col5">小计</th>
                </tr>
                </thead>
                <tbody>
                <?php $count=0 ;$total=0?>
                <?php foreach ($models as $model):?>
                <tr>
                    <td class="col1"><a href=""><?=\yii\helpers\Html::img('http://admin.yii2shop.com'.$model['logo'])?></a>  <strong><?=Html::a($model['name'])?></strong></td>
                    <td class="col3"><?=$model['shop_price']?></td>
                    <td class="col4"><?=$model['amount']?></td>
                    <td class="col5"><span><?=$model['shop_price']*$model['amount']?></span></td>
                </tr>
                <?php
                    $count += $model['amount'];
                    $total += $model['shop_price']*$model['amount'];
                endforeach;
                ?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5">
                        <ul>
                            <li>
                                <span><?=$count?> 件商品，总商品金额：</span>
                                <em id="total"><?=$total?></em>
                            </li>

                            <li>
                                <span>运费：</span>
                                <em id="yf"></em>
                            </li>
                            <li>
                                <span>应付总额：</span>
                                <em id="ze1"></em>
                            </li>
                        </ul>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        <!-- 商品清单 end -->

    </div>

    <div class="fillin_ft">
        <a href="javascript:;" id="send"><span >提交订单</span></a>
        <p>应付总额：<strong id="ze2"></strong></p>

    </div>
<!--    </form>-->
</div>>

<!-- 主体部分 end -->

<?php
/**
 * @var $this \yii\web\View
 */
$url = \yii\helpers\Url::to(['index/order-save']);
$url2 = \yii\helpers\Url::to(['index/pay']);
$token =Yii::$app->request->csrfToken;
$this->registerJs(new \yii\web\JsExpression(
<<<JS
$('[name="delivery_id"]').click(function() {
  var yunfei = $(this).closest('tr').find(".price").text();
  zonge = parseInt($("#total").text())+parseInt(yunfei);
  
  $("#yf").text('￥'+yunfei);
  $("#ze1").text('￥'+zonge);
  $("#ze2").text('￥'+zonge+'元');
  
})
//提交订单 收货人信息，送货方式，支付方式，购物车数据
$("#send").click(function() {
  var address_id = $('[name="address_id"]:checked').val();
  var delivery_id = $('[name="delivery_id"]:checked').val();
  var payment_id = $('[name="payment_id"]:checked').val();
  var total = zonge;
  //提交订单到后台处理
  $.post("$url",{address_id:address_id,delivery_id:delivery_id,payment_id:payment_id,total:total,"_csrf-frontend":"$token"},function(response) {
        if(response){
            location.href = "$url2"+"?id="+response;
        }
  });  
})
JS
))
?>

