<?php

namespace Controller\v1_0;

//use Gn\General;
/**
 * Description of AddUser
 *
 * @author SERHIO
 */
class User_Add extends AuthControl {

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
    $this->user_db_check();
    $this->user_db_add();
  }

  private function check_post_params() {
    $this->aParams = ['user_login', 'user_pass', 'user_email', 'user_name', 'user_role', 'user_status', 'car_number'];
    $this->aPost = $this->objCont->request->getParsedBody();

    $aCheck = $this->check_post_variables($this->aParams, $this->aPost);

    if (!$aCheck['flag']) {
      throw new ControllersException($aCheck['message'], 400);
    }
  }

  private function user_db_check() {
    $query = $this->objCont->db->query('SELECT login, email FROM user WHERE login = "' . $this->aPost['user_login'] . '" OR email = "' . $this->aPost['user_email'] . '"');
    $aUser = \mysqli_fetch_assoc($query);

    if ($aUser) {

      switch (true) {
        case ($aUser['login'] == $this->aPost['user_login']):
          throw new ControllersException('The login "' . $aUser['login'] . '" is already exists', 409);
          break;
        case ($aUser['email'] == $this->aPost['user_email']):
          throw new ControllersException('The email "' . $aUser['email'] . '" is already exists', 409);
          break;
      }
    }
  }

  private function user_db_add() {

    $aData = [
      'name' => $this->aPost['user_name'],
      'login' => $this->aPost['user_login'],
      'pass' => md5($this->aPost['user_pass']),
      'email' => $this->aPost['user_email'],
      'registered' => date('Y-m-d H:i:s'),
      'status' => $this->aPost['user_status'],
      'role' => $this->aPost['user_role'],
      'owner_id' => $this->aPost['owner_id'],
    ];

    $aData = [
      'user_id' => $this->objCont->db->insert('user', $aData),
      'number' => $this->aPost['car_number'],
      'cartype_id' => 1,
    ];

    $this->objCont->db->insert('car', $aData);

    $this->aAnswer = [
      'http' => 200,
      'data' => [
        'success' => 'User was added sucessfully'
      ],
    ];
  }

}
