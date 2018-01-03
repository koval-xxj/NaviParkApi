<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

//Token
$container['csrf'] = function ($c) {
  return new \Gn\Guard;
};
$app->add($container->get('csrf'));

//DB connect
$container['db'] = function ($c) {
  
  $objConf = new Gn\Config($c);
  $aDbParams = $objConf->get_db_config();
  
  $db = \DB\Database_Mysql::create($aDbParams['server'], $aDbParams['user'], $aDbParams['pass'])
        ->setCharset('utf8')
        ->setDatabaseName($aDbParams['db_name']);
      
  return $db;
};
