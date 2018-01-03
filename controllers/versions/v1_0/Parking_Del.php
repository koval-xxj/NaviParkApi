<?php

namespace Controller\v1_0;

//use Gn\General;

class Parking_Del extends AuthControl {

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
    $this->parking_db_check();
    $this->parking_db_del();
  }

  private function check_post_params() {
    $this->aParams = ['parking_id'];
    $this->aPost = $this->objCont->request->getParsedBody();

    $aCheck = $this->check_post_variables($this->aParams, $this->aPost);

    if (!$aCheck['flag']) {
      throw new ControllersException($aCheck['message'], 400);
    }
  }

  private function parking_db_del() {
    $sConditions = " parking_id = '" . $this->aPost['parking_id'] . "' ";

    $this->objCont->db->delete('parking', $sConditions);

    $this->aAnswer = [
      'http' => 200,
      'data' => [
        'success' => 'Parking was deleted sucessfully'
      ],
    ];
  }

  private function parking_db_check() {
    // TODO
  }

}
