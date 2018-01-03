<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Controller\v1_0;

/**
 * Description of GetPark_qr
 *
 * @author SERHIO
 */
class Parking_Qr_Get extends AuthControl {

  public $aAnswer = [];
  private $aPost = [];
  private $aParams = [];
  private $sQR;

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
    $this->parking_db_get();
  }

  private function check_post_params() {
    $this->aParams = ['qr'];
    $this->aPost = $this->objCont->request->getParsedBody();

    $aCheck = $this->check_post_variables($this->aParams, $this->aPost);

    if (!$aCheck['flag']) {
      throw new ControllersException($aCheck['message'], 400);
    }

    $this->sQR = trim($this->aPost['qr']);
  }

  private function parking_db_get() {
    $sql = 'SELECT parking_id, park_title, address, places_qty
            FROM parking
            WHERE park_code = "' . $this->sQR . '"';

    $query = $this->objCont->db->query($sql);
    $aData = [];

    while ($rowParking = \mysqli_fetch_assoc($query)) {

      $rTariffs = $this->objCont->db->query("
          SELECT t.tariff_id, t.tariff_title, t.validity_from, t.validity_to, t.price, t.max_time, t.unit_type
          FROM tariff t
          JOIN parking_tariff pt ON t.tariff_id = pt.tariff_id
          JOIN parking p ON pt.parking_id = p.parking_id
          WHERE p.parking_id = '" . $rowParking['parking_id'] . "'
          GROUP BY t.tariff_id
        ");

      while ($rowTariff = \mysqli_fetch_assoc($rTariffs)) {

        $aTariffs[] = [
          'tariff_id' => $rowTariff['tariff_id'],
          'tariff_title' => $rowTariff['tariff_title'],
          'validity_from' => $rowTariff['validity_from'],
          'validity_to' => $rowTariff['validity_to'],
          'price' => $rowTariff['price'],
          'max_time' => $rowTariff['max_time'],
          'unit_type' => $rowTariff['unit_type'],
        ];
      }

      $aParking = [
        "p_id" => $rowParking['parking_id'],
        "p_title" => $rowParking['park_title'],
        "p_address" => $rowParking['address'],
        "p_places_qty" => $rowParking['places_qty'],
      ];

      if ($aTariffs) {
        $aParking['tariffs'] = [
          'count' => count($aTariffs),
          'items' => $aTariffs,
        ];
      }

      $aData[] = $aParking;
    }

    if ($aData) {
      $this->aAnswer = [
        'http' => null,
        'data' => [
          'count' => count($aData),
          'items' => $aData,
        ]
      ];
    } else {
      throw new ControllersException('Parking wasn\'t found', 404);
    }
  }

}
