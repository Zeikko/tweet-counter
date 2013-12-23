<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'My Application',
    'preload' => array(
        'log',
    ),
    'language' => 'fi',
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'ext.yii-mail.YiiMailMessage',
    ),
    'modules' => array(
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'gii67',
            'ipFilters' => array('127.0.0.1', '::1'),
        ),
    ),
    // application components
    'components' => array(
        'mail' => array(
            'class' => 'ext.yii-mail.YiiMail',
            'transportType' => 'php',
            'viewPath' => 'application.views.mail',
            'logging' => true,
            'dryRun' => false,
        ),
        'cache' => array(
            'class' => 'CApcCache',
        ),
        'session' => array(
            'timeout' => 60 * 60 * 8,
        ),
        'urlManager' => array(
            'urlFormat' => 'path',
            'rules' => array(
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
            'showScriptName' => false,
        ),
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=dbname',
            'emulatePrepare' => true,
            'username' => 'username',
            'password' => 'password',
            'charset' => 'utf8',
        ),
        'errorHandler' => array(
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error',
                ),
            // uncomment the following to show log messages on web pages
            /*
              array(
              'class'=>'CWebLogRoute',
              ),
             */
            ),
        ),
    ),
    'params' => array(
        'twitter' => array(
            'accessToken' => '38498935-KYJnReqoa2ep05kpgzsFTZCIVsDr8sLas7quGqt51',
            'accessTokenSecret' => '2u2xdyVxwhncZrKsyznd5H1ggfT1ndQC5fYnd22dFxVLk',
            'consumerKey' => '263Y69smSQAO7mOUpZxtmQ',
            'consumerSecret' => 'frbD5oRte4Prq073rULMCEtIZMCe6kzAfuzdT69cxQ',
        )
    ),
);