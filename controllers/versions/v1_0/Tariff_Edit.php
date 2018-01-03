<?php

namespace Controller\v1_0;

// use Gn\General;

class Tariff_Edit extends AuthControl {

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
    $this->tariff_db_check();
    $this->tariff_db_edit();
  }

  private function check_post_params() {
    $this->aParams = ['tariff_id', 'tariff_title', 'validity_from', 'validity_to', 'price', 'max_time', 'unit_type'];
    $this->aPost = $this->objCont->request->getParsedBody();

    $aCheck = $this->check_post_variables($this->aParams, $this->aPost);

    if (!$aCheck['flag']) {
      throw new ControllersException($aCheck['message'], 400);
    }
  }

  private function tariff_db_edit() {
    $aData = [
      'tariff_title' => $this->aPost['tariff_title'],
      'validity_from' => $this->aPost['validity_from'],
      'validity_to' => $this->aPost['validity_to'],
      'price' => $this->aPost['price'],
      'max_time' => $this->aPost['max_time'],
      'unit_type' => $this->aPost['unit_type'],
    ];
    $sConditions = " tariff_id = '" . $this->aPost['tariff_id'] . "' ";

    $this->objCont->db->update('tariff', $aData, $sConditions);

    $this->aAnswer = [
      'http' => 200,
      'data' => [
        'success' => 'Tariff was updated sucessfully',
      ],
    ];
  }

  private function tariff_db_check() {
    // TODO
    /*
      $query = $this->objCont->db->query('SELECT login, email FROM user WHERE login = "' . $this->aPost['user_login'] . '" OR email = "' . $this->aPost['user_email'] . '"');
      $aBankAcc = \mysqli_fetch_assoc($query);

      if ($aUser) {

      switch (true) {
      case ($aUser['login'] == $this->aPost['user_login']):
      throw new ControllersException('The login "' . $aUser['login'] . '" is already exists', 400);
      break;
      case ($aUser['email'] == $this->aPost['user_email']):
      throw new ControllersException('The email "' . $aUser['email'] . '" is already exists', 400);
      break;
      }
      }
     */
  }

}
