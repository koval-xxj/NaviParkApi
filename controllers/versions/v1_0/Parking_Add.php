<?php

namespace Controller\v1_0;

//use Gn\General;

class Parking_Add extends AuthControl {

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
    $this->parking_db_add();
  }

  private function check_post_params() {
    $this->aParams = ['park_title', 'address', 'places_qty', 'center_x', 'center_y', 'park_code', 'user_id', 'parent_id'];
    $this->aPost = $this->objCont->request->getParsedBody();

    $aCheck = $this->check_post_variables($this->aParams, $this->aPost);

    if (!$aCheck['flag']) {
      throw new ControllersException($aCheck['message'], 400);
    }
  }

  private function parking_db_add() {

    $fRadius = 0.005;

    $aData = [
      'park_title' => $this->aPost['park_title'],
      'address' => $this->aPost['address'],
      'places_qty' => $this->aPost['places_qty'],
      'rect_min_x' => empty($this->aPost['rect_min_x']) ? ($this->aPost['center_x'] + $fRadius) : $this->aPost['rect_min_x'],
      'rect_min_y' => empty($this->aPost['rect_min_y']) ? ($this->aPost['center_y'] + $fRadius) : $this->aPost['rect_min_y'],
      'rect_max_x' => empty($this->aPost['rect_max_x']) ? ($this->aPost['center_x'] - $fRadius) : $this->aPost['rect_max_x'],
      'rect_max_y' => empty($this->aPost['rect_max_y']) ? ($this->aPost['center_y'] - $fRadius) : $this->aPost['rect_max_y'],
      'center_x' => $this->aPost['center_x'],
      'center_y' => $this->aPost['center_y'],
      'park_code' => $this->aPost['park_code'],
      'user_id' => $this->aPost['user_id'],
      'parent_id' => $this->aPost['parent_id'],
    ];

    $this->objCont->db->insert('parking', $aData);

    $this->aAnswer = [
      'http' => 200,
      'data' => [
        'success' => 'Parking was added sucessfully'
      ],
    ];
  }

  private function parking_db_check() {
    // TODO
  }

}
