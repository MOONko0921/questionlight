<?php
ob_start();
session_start();

require_once('apps/functions.php');
require_once('apps/autoload.php');


if(empty($_GET['id'])){
    header('location: index.php');
}
$question = new Question();
if(!empty($_POST['supplement'])){
    $question->addSupplemnt($_POST['question_id'], $_POST['supplement']);
}

$q_con = $question->getQuestion($_GET['id']);
$a_con = $question->getAnswer($_GET['id']);

?>
        <?php require '_include/header.php'; ?>
        <div class="wraper">
            <div class="question-box">
                <div class="question">
                    <p class="title"><span class="q">Q</span>：<?php echo h($q_con['title']); ?></p>
                    <p class="body"><?php echo nl2br(h($q_con['content'])); ?></p>
                    <div class="supplement">
                        <p class="st"><span>補足</span></p>
                        <form action="" method="post">
                            <p><textarea name="supplement" ><?php if(!empty($q_con)) echo h($q_con['supplement']); ?></textarea></p>
                            <input type="hidden" name="question_id" value="<?php echo h($_GET['id']); ?>">
                            <p class="submit"><input type="submit" value="送信"></p>
                        </form>
                    </div>
                </div>
                <?php foreach($a_con as $value): ?>
                    <div class="answer">
                        <p class="body"><?php echo nl2br(h($value['content'])); ?></p>
                        <p class="add"></p>
                        <div class="meta">
                            <p class="adv">
                                <img src="images/<?php echo h($value['img']); ?>" alt="">
                                <span class="name"><?php echo h($value['name']); ?></span>
                            </p>
                            <div class="prof-box">
                                <p class="close"><img src="images/close.png" alt="x"></p>
                                <p class="prof-img"><img src="images/<?php echo h($value['img']); ?>" alt=""></p>
                                <p class="name"><?php echo h($value['name']); ?></p>
                                <p class="school">留学校：<?php echo h($value['school']); ?></p>
                                <p class="school">期間：<?php echo h($value['period']); ?>週間</p>
                                <p class="menu"><a href="profile.php?id=<?php echo h($value['user_id']); ?>"><img src="images/prof.png" alt=""></a><a href="message.php?to=<?php echo h($value['user_id']); ?>"><img src="images/message.png" alt=""></a></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="comment-box">
                    <div class="comment">
                        <p class="body"></p>
                        <p class="meta">
                            <sapn class="name"></sapn>
                            <span class="date"></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <?php require '_include/footer.php'; ?>
    </body>
</html>
