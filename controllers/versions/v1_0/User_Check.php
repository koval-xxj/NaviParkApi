<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Controller\v1_0;

/**
 * Description of CheckLogin
 *
 * @author SERHIO
 */
class User_Check extends AuthControl {
  
  public $aAnswer = [];
  private $aPost = [];
  private $aParams = [];
  private $sLogin;

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
    $this->user_db_check();
  }
  
  private function check_post_params() {
    $this->aParams = ['user_login'];
    $this->aPost = $this->objCont->request->getParsedBody();
    
    $aCheck = $this->check_post_variables($this->aParams, $this->aPost);
    
    if (!$aCheck['flag']) {
      throw new ControllersException($aCheck['message'], 400);
    }
    
    $this->sLogin = trim($this->aPost['user_login']);
  }
  
  private function user_db_check() {
    
    $sCheckLogin = $this->objCont->db->get_single_value('SELECT login FROM user WHERE login = "' . $this->sLogin . '"');
    
    if ($sCheckLogin) {
      throw new ControllersException("Login '" . $sCheckLogin . "' is not available", 302);
    }
    
    $this->aAnswer = [
      'http' => null,
      'data' => [
        'success' => 'Login is available',
      ],
    ];
    
  }
  
}
