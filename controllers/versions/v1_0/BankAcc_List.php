<?php

namespace Controller\v1_0;

// use Gn\General;

class BankAcc_List extends AuthControl {

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
    $this->bankacc_db_list();
  }

  private function check_post_params() {
    $this->aParams = ['user_id'];
    $this->aPost = $this->objCont->request->getParsedBody();

    $aCheck = $this->check_post_variables($this->aParams, $this->aPost);

    if (!$aCheck['flag']) {
      throw new ControllersException($aCheck['message'], 400);
    }
  }

  private function bankacc_db_list() {

    $sql = 'SELECT * FROM bank_account WHERE user_id = "' . $this->aPost['user_id'] . '"';
    $query = $this->objCont->db->query($sql);
    $aData = [];

    while ($row = \mysqli_fetch_assoc($query)) {
      $aData[] = [
        'account_id' => $row['account_id'],
        'swift_bic' => $row['swift_bic'],
        'institution' => $row['institution'],
        'intermediary' => $row['intermediary'],
        'account_number' => $row['account_number'],
        'bank_zip_city' => $row['bank_zip_city'],
        'bank_address' => $row['bank_address'],
        'name' => $row['name'],
        'zip_city' => $row['zip_city'],
        'address' => $row['address'],
        'iban' => $row['iban'],
      ];
    }

    if (is_array($aData)) {
      $this->aAnswer = [
        'http' => 200,
        'data' => [
          'count' => count($aData),
          'items' => $aData,
        ]
      ];
    }
    if (!is_array($aData)) {
      throw new ControllersException('The bank account with user ID "' . $this->aPost['user_id'] . '" wasn\'t found.', 404);
    }
  }

  private function bankacc_db_check() {
    // TODO
  }

}
