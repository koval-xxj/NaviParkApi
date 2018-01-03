<?php

namespace Controller\v1_0;

// use Gn\General;

class BankAcc_Get extends AuthControl {

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
    $this->bankacc_db_get();
  }

  private function check_post_params() {
    $this->aParams = ['account_id'];
    $this->aPost = $this->objCont->request->getParsedBody();

    $aCheck = $this->check_post_variables($this->aParams, $this->aPost);

    if (!$aCheck['flag']) {
      throw new ControllersException($aCheck['message'], 400);
    }
  }

  private function bankacc_db_get() {

    $aBankAcc = \mysqli_fetch_assoc($this->objCont->db->query("SELECT * FROM bank_account WHERE account_id = '" . $this->aPost['account_id'] . "'"));

    if (!$aBankAcc) {
      throw new ControllersException('The bank account with ID "' . $this->aPost['account_id'] . '" wasn\'t found.', 404);
    }

    $this->aAnswer = [
      'http' => 200,
      'data' => [
        'user_id' => $aBankAcc['user_id'],
        'swift_bic' => $aBankAcc['swift_bic'],
        'institution' => $aBankAcc['institution'],
        'intermediary' => $aBankAcc['intermediary'],
        'account_number' => $aBankAcc['account_number'],
        'bank_zip_city' => $aBankAcc['bank_zip_city'],
        'bank_address' => $aBankAcc['bank_address'],
        'name' => $aBankAcc['name'],
        'zip_city' => $aBankAcc['zip_city'],
        'address' => $aBankAcc['address'],
        'iban' => $aBankAcc['iban'],
      ],
    ];
  }

  private function bankacc_db_check() {
    // TODO
  }

}
