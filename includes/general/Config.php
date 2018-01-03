<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Gn;

/**
 * Description of Config
 *
 * @author SERHIO
 */
class Config {
  
  private $objContainer;
  private $sFileName;
  private $sDirConfFile;
  private $aConf;

  public function __construct($objCont) {
    $this->objContainer = $objCont;
    $this->sFileName = 'config.php';
    $this->sDirConfFile = __DIR__ . '/../config/';
    
    $this->generate_conf_ser_file();
  }
  
  public function get_db_config() {
    
    $sHostName = $this->objContainer->get('request')->getUri()->getHost();
    
    $aDbConf = [];
    
    switch (true) {
      case (isset($this->aConf['DB']['production'][$sHostName])):
        $aDbConf = $this->aConf['DB']['production'][$sHostName]; 
        break;
      case (isset($this->aConf['DB']['staging'][$sHostName])):
        $aDbConf = $this->aConf['DB']['staging'][$sHostName];
        break;
      case (isset($this->aConf['DB']['development'][$sHostName])):
        $aDbConf = $this->aConf['DB']['development'][$sHostName];
        break;
      default :
        throw new Config_Exception("Config settings doesn't exist for " . $sHostName);
    }
    
    return $aDbConf;
    
  }
  
  private function generate_conf_ser_file() {
    
    if (!file_exists($this->sDirConfFile . $this->sFileName)) {
      throw new Config_Exception("Config file doesn't exist");
    }
    
    $file_dat = $this->sDirConfFile . $this->sFileName . '.dat';
    
    if (!file_exists($file_dat) || filemtime($file_dat) <= filemtime($this->sDirConfFile . $this->sFileName)) {
        $r = require_once($this->sDirConfFile . $this->sFileName);
        if ($F = fopen($file_dat, "w")) {
          fwrite($F, serialize($r));
          fclose($F);
        }
    } else {
      $r = unserialize(file_get_contents($file_dat));
    }
 
    $this->aConf = $r;
  }
  
  //If you want to get any configuration. A method should start with get_ + conf param
  public function __call($name, $arguments) {
    $sConfParam = str_replace('get_', '', $name);
    return $this->aConf[$sConfParam];
  }
  
}

class Config_Exception extends \Exception {}
