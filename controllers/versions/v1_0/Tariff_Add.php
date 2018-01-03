<?php

namespace Controller\v1_0;

//use Gn\General;

class Tariff_Add extends AuthControl {

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
    $this->tariff_db_add();
  }

  private function check_post_params() {
    $this->aParams = ['parking_id', 'tariff_title', 'validity_from', 'validity_to', 'price', 'max_time', 'unit_type'];
    $this->aPost = $this->objCont->request->getParsedBody();

    $aCheck = $this->check_post_variables($this->aParams, $this->aPost);

    if (!$aCheck['flag']) {
      throw new ControllersException($aCheck['message'], 400);
    }
  }

  private function tariff_db_add() {
    $aData = [
      'tariff_title' => $this->aPost['tariff_title'],
      'validity_from' => $this->aPost['validity_from'],
      'validity_to' => $this->aPost['validity_to'],
      'price' => $this->aPost['price'],
      'max_time' => $this->aPost['max_time'],
      'unit_type' => $this->aPost['unit_type'],
    ];

    $aData = [
      'tariff_id' => $this->objCont->db->insert('tariff', $aData),
      'parking_id' => $this->aPost['parking_id'],
    ];

    $this->objCont->db->insert('parking_tariff', $aData);

    $this->aAnswer = [
      'http' => 200,
      'data' => [
        'success' => 'Tariff was added sucessfully'
      ],
    ];
  }

  private function tariff_db_check() {
    // TODO
  }

}
