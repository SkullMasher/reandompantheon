<?php
// DIC configuration (Dependency Injection Container)

$container = $app->getContainer();

// Register twig templating engine as view renderer
$container['view'] = function ($container) {
  $settings = $container->get('settings')['view'];
  $view = new \Slim\Views\Twig($settings['template_path'], [
      'cache' => false
  ]);
  
  // Instantiate and add Slim specific extension & flash message support
  $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
  $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));
  $view->addExtension(new Knlv\Slim\Views\TwigMessages(
    new Slim\Flash\Messages()
  ));
  return $view;
};

// Register database ORM Eloquent
// $container['db'] = function ($container) {
//   $capsule = new \Illuminate\Database\Capsule\Manager;
//   $capsule->addConnection($container['settings']['db']);

//   $capsule->setAsGlobal();
//   $capsule->bootEloquent();

//   return $capsule;
// };

$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

// monolog
$container['logger'] = function ($c) {
  $settings = $c->get('settings')['logger'];
  $logger = new Monolog\Logger($settings['name']);
  $logger->pushProcessor(new Monolog\Processor\UidProcessor());
  $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// Flash message
$container['flash'] = function () {
    return new \Slim\Flash\Messages();
};

// CSRF protection
$container['csrf'] = function ($c) {
    return new \Slim\Csrf\Guard;
};
