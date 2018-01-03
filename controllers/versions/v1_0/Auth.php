<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Controller\v1_0;

//use Gn\General;

/**
 * Description of Auth
 *
 * @author SERHIO
 */
class Auth {

  //put your code here
  private $aParams = ["login", "password"];
  private $login;
  private $password;
  private $objContainer;
  public $aAnswer = [];
  private $aPost;
  private $objConf;

  public function __construct($objCont) {

    $this->objContainer = $objCont;
    $this->aPost = $this->objContainer->request->getParsedBody();
    $this->objConf = new \Gn\Config($this->objContainer);

    try {
      $this->process();
    } catch (ControllersException $exc) {

      $this->aAnswer = [
        'http' => $exc->getCode(),
        'data' => [
          'error' => $exc->getMessage()
        ],
      ];
    }
  }

  private function process() {
    $this->check_post_params();

    $this->login = trim($this->aPost['login']);
    $this->password = trim($this->aPost['password']);

    $this->user_check();
  }

  private function user_check() {

    $conf_user = $this->objConf->get_user_name();
    $conf_pass = $this->objConf->get_user_pass();

    if ($conf_user == $this->login && $conf_pass == $this->password) {

      $this->generate_token();
    } else {
      $this->wrong_user_pass();
    }
  }

  private function generate_token() {

    $this->objContainer->db->delete('token', '`expire` < "' . date('Y-m-d H:i:s') . '"');

    $nameKey = $this->objContainer->csrf->getTokenNameKey();
    $valueKey = $this->objContainer->csrf->getTokenValueKey();
    $aToken = $this->objContainer->csrf->generateToken();
    $aServerParams = $this->objContainer->request->getServerParams();

    $aData = [
      'token_name' => $aToken[$nameKey],
      'token_value' => $aToken[$valueKey],
      'expire' => date('Y-m-d H:i:s', mktime(date('H') + $this->objConf->get_token_expire(), date('i'), date('s'), date('m'), date('d'), date('Y'))),
      'ip' => $aServerParams['REMOTE_ADDR'],
    ];

    $this->objContainer->db->insert('token', $aData);

    $this->aAnswer = [
      'http' => null,
      'data' => [
        'token-name' => $aToken[$nameKey],
        'token-value' => $aToken[$valueKey]
      ]
    ];
  }

  private function check_post_params() {

    $aDiff = array_diff($this->aParams, array_keys($this->aPost));

    switch (true) {
      case (count($aDiff)):
        throw new ControllersException('Missing params: ' . implode(', ', $aDiff), 400);
        break;
      case (!$this->aPost['login'] || !$this->aPost['password']):
        $this->wrong_user_pass();
        break;
    }
  }

  private function wrong_user_pass() {
    throw new ControllersException('Wrong user name or password.', 400);
  }

}
