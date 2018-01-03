<?php

namespace Controller\v1_0;

//use Gn\General;

class Tariff_Del extends AuthControl {

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
    $this->tariff_db_del();
  }

  private function check_post_params() {
    $this->aParams = ['tariff_id'];
    $this->aPost = $this->objCont->request->getParsedBody();

    $aCheck = $this->check_post_variables($this->aParams, $this->aPost);

    if (!$aCheck['flag']) {
      throw new ControllersException($aCheck['message'], 400);
    }
  }

  private function tariff_db_del() {
    $sConditions = " tariff_id = '" . $this->aPost['tariff_id'] . "' ";

    $this->objCont->db->delete('tariff', $sConditions);

    $this->aAnswer = [
      'http' => 200,
      'data' => [
        'success' => 'Tariff was deleted sucessfully'
      ],
    ];
  }

  private function tariff_db_check() {
    // TODO
  }

}
