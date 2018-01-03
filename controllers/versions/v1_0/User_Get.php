<?php

namespace Controller\v1_0;

class User_Get extends AuthControl {

  public $aAnswer = [];
  private $aPost = [];
  private $aParams = [];

  public function __construct($objCont) {

    if (!parent::__construct($objCont)) {
      $this->aAnswer = $this->aParAnswer;

      return;
    }

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
    $this->user_db_get();
  }

  private function check_post_params() {
    $this->aParams = ['user_login', 'user_pass'];
    $this->aPost = $this->objCont->request->getParsedBody();

    $aCheck = $this->check_post_variables($this->aParams, $this->aPost);

    if (!$aCheck['flag']) {
      throw new ControllersException($aCheck['message'], 400);
    }
  }

  private function user_db_get() {
    $login = trim($this->aPost['user_login']);
    $pass = trim($this->aPost['user_pass']);

    $aUser = \mysqli_fetch_assoc($this->objCont->db->query('SELECT * FROM user WHERE login = "' . $login . '" AND pass = "' . md5($pass) . '"'));

    if (!$aUser) {
      throw new ControllersException('The user "' . $this->aPost['user_login'] . '" wasn\'t found.', 404);
    }

    $aCar = \mysqli_fetch_assoc($this->objCont->db->query("SELECT number FROM car WHERE user_id = '" . $aUser['user_id'] . "'"));

    $this->aAnswer = [
      'http' => 200,
      'data' => [
        "user_id" => $aUser['user_id'],
        "user_login" => $aUser['login'],
        "user_name" => $aUser['name'],
        "user_email" => $aUser['email'],
        "user_registered" => $aUser['registered'],
        "user_status" => $aUser['status'],
        "user_role" => $aUser['role'],
        "balance" => $aUser['balance'],
        "owner_id" => $aUser['owner_id'],
        "wp_id" => $aUser['wp_id'],
        "car_number" => $aCar['number'],
      ],
    ];
  }

}
