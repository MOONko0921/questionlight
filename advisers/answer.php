<?php
ob_start();
session_start();
$file_path = '../';
require_once ($file_path.'apps/autoload.php');
require_once ($file_path.'apps/functions.php');
$question = new Question();

if(!empty($_POST['content'])){
    $question->answerContribute($_SESSION['id'], $_POST['question_id'], $_POST['content']);
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
                    <?php if(!empty($q_con['supplement'])): ?>
                        <div class="supplement">
                            <p class="st"><span>補足</span></p>
                            <p class="scon"><?php echo nl2br(h($q_con['supplement'])); ?></p>
                        </div>
                    <?php endif; ?>
                    <p class="add"></p>
                </div>
                <?php foreach($a_con as $value): ?>
                    <div class="answer">
                        <p class="body"><?php echo nl2br(h($value['content'])); ?></p>
                        <p class="add"></p>
                        <div class="meta">
                            <p class="adv">
                                <img src="../images/prof1.jpg" alt="">
                                <span class="name"><?php echo h($value['name']); ?></span>
                            </p>
                            <div class="prof-box">
                                <p class="close"><img src="../images/close.png" alt="x"></p>
                                <p class="prof-img"><img src="../images/prof1.jpg" alt=""></p>
                                <p class="name"><?php echo h($value['name']); ?></p>
                                <p class="school">留学校：<?php echo h($value['school']); ?></p>
                                <p class="school">期間：<?php echo h($value['period']); ?>週間</p>
                                <p class="menu"><a href="../profile.php?id=<?php echo h($value['user_id']); ?>"><img src="../images/prof.png" alt=""></a><a href="../message.php?to=<?php echo h($value['user_id']); ?>"><img src="../images/message.png" alt=""></a></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="add-question-box">
                    <p class="at"><img src="../images/answer.png" alt=""></p>
                    <form action="" method="post">
                        <p><textarea name="content" id=""></textarea></p>
                        <input type="hidden" name="question_id" value="<?php echo h($_GET['id']); ?>">
                        <p class="submit"><input type="submit"></p>
                    </form>
                </div>
            </div>
        </div>
        <?php require '_include/footer.php'; ?>
        </script>
    </body>
</html>
