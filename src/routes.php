<?php

/*
 * MAIN ROUTES
 */

// DB
$app->get('/db', function ($request, $response, $args) {
  $a = [
    'a' => 1
  ];
  //Gn\General::obj_dump($this->db->query('SELECT * FROM user'));
  //$newResponse = $response->withStatus(302);
  //$this->logger->debug('test');

  return $response->write($a['b']);
});

// Index
$app->get('/[{name}]', function ($request, $response, $args) {
  // Sample log message
  //$this->logger->info("Slim-Skeleton '/' route");
  // Render index view

  return $this->renderer->render($response, 'index.phtml', $args);
});


/*
 * AUTH (API USER)
 */

// Authorize API-user (by login and password)
$app->post('/{v_numb}/auth', function ($request, $response, $args) {
  $class = 'Controller\\' . $args['v_numb'] . '\\Auth';
  $obj = new $class($this);
  //$this->logger->debug('post vars', $aAnsver);

  return $response->withJson($obj->aAnswer['data'], $obj->aAnswer['http']);
});

/*
 * USER/CUSTOMER
 */

// Add new user/customer
$app->post('/{v_numb}/user/add', function ($request, $response, $args) {
  $class = 'Controller\\' . $args['v_numb'] . '\\User_Add';
  $obj = new $class($this);

  return $response->withJson($obj->aAnswer['data'], $obj->aAnswer['http']);
});

// Get/login user/customer (by login and password)
$app->post('/{v_numb}/user/get', function ($request, $response, $args) {
  $class = 'Controller\\' . $args['v_numb'] . '\\User_Get';
  $obj = new $class($this);

  return $response->withJson($obj->aAnswer['data'], $obj->aAnswer['http']);
});

// Check user (by login)
$app->post('/{v_numb}/user/check', function ($request, $response, $args) {
  $class = 'Controller\\' . $args['v_numb'] . '\\User_Check';
  $obj = new $class($this);

  return $response->withJson($obj->aAnswer['data'], $obj->aAnswer['http']);
});

// Get list of managers (by role)
$app->post('/{v_numb}/user/manager/get', function ($request, $response, $args) {
  $class = 'Controller\\' . $args['v_numb'] . '\\User_Manager_Get';
  $obj = new $class($this);

  return $response->withJson($obj->aAnswer['data'], $obj->aAnswer['http']);
});

// Get list of owners (by role)
$app->post('/{v_numb}/user/owner/get', function ($request, $response, $args) {
  $class = 'Controller\\' . $args['v_numb'] . '\\User_Owner_Get';
  $obj = new $class($this);

  return $response->withJson($obj->aAnswer['data'], $obj->aAnswer['http']);
});


/*
 * BANK ACCOUNT
 */

// Add new bank account
$app->post('/{v_numb}/bank_account/add', function ($request, $response, $args) {
  $class = 'Controller\\' . $args['v_numb'] . '\\BankAcc_Add';
  $obj = new $class($this);

  return $response->withJson($obj->aAnswer['data'], $obj->aAnswer['http']);
});

// Get bank account (by account ID)
$app->post('/{v_numb}/bank_account/get', function ($request, $response, $args) {
  $class = 'Controller\\' . $args['v_numb'] . '\\BankAcc_Get';
  $obj = new $class($this);

  return $response->withJson($obj->aAnswer['data'], $obj->aAnswer['http']);
});

// Get list of bank accounts (by user ID)
$app->post('/{v_numb}/bank_account/list', function ($request, $response, $args) {
  $class = 'Controller\\' . $args['v_numb'] . '\\BankAcc_List';
  $obj = new $class($this);

  return $response->withJson($obj->aAnswer['data'], $obj->aAnswer['http']);
});

// Edit/update bank account (by ID)
$app->post('/{v_numb}/bank_account/edit', function ($request, $response, $args) {
  $class = 'Controller\\' . $args['v_numb'] . '\\BankAcc_Edit';
  $obj = new $class($this);

  return $response->withJson($obj->aAnswer['data'], $obj->aAnswer['http']);
});

// Delete bank account (by ID)
$app->post('/{v_numb}/bank_account/del', function ($request, $response, $args) {
  $class = 'Controller\\' . $args['v_numb'] . '\\BankAcc_Del';
  $obj = new $class($this);

  return $response->withJson($obj->aAnswer['data'], $obj->aAnswer['http']);
});


/*
 * PARKING
 */

// Add new parking
$app->post('/{v_numb}/parking/add', function ($request, $response, $args) {
  $class = 'Controller\\' . $args['v_numb'] . "\\Parking_Add";
  $obj = new $class($this);

  return $response->withJson($obj->aAnswer['data'], $obj->aAnswer['http']);
});

// Get parking list (by user ID)
$app->post('/{v_numb}/parking/get', function ($request, $response, $args) {
  $class = 'Controller\\' . $args['v_numb'] . "\\Parking_Get";
  $obj = new $class($this);

  return $response->withJson($obj->aAnswer['data'], $obj->aAnswer['http']);
});

// Get Parking Loaction (by coordinates X and Y) / QR (by code)
$app->post('/{v_numb}/parking/{type}/get', function ($request, $response, $args) {
  $class = 'Controller\\' . $args['v_numb'] . '\\Parking_' . ucfirst($args['type']) . "_Get";
  if (!class_exists($class)) {
    $aData = [
      'http' => 400,
      'data' => [
        'error' => 'Wrong url param ' . $args['type'],
      ]
    ];
  } else {
    $objGetPark = new $class($this);
    $aData = [
      'http' => $objGetPark->aAnswer['http'],
      'data' => $objGetPark->aAnswer['data'],
    ];
  }

  return $response->withJson($aData['data'], $aData['http']);
});

// Edit/update parking (by ID)
$app->post('/{v_numb}/parking/edit', function ($request, $response, $args) {
  $class = 'Controller\\' . $args['v_numb'] . "\\Parking_Edit";
  $obj = new $class($this);

  return $response->withJson($obj->aAnswer['data'], $obj->aAnswer['http']);
});

// Delete parking (by ID)
$app->post('/{v_numb}/parking/del', function ($request, $response, $args) {
  $class = 'Controller\\' . $args['v_numb'] . "\\Parking_Del";
  $obj = new $class($this);

  return $response->withJson($obj->aAnswer['data'], $obj->aAnswer['http']);
});

/*
 * TARIFF
 */

// Add new tariff
$app->post('/{v_numb}/tariff/add', function ($request, $response, $args) {
  $class = 'Controller\\' . $args['v_numb'] . "\\Tariff_Add";
  $obj = new $class($this);

  return $response->withJson($obj->aAnswer['data'], $obj->aAnswer['http']);
});

// Get tariff (by ID)
$app->post('/{v_numb}/tariff/get', function ($request, $response, $args) {
  $class = 'Controller\\' . $args['v_numb'] . '\\Tariff_Get';
  $obj = new $class($this);

  return $response->withJson($obj->aAnswer['data'], $obj->aAnswer['http']);
});

// Edit/update tariff (by ID)
$app->post('/{v_numb}/tariff/edit', function ($request, $response, $args) {
  $class = 'Controller\\' . $args['v_numb'] . '\\Tariff_Edit';
  $obj = new $class($this);

  return $response->withJson($obj->aAnswer['data'], $obj->aAnswer['http']);
});

// Delete tariff (by ID)
$app->post('/{v_numb}/tariff/del', function ($request, $response, $args) {
  $class = 'Controller\\' . $args['v_numb'] . '\\Tariff_Del';
  $obj = new $class($this);

  return $response->withJson($obj->aAnswer['data'], $obj->aAnswer['http']);
});
