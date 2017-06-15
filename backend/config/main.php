<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'language'=>'zh-CN',//语言设置成中文
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'loginUrl'=>['user/login'],
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        //地址美化
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        //七牛云配置
        'qiniu'=>[
            'class'=>\backend\components\Qiniu::className(),
            'up_host'=>'http://up.qiniu.com',
            'accessKey'=>'CDD4INkEdp2BT0PM4PB6U2dEEO1xVqrQX3RqdOah',
            'secretKey'=>'Egbb9gPGRoL2kyV4Q6m8hZTVTL3P6DfSz9fDVKpQ',
            'bucket'=>'yii2shop',
            'domain'=>'http://or9tmzkdz.bkt.clouddn.com',
        ],
        //上传


    ],
    'params' => $params,
];
