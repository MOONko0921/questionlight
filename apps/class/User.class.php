<?php

//detabase class 読み込み
require_once('DB.class.php');

//validate class 読み込み
require_once('Validate.class.php');

/* user class
2017.04.18*/
class User extends Validate
{

    public $id;
    public $status;
    private $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = new DB();
    }
    public function setUserId($id)
    {
        $this->id = $id;
    }
    public function login($email, $password, $key)
    {
        $sql = 'SELECT u.*, um.user_status FROM ch_users u, ch_user_meta um
        WHERE email=:email AND u.id=um.user_id';
        $data = array(':email' => $email);

        $recode = $this->db->queryPost($sql, $data);
        $row = $this->db->dbFetch($recode);

        if(empty($row) || !password_verify($password, $row[0]['password'])){
            $this->err_msg[$key] = 'emailまたはパスワードが正しくありません';
        }else{
            $this->id = $row[0]['id'];
            $this->status = $row[0]['user_status'];
            $sql = 'UPDATE ch_users SET login_time=NOW() WHERE id=:id';
            $data = array(':id' => $this->id);
            $this->db->queryPost($sql, $data);
        }
    }
    public function insertUser($name, $email, $password)
    {
        $sql = 'INSERT INTO ch_users SET name=:name, email=:email, password=:password, created=NOW()';
        $data = array(
            ':name' => $name,
            ':email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        );
        $this->db->queryPost($sql, $data);
        $this->login($email, $password, 'email');
        $sql = 'INSERT INTO ch_user_meta SET user_id=:id, user_status=1, created=NOW()';
        $data = array(':id' => $this->id);
        $this->db->queryPost($sql, $data);
    }
    public function logout()
    {
        $_SESSION = array();
        session_destroy();
    }
    public function getUser($id)
    {
        $sql = 'SELECT u.*, um.img FROM ch_users u, ch_user_meta um WHERE u.id=:id AND u.id=um.user_id';
        $data = array(':id' => $id);
        $recode = $this->db->queryPost($sql, $data);
        $row = $this->db->dbFetch($recode);
        return $row[0];
    }
    public function forgetPassword($email, $key)
    {
        $sql = 'SELECT * FROM ch_users WHERE email=:email';
        $data = array(':email' => $email);

        $recode = $this->db->queryPost($sql, $data);
        $row = $this->db->dbFetch($recode);

        if(empty($row)){
            $this->err_msg[$key] = 'このメールアドレスは登録されていません';
        }else{
            $code = sha1(uniqid(mt_rand(), true));

            $sql = 'UPDATE ch_users SET reset_code=:code WHERE id=:id';
            $data = array(
                ':code' => $code,
                ':id' => $row[0]['id']
            );
            $this->db->queryPost($sql, $data);

            mb_language('japanese');
            mb_internal_encoding('UTF-8');

            $to = $row[0]['email'];
            $subject = 'パスワード 変更';
            $body = "下記の URL にアクセスしてパスワードの再設定を行ってください。\n\n\nパスワード 変更 URL：\nURL?code=".$code;
            $from = mb_encode_mimeheader(mb_convert_encoding($_POST['name'], 'JIS', 'UTF-8')).'<'.$_POST['email'].'>';

            mb_send_mail($to, $subject, $body, 'FROM:'.$from);
        }
    }
    public function checkCode($code)
    {
        $sql = 'SELECT * FROM ch_users WHERE resert_code=:code';
        $data = array(':code' => $code);

        $recode = $this->db->queryPost($sql, $data);
        $row = $this->db->dbFetch($recode);
        if(empty($row)){
            $this->err_msg['code'] = 'リセットコードが不正です';
        }else{
            $this->id = $row[0]['id'];
        }
    }
    public function resetPassword($password, $id)
    {
        $sql = 'UPDATE ch_users SET password=:password WHERE id=:id';
        $data = array(
            ':password' => password_hash($password, PASSWORD_DEFAULT),
            ':id' => $id
        );
        $this->db->queryPost($sql, $data);
    }
    // ユーザーの判別
    public function distingUser($id)
    {
        $sql = 'SELECT user_status FROM ch_user_meta WHERE user_id=:id';
        $data = array(':id' => $id);
        $recode = $this->db->queryPost($sql, $data);
        $row = $this->db->dbFetch($recode);
        if($row[0]['user_status'] == '1'){
            $this->status = 'user';
        }else{
            $this->status = 'adviser';
        }
    }

    // プロフィールアップデート
    // 基本情報
    public function updateBasicProfile($name, $email, $id)
    {
        $sql = 'UPDATE ch_users SET name=:name, email=:email WHERE id=:id';
        $data = array(
            ':name' => $name,
            ':email' => $email,
            ':id' => $id
        );
        $this->db->queryPost($sql, $data);
    }
    // プロフィール写真変更
    public function updateProfImg($file, $x, $y, $width, $height, $filePath, $id)
    {
        $inputimg = $file['tmp_name'];
        if($file['error'] === 0){
            $in = @ImageCreateFromJPEG($inputimg);
            $m_width = @ImageSx($in);
            $m_height = @ImageSy($in);
            $fname = 'prof'.date('ymdHis').'.jpg';
            $image_type = exif_imagetype($inputimg);
            //JPGかどうか
            if ($image_type == IMAGETYPE_JPEG){
                $out = ImageCreateTrueColor(300 , 300);
                ImageCopyResampled($out, $in,0,0,$x,$y, 300, 300, $width, $height);
                $filePath .= 'images/'.$fname;
                ImageJPEG($out, $filePath);

                $sql = 'UPDATE ch_user_meta SET img=:fname WHERE user_id=:id';
                $data = array(
                    ':fname' => $fname,
                    ':id' => $id
                );
                $this->db->queryPost($sql, $data);


            }else{
                $this->err_msg['img'] = 'JPG形式以外の画像はアップロードできません(画像が壊れている可能性もあります、確認してください)';
            }
        }else{
            $this->err_msg['img'] = '2MB以上の画像はアップロードできません';

        }
    }
    public function updateAdviser($message, $toeic, $toefl, $ielts, $license, $id)
    {
        $sql = 'UPDATE advisers SET message=:message, toeic_point=:toeic, toefl_point=:toefl, ielts_point=:ielts, license=:license WHERE user_id=:id';
        $data = array(
            ':message' => $message,
            ':toeic' => $toeic,
            ':toefl' => $toefl,
            ':ielts' => $ielts,
            ':license' => $license,
            ':id' => $id
        );
        $this->db->queryPost($sql, $data);
    }
}
