<?php
ob_start();
session_start();
require_once('apps/autoload.php');
require_once('apps/functions.php');

//　ログイン前に質問を送信しようとしていた場合
if(!empty($_SESSION['qq'])){
    $_POST = $_SESSION['qq'];
    $_SESSION['qq'] = array();
}
// 質問の投稿
if(!empty($_POST)){
    // ログイン確認
    if(empty($_SESSION['id'])){
        // login していなければ送信内容をSESSIONに保存して　ログインページへ
        $_SESSION['login_filepath'] = 'index.php';
        $_SESSION['qq']['title'] = $_POST['title'];
        $_SESSION['qq']['content'] = $_POST['content'];
        $_SESSION['qq']['keyword'] = $_POST['keyword'];
        header('location: login.php');
        exit();
    }else{
        // 入力チェックを行ってデータベースに保存
        $valid = new Validate();
        $question = new Question();
        $valid->validRequired($_POST['title'], 'title');
        $valid->validRequired($_POST['content'], 'content');
        if(empty($valid->err_msg)){
            $question->questionRequest($_SESSION['id'], $_POST['keyword'], $_POST['title'],  $_POST['content']);
        }
    }
}
?>
        <?php require '_include/header.php'; ?>
        <div class="wraper">
            <div class="serch-form">
                <p class="forms">
                    <select id="keyword">
                        <option value="">キーワード</option>
                        <option value="school">学校情報</option>
                        <option value="loacal">現地情報</option>
                        <option value="else">その他</option>
                    </select>
                    <input type="text" value="" id="freeword">
                    <button type="button" id="serch">検索</button>
                </p>
            </div>
            <div class="question-list">
                <p class="loading">Loading...</p>
                <ul>

                </ul>
                <div class="btn-waku"><span>もっと見る</span></div>
            </div>
            <div class="add-question-box">
                    <p class="qt"><img src="images/question.png" alt=""></p>
                    <?php if(!empty($valid->err_msg)): ?>
                        <div class="error-box">
                            <p class="error"><?php if(!empty($valid->err_msg['title'])) echo h('タイトル：'.$valid->err_msg['title']); ?></p>
                            <p class="error"><?php if(!empty($valid->err_msg['content'])) echo h('本文：'.$valid->err_msg['content']); ?></p>
                        </div>
                    <?php endif; ?>
                    <form action="" method="post">
                        <p>
                            <input type="text" name="title">
                            <select name="keyword" id="">
                                <option value="その他">カテゴリー</option>
                                <option value="その他">その他</option>
                            </select>
                        </p>
                        <p><textarea name="content" id=""></textarea></p>
                        <p class="submit"><input type="submit"></p>
                    </form>
                </div>
            </div>
        <?php require '_include/footer.php'; ?>
        <script>
        $(function(){
            var freewordPost, keywordPost;
            var freeword = $('#freeword');
            var keyword = $('#keyword');
            keyword.on('change', function(){
                keywordPost = keyword.val();
            });
            freeword.on('keyup', function(){
                freewordPost = freeword.val();
            });
            var getStart = 0;
            function getQuestionsList(){
                $('.question-list ul').empty();
                $('.loading').show();
                $.ajax({
                    url:'apps/ajax/questionslist.php',
                    type:'post',
                    dataType: 'html',
                    data: {
                        get_start:getStart,
                        keyword:keywordPost,
                        freeword:freewordPost
                    },
                    success: function(data){
                        $('.loading').hide();
                        $(data).appendTo('.question-list ul');
                    }
                });
                $('.key-res').html(keywordPost);
                $('.free-res').html(freewordPost);
            }
            getQuestionsList();
            $('#serch').on('click', function(){
                getQuestionsList();
            });
        });
        </script>
    </body>
</html>
