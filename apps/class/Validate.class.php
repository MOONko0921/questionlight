<?php
//detabase class 読み込み
require_once('DB.class.php');

class Validate
{

    const MSG01 = '入力されていません';
    const MSG02 = 'emailの形式で入力してください';
    const MSG03 = 'passwordは4文字以上で設定してください';
    const MSG04 = 'passworfは20文字以内で設定してください';
    const MSG05 = 'passwordは半角英数字で設定してください';
    const MSG06 = 'password再入力と一致しません';
    const MSG07 = 'このemailアドレスはすでに登録されています';
    const MSG08 = 'emailまたはpasswordが正しくありません';
    const MSG09 = 'このメールアドレスはすでに登録されています';
    const MSG10 = 'このユーザーネームはすでに登録されています';

    public $err_msg = array();
    private $db;
    public function __construct()
    {
        $this->db = new DB();
    }
    public function validRequired($str, $key)
    {
        if(empty($str)){
            $this->err_msg[$key] = self::MSG01;
        }
    }
    public function validEmail($str, $key)
    {
        if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $str)){
            $this->err_msg[$key] = self::MSG02;
        }
    }
    public function validPassLenShot($str, $key)
    {
        if(strlen($str) < 4){
            $this->err_msg[$key] = self::MSG03;
        }
    }
    public function validPassLenLarg($str, $key)
    {
        if(strlen($str) > 20){
            $this->err_msg[$key] = self::MSG04;
        }
    }
    public function validPassType($str, $key)
    {
        if(!preg_match("/^[a-zA-Z0-9]+$/", $str)){
            $this->err_msg[$key] = self::MSG05;
        }
    }
    public function validPassRetype($str1, $str2, $key)
    {
        if($str1 !== $str2){
            $this->err_msg[$key] = self::MSG06;
        }
    }
    public function validDuplicateEmail($email, $key)
    {
        $sql = 'SELECT * FROM ch_users WHERE email=:email';
        $data = array('email' => $email);
        $recode = $this->db->queryPost($sql, $data);
        $row = $this->db->dbFetch($recode);
        if(!empty($row)){
            $this->err_msg[$key] = self::MSG09;
        }
    }
    public function validDuplicateName($name, $key)
    {
        $sql = 'SELECT * FROM ch_users WHERE name=:name';
        $data = array('name' => $name);
        $recode = $this->db->queryPost($sql, $data);
        $row = $this->db->dbFetch($recode);
        if(!empty($row)){
            $this->err_msg[$key] = self::MSG10;
        }
    }
}
