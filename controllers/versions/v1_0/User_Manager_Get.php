<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Controller\v1_0;

/**
 * Description of GetManagers
 *
 * @author SERHIO
 */
class User_Manager_Get extends AuthControl {

  public $aAnswer = [];
  private $aPost = [];
  private $aParams = [];
  private $iOwnerId;

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
    $this->manager_db_list();
  }

  private function check_post_params() {
    $this->aParams = ['owner_id'];
    $this->aPost = $this->objCont->request->getParsedBody();

    $aCheck = $this->check_post_variables($this->aParams, $this->aPost);

    if (!$aCheck['flag']) {
      throw new ControllersException($aCheck['message'], 400);
    }

    $this->iOwnerId = $this->aPost['owner_id'];
  }

  private function manager_db_list() {

    $aData = [];
    $query = $this->objCont->db->query('SELECT * FROM user WHERE owner_id = "' . $this->iOwnerId . '"');

    while ($row = \mysqli_fetch_assoc($query)) {
      $aData[] = [
        'user_id' => $row['user_id'],
        'user_name' => $row['name'],
        'user_email' => $row['email'],
        'user_registered' => $row['registered'],
        'user_status' => $row['status'],
      ];
    }

    if (!$aData) {
      throw new ControllersException("A manager wasn't found", 404);
    }

    $this->aAnswer = [
      'http' => null,
      'data' => [
        'count' => count($aData),
        'items' => $aData,
      ],
    ];
  }

}
