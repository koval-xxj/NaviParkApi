<?php

namespace Controller\v1_0;

// use Gn\General;

class Tariff_Get extends AuthControl {

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
    $this->tariff_db_get();
  }

  private function check_post_params() {
    $this->aParams = ['parking_id'];
    $this->aPost = $this->objCont->request->getParsedBody();

    $aCheck = $this->check_post_variables($this->aParams, $this->aPost);

    if (!$aCheck['flag']) {
      throw new ControllersException($aCheck['message'], 400);
    }
  }

  private function tariff_db_get() {
    $rTariff = $this->objCont->db->query("
        SELECT t.tariff_id, t.tariff_title, t.validity_from, t.validity_to, t.price, t.max_time, t.unit_type
        FROM tariff t
        JOIN parking_tariff pt ON t.tariff_id = pt.tariff_id
        JOIN parking p ON pt.parking_id = p.parking_id
        WHERE p.parking_id = '" . $this->aPost['parking_id'] . "'
        GROUP BY t.tariff_id
      ");

    $aData = [];

    while ($row = \mysqli_fetch_assoc($rTariff)) {
      $aData[] = [
        'tariff_id' => $row['tariff_id'],
        'tariff_title' => $row['tariff_title'],
        'validity_from' => $row['validity_from'],
        'validity_to' => $row['validity_to'],
        'price' => $row['price'],
        'max_time' => $row['max_time'],
        'unit_type' => $row['unit_type'],
      ];
    }

    $this->aAnswer = [
      'http' => null,
      'data' => [
        'count' => count($aData),
        'items' => $aData,
      ]
    ];

    if (!$aData) {
      throw new ControllersException("The parking tariffs with parking ID '" . $this->aPost['parking_id'] . "' wasn't found.", 404);
    }
  }

  private function tariff_db_check() {
    // TODO
  }

}
