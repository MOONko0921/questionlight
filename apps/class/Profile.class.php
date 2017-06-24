<?php
//detabase class 読み込み
require_once('DB.class.php');

//user class 読み込み
require_once('User.class.php');

class Profile extends User {

    private $db;

    public function __construct(){
        $this->db = new DB();
    }

}
