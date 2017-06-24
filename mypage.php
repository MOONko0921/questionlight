<?php
ob_start();
session_start();
require_once ('apps/autoload.php');
require_once ('apps/functions.php');

if(empty($_SESSION['id'] )){
    $_SESSION['login_filepath'] = 'mypage.php';
    header('location: login.php');
}


$user = new User();
$question = new Question();

$prof = $user->getUser($_SESSION['id']);


$my_qestions = $question->getMyQuestion($_SESSION['id']);
?>
        <?php require '_include/header.php'; ?>
        <div class="wraper">
            <div class="profile-box">
                <div class="prof">
                    <p><img src="images/<?php echo h($prof['img']); ?>" alt=""></p>
                 </div>
                 <div class="career">
                     <p class="name"><?php echo h($prof['name']); ?><a href="profedit.php">Edit Profile</a></p>
                     <p class="email"><?php echo h($prof['email']); ?></p>
                 </div>
            </div>
            <?php if(!empty($my_qestions)): ?>
            <div class="question-list">
                <p class="loading">あなたの質問</p>
                <ul>
                    <?php foreach ($my_qestions as $value) : ?>
                        <?php $a_con = $question->getAnswer($value['id']); ?>
                        <li>
                            <p class="title"><?php echo $value['title']; ?></p>
                            <p class="disc"><span class="q">Q</span>：<?php echo h(mb_substr($value['content'], 0, 110)); ?>....</p>
                            <?php foreach ($a_con as $answer) : ?>
                                <p class="ans-disc"><span class="a">A</span>：<?php echo h(mb_substr($answer['content'], 0, 110)); ?>....</p>
                            <?php endforeach; ?>
                            <p class="meta"><span class="cate"><?php echo h($value['keyword']); ?></span></p>
                            <p class="btn"><a href="pagemy.php?id=<?php echo h($value['id']); ?>">続きを読む >></a></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
        <?php require '_include/footer.php'; ?>
    </body>
</html>
