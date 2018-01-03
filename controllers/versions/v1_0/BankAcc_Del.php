<?php

namespace Controller\v1_0;

//use Gn\General;

class BankAcc_Del extends AuthControl {

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
    $this->bankacc_db_check();
    $this->bankacc_db_del();
  }

  private function check_post_params() {
    $this->aParams = ['account_id'];
    $this->aPost = $this->objCont->request->getParsedBody();

    $aCheck = $this->check_post_variables($this->aParams, $this->aPost);

    if (!$aCheck['flag']) {
      throw new ControllersException($aCheck['message'], 400);
    }
  }

  private function bankacc_db_del() {
    $aData = [
      'account_id' => $this->aPost['account_id'],
    ];

    $this->objCont->db->delete('bank_account', ' account_id = ' . $aData['account_id']);

    $this->aAnswer = [
      'http' => 200,
      'data' => [
        'success' => 'Bank account was added sucessfully'
      ],
    ];
  }

  private function bankacc_db_check() {
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
