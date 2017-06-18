<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        //配置好rbac的认证实现类
        'authManager'=>[
            'class'=>\yii\rbac\DbManager::className(),
        ]
    ],
];
