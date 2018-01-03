<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Controller\v1_0;
//use Gn\General;

/**
 * Description of AuthControl
 *
 * @author SERHIO
 */
abstract class AuthControl {
  
  protected $objCont;
  private $token_name;
  private $token_value;
  protected $aParAnswer = [];
  private $aParams = [];

  protected function __construct($objCont) {
    $this->objCont = $objCont;
    $this->aParams = [
      'token_name' => 'token-name',
      'token_value' => 'token-value'
    ];
    
    $bFlag = true;
    
    try {
      $this->check();
    } catch (ControllersException $exc) {
      $this->aParAnswer = [
        'http' => $exc->getCode(),
        'data' => [
          'error' => $exc->getMessage()
        ]
      ];
      
      $bFlag = false;
    }
    
    return $bFlag;
  }
  
  private function check() {
    $this->check_headers();
    $this->check_tokens();
  }

  private function check_headers() {
    
    $aMissing = [];
    
    foreach ($this->aParams as $p_key => $p_val) {
      $this->$p_key = trim($this->objCont->request->getHeaderLine($p_key));
      if (!$this->$p_key) {
        $aMissing[] = $p_val;
      }
    }
    
    if (count($aMissing)) {
      throw new ControllersException('Missing token params: ' . implode(', ', $aMissing), 400);
    }
    
  }
  
  private function check_tokens() {
    $this->objCont->db->delete('token', '`expire` < "' . date('Y-m-d H:i:s') . '"');
    $query = $this->objCont->db->query('SELECT * FROM token WHERE token_name = "' . $this->token_name . '" AND token_value = "' . $this->token_value . '"');
    
    if (!\mysqli_num_rows($query)) {
      throw new ControllersException('Authentication is required', 401);
    }
  }
  
  protected function check_post_variables($aParams, $aPost) {
    $aResult = [
      'flag' => true
    ];
    
    $aMissing = [];
    
    foreach ($aParams as $sParam) {
      $param = trim($aPost[$sParam]);
      if (!$param) {
        $aMissing[] = $sParam;
      }
    }
    
    if ($aMissing) {
      $aResult = [
        'flag' => false,
        'message' => 'Missing params: ' . implode(', ', $aMissing),
      ];
    }
    
    return $aResult;
  }
  
}