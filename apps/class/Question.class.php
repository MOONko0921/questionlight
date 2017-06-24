<?php
//detabase class 読み込み
require_once('DB.class.php');

//validate class 読み込み
require_once('Validate.class.php');


/* question class
2017.04.18*/
class Question extends Validate
{

    public $question_id;
    private $db;

    public function __construct()
    {
        $this->db = new DB();
    }
    public function getNewQestion()
    {
        $sql = 'SELECT q.*, u.name FROM ch_questions q, ch_users u WHERE status=1 AND u.id=q.user_id';
        $recode = $this->db->queryPost($sql, array());
        $row = $this->db->dbFetch($recode);
        return $row;
    }
    public function getMyQuestion($id)
    {
        $sql = 'SELECT * FROM ch_questions WHERE user_id=:id';
        $data = array(':id' => $id);
        $recode = $this->db->queryPost($sql, $data);
        $row = $this->db->dbFetch($recode);
        return $row;
    }
    //質問の詳細を取得する
    public function getQuestion($id)
    {
        $sql = 'SELECT q.*, u.name, um.img FROM ch_questions q, ch_users u, ch_user_meta um WHERE q.id=:id AND u.id=q.user_id AND u.id=um.user_id';
        $data = array(':id' => $id);
        $recode = $this->db->queryPost($sql, $data);
        $row = $this->db->dbFetch($recode);
        $this->question_id = $row[0]['id'];
        return $row[0];
    }

    //質問に対する回答を取得する
    public function getAnswer($id)
    {
        $sql = 'SELECT a.*, u.name, u.email, u.id AS user_id, um.img, ad.school, ad.period FROM ch_answers a, ch_users u, ch_user_meta um, ch_advisers ad WHERE a.question_id=:id AND u.id=um.user_id AND u.id=a.user_id AND ad.user_id=u.id ORDER BY created DESC';
        $data = array(':id' => $id);
        $recode = $this->db->queryPost($sql, $data);
        return $row = $this->db->dbFetch($recode);
    }

    //過去の質問の一覧を取得する
    //キーワード、フリーワードから検索が可能
    public function getQuestionsList($get_start, $keyword, $freeword)
    {
        $data = array();
        if(!empty($keyword)){
            $sql_serch = 'AND keyword=:keyword ';
            $data[':keyword'] = $keyword;
        }
        if(!empty($freeword)){
            @$sql_serch .= 'AND (title LIKE :freeword OR content LIKE :freeword) ';
            $data['freeword'] = '%'.$freeword.'%';
        }
        $sql = 'SELECT u.name, a.content AS answer, q.* FROM ch_questions q, ch_users u, ch_answers a WHERE status=3  '.@$sql_serch.'AND u.id=a.user_id AND q.best_ans_id=a.id LIMIT '.$get_start.', 5';
        $recode = $this->db->queryPost($sql, $data);
        return $row = $this->db->dbFetch($recode);
    }

    //新しく質問を依頼する
    //ログインユーザーのみ
    public function questionRequest($user_id, $keyword, $title, $content)
    {
        $sql = 'INSERT INTO ch_questions SET user_id=:id, status=1, title=:title, content=:content, keyword=:keyword, created=NOW()';
        $data = array(
            ':id' => $user_id,
            ':title' => $title,
            ':content' => $content,
            ':keyword' => $keyword
        );
        $this->db->queryPost($sql, $data);

        // アドバイザーにメールを送信する
        $sql = 'SELECT id FROM ch_questions WHERE title=:title AND content=:content';
        $data = array(
            ':title' => $title,
            ':content' => $content
        );
        $recode = $this->db->queryPost($sql, $data);
        $row = $this->db->dbFetch($recode);
        $question_id = $row[0]['id'];

        $sql = 'SELECT u.*, um.created FROM ch_users u, ch_user_meta um WHERE um.user_status=2';
        $recode = $this->db->queryPost($sql, array());
        $advs = $this->db->dbFetch($recode);
        foreach ($advs as $value) {
            mb_language('japanese');
            mb_internal_encoding('UTF-8');

            $to = $value['email'];
            $subject = '○○ユーザー様より留学に関する質問【'.$keyword.'】';
            $body = "○○ユーザー様より留学に関する質問が届いております\nお答え頂けるかたよろしくお願いいたします。\n\nタイトル：".$title."\nキーワード：".$keyword."\n本文：".$content."\n\n\n回答投稿画面 URL：\nURL?question_id=".$question_id."&user_id=".$value['id'];
            $from = mb_encode_mimeheader(mb_convert_encoding('運営事務局', 'JIS', 'UTF-8')).'<info@laughtus.com>';

            mb_send_mail($to, $subject, $body, 'FROM:'.$from);
        }
    }

    //回答を投稿
    //アドバイザーユーザーのみ
    public function answerContribute($user_id, $question_id, $content)
    {
        $sql = 'INSERT INTO ch_answers SET user_id=:user_id, content=:content, question_id=:question_id, flug=1, created=NOW()';
        $data = array(
            ':user_id' => $user_id,
            ':question_id' => $question_id,
            ':content' => $content
        );
        $this->db->queryPost($sql, $data);
    }
    // ベストアンサーを選ぶ
    public function selectBestAnser($questio_id, $answer_id)
    {
        $sql = 'UPDATE ch_questions SET status=3, best_ans_id=:ans_id WHERE id=:id';
        $data = array(
            ':id' => $questio_id,
            ':ans_id' => $answer_id
        );
        $this->db->queryPost($sql, $data);
    }
    // 補足情報の投稿
    public function addSupplemnt($questio_id, $content)
    {
        $sql = 'UPDATE ch_questions SET supplement=:content WHERE id=:id';
        $data = array(
            ':id' => $questio_id,
            ':content' => $content
        );
        $this->db->queryPost($sql, $data);
    }
}
