<?php
/**
* Handles login attepts
*/
class AdminController
{
  private $router;
  private $flash;
  private $logger;
  private $view;

  function __construct($container)
  {
    $this->router = $container['router'];
    $this->flash = $container['flash'];
    $this->logger = $container['logger'];
    $this->view = $container['view'];
  }

  public function getPage($request, $response)
  {
    $this->logger->info(" '/admin' route");
    $allBands = Band::all()->toArray();
    return $this->view->render($response, 'admin.twig', [ 'bands' => $allBands]);
  }

  public function postData($request, $response) {
    $method = $request->getParam('_method');
    switch ($method) {
      case 'post':
        return $this->addBand($request, $response);
      case 'delete':
        return $this->deleteBand($request, $response);
      default:
        die('You are in the default of the method case !');
        break;
    }
  }

  private function addBand($request, $response)
  {
    $bandName = $request->getParam('bandName');
    $bandLink = $request->getParam('bandLink');

    $successMessage = '<p class="success">(☞ﾟ∀ﾟ)☞ ' . $bandName .' as been added !</p>';
    $errorMessage = '<p class="error">¯\_ツ_/¯ You failed at adding a band !</p>';

    if (strlen($bandName) > 0 && strlen($bandName) < 255 && strlen($bandLink) > 0 && strlen($bandLink) < 255) {
      // Insert to bands table
      $band = new Band;
      $band->name = $bandName;
      $band->link = $bandLink;
      $band->save();

      $this->flash->addMessage('addBandSuccess', $successMessage);
      return $response->withRedirect($this->router->pathfor('admin'));
    }

    $this->flash->addMessage('addBandError', $errorMessage);
    return $response->withRedirect($this->router->pathfor('admin'));
  }

  private function deleteBand($request, $response)
  {
    $bandId = $request->getParam('bandID');
    $successMessage = '<p class="success">(☞ﾟ∀ﾟ)☞ ' . $bandName .' as been <span class="error">DELETED</span> !</p>';
    $errorMessage = '<p class="error">¯\_ツ_/¯ Could not delete that band !</p>';
    
  }
}
