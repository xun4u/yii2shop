
<div class="main w1210 bc mt10">
    <div class="crumb w1210">
        <h2><strong>我的XX </strong><span>> 我的订单</span></h2>
    </div>

    <!-- 左侧导航菜单 start -->
    <div class="menu fl">
        <h3>我的XX</h3>
        <div class="menu_wrap">
            <dl>
                <dt>订单中心 <b></b></dt>
                <dd><b>.</b><a href="">我的订单</a></dd>
                <dd><b>.</b><a href="">我的关注</a></dd>
                <dd><b>.</b><a href="">浏览历史</a></dd>
                <dd><b>.</b><a href="">我的团购</a></dd>
            </dl>

            <dl>
                <dt>账户中心 <b></b></dt>
                <dd class="cur"><b>.</b><a href="">账户信息</a></dd>
                <dd><b>.</b><a href="">账户余额</a></dd>
                <dd><b>.</b><a href="">消费记录</a></dd>
                <dd><b>.</b><a href="">我的积分</a></dd>
                <dd><b>.</b><a href="">收货地址</a></dd>
            </dl>

            <dl>
                <dt>订单中心 <b></b></dt>
                <dd><b>.</b><a href="">返修/退换货</a></dd>
                <dd><b>.</b><a href="">取消订单记录</a></dd>
                <dd><b>.</b><a href="">我的投诉</a></dd>
            </dl>
        </div>
    </div>
    <!-- 左侧导航菜单 end -->

    <!-- 右侧内容区域 start -->
    <div class="content fl ml10">
        <div class="address_hd">


            <h3>收货地址薄</h3>

            <?php foreach ($infos as $k=>$info):?>
                <dl>
                    <dt><?=($k+1).'. '. $info->name.' '.$info->province->name.' '.$info->city->name.' '.$info->area->name.' '.$info->address.' '.$info->tel?> </dt>
                    <dd>
                        <?=\yii\helpers\Html::a('修改',['address/edit','id'=>$info->id])?>
                        <?=\yii\helpers\Html::a('删除',['address/del','id'=>$info->id])?>
                        <?=$info->select==0? \yii\helpers\Html::a('设为默认地址',['address/select','id'=>$info->id]): '默认地址';?>
                    </dd>
                </dl>
            <?php endforeach;?>



        </div>

        <div class="address_bd mt10">
            <h4>新增收货地址</h4>
            <?php
            $form = \yii\widgets\ActiveForm::begin([
                'fieldConfig'=>[
                    'options'=>[
                        'tag'=>'li'
                    ],
                    'errorOptions'=>[
                        'tag'=>'p'
                    ]
                ]
            ]);
            echo '<ul>';
            echo $form->field($model,'name')->textInput(['class'=>'txt']);

            $url=\yii\helpers\Url::toRoute(['get-region']);
            echo $form->field($model, 'p_id')->widget(\chenkby\region\Region::className(),[
                'model'=>$model,
                'url'=>$url,
                'province'=>[
                    'attribute'=>'p_id',
                    'items'=>\frontend\models\Locations::getRegion(),
                    'options'=>['class'=>'form-control form-control-inline','prompt'=>'选择省份']
                ],
                'city'=>[
                    'attribute'=>'c_id',
                    'items'=>\frontend\models\Locations::getRegion($model['p_id']),
                    'options'=>['class'=>'form-control form-control-inline','prompt'=>'选择城市']
                ],
                'district'=>[
                    'attribute'=>'a_id',
                    'items'=>\frontend\models\Locations::getRegion($model['c_id']),
                    'options'=>['class'=>'form-control form-control-inline','prompt'=>'选择县/区']
                ]
            ]);

            echo $form->field($model,'address')->textInput(['class'=>'txt address']);
            echo $form->field($model,'tel')->textInput(['class'=>'txt']);
            echo $form->field($model,'select')->checkbox();

            echo '<li>
                    <label for="">&nbsp;</label>
                    <input type="submit" name="" class="btn" value="保存" />
                </li>';
            echo '<ul>';
            \yii\widgets\ActiveForm::end();
            ?>
            <!--<form action="" name="address_form">
                <ul>
                    <li>
                        <label for=""><span>*</span>收 货 人：</label>
                        <input type="text" name="" class="txt" />
                    </li>
                    <li>
                        <label for=""><span>*</span>所在地区：</label>
                        <select name="" id="">
                            <option value="">请选择</option>
                            <option value="">北京</option>
                            <option value="">上海</option>
                            <option value="">天津</option>
                            <option value="">重庆</option>
                            <option value="">武汉</option>
                        </select>

                        <select name="" id="">
                            <option value="">请选择</option>
                            <option value="">朝阳区</option>
                            <option value="">东城区</option>
                            <option value="">西城区</option>
                            <option value="">海淀区</option>
                            <option value="">昌平区</option>
                        </select>

                        <select name="" id="">
                            <option value="">请选择</option>
                            <option value="">西二旗</option>
                            <option value="">西三旗</option>
                            <option value="">三环以内</option>
                        </select>
                    </li>
                    <li>
                        <label for=""><span>*</span>详细地址：</label>
                        <input type="text" name="" class="txt address"  />
                    </li>
                    <li>
                        <label for=""><span>*</span>手机号码：</label>
                        <input type="text" name="" class="txt" />
                    </li>
                    <li>
                        <label for="">&nbsp;</label>
                        <input type="checkbox" name="" class="check" />设为默认地址
                    </li>
                    <li>
                        <label for="">&nbsp;</label>
                        <input type="submit" name="" class="btn" value="保存" />
                    </li>
                </ul>
            </form>-->
        </div>

    </div>
    <!-- 右侧内容区域 end -->
</div>
