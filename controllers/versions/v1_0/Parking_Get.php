<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Controller\v1_0;

//use Gn\General;

/**
 * Description of GetPark_location
 *
 * @author SERHIO
 */
class Parking_Get extends AuthControl {

  public $aAnswer = [];
  private $aPost = [];
  private $aParams = [];
  private $iUserId;

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
    $this->parking_db_list();
  }

  private function check_post_params() {
    $this->aParams = ['user_id'];
    $this->aPost = $this->objCont->request->getParsedBody();

    $aCheck = $this->check_post_variables($this->aParams, $this->aPost);

    if (!$aCheck['flag']) {
      throw new ControllersException($aCheck['message'], 400);
    }

    $this->iUserId = $this->aPost['user_id'];
  }

  private function parking_db_list() {
    $sql = 'SELECT parking_id, park_title, address, places_qty, park_code
            FROM parking 
            WHERE user_id = "' . $this->iUserId . '"';

    $query = $this->objCont->db->query($sql);
    $aData = [];

    while ($row = \mysqli_fetch_assoc($query)) {
      $aData[] = [
        'parking_id' => $row['parking_id'],
        'park_title' => $row['park_title'],
        'address' => $row['address'],
        'places_qty' => $row['places_qty'],
        "park_code" => $row['park_code'],
      ];
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
      throw new ControllersException("No one parking wasn't found", 404);
    }
  }

}
