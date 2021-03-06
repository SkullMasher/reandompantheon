<?php
/**
* Handles login attepts
*/
class AuthController
{
  private $router;
  private $flash;
  private $adminName;
  private $adminPassword;

  function __construct($container)
  {
    $this->router = $container['router'];
    $this->flash = $container['flash'];
    $this->adminName = $container['settings']['admin']['username'];
    $this->adminPassword = $container['settings']['admin']['password'];
  }

  public function postLogin($request, $response)
  {
    $postAdminName = $request->getParam('adminName');
    $postAdminPassword = $request->getParam('adminPassword');
    $shrugFace = '<p class="tac error">¯\_ツ_/¯</p>';
    $errorMessage = '<p class="error">Wrong username or password. Try again.</p>';
 
    if ($this->adminName === $postAdminName && $this->adminPassword === $postAdminPassword) {
      $_SESSION['user'] = $postAdminName;
      return $response->withRedirect($this->router->pathfor('admin'));
    } else {
      $this->flash->addMessage('loginerror', $shrugFace);
      $this->flash->addMessage('loginerror', $errorMessage);
      return $response->withRedirect($this->router->pathfor('login'));
    }
    // $parsedBody = $request->getParsedBody();
    // return $response->getBody()->write(var_dump($this->adminName));
  }

  public function getLogout($request, $response) {
    unset($_SESSION['user']);
    return $response->withRedirect($this->router->pathfor('home'));
  }
}
