<?php
ob_start();
session_start();
require_once ('apps/autoload.php');
require_once ('apps/functions.php');

$user = new User();
$question = new Question();

$prof = $user->getUser($_SESSION['id']);


$new_qestions = $question->getNewQestion();
?>
        <?php require '_include/header.php'; ?>
        <div class="wraper">
            <div class="profile-box">
                <div class="prof">
                    <p><img src="images/prof1.jpg" alt=""></p>
                 </div>
                 <div class="career">
                     <p class="name"><?php echo h($prof['name']); ?></p>
                     <p class="email"><?php echo h($prof['email']); ?></p>
                 </div>
            </div>
            <?php if(!empty($new_qestions)): ?>
            <div class="question-list">
                <p class="loading">Loading...</p>
                <ul>

                        <?php foreach ($new_qestions as $value) : ?>
                            <li>
                                <p class="title"><?php echo $value['title']; ?></p>
                                <p class="disc"><span class="q">Q</span>：<?php echo h(mb_substr($value['content'], 0, 110)); ?>....</p>
                                <p class="ans-disc"><span class="a">A</span>：<?php echo h(mb_substr($value['answer'], 0, 110)); ?>....</p>
                                <p class="meta"><span class="best">ベストアンサー：<?php echo h($value['name']); ?></span><span class="cate"><?php echo h($value['keyword']); ?></span></p>
                                <p class="btn"><a href="page.php?id=<?php echo h($value['id']); ?>">続きを読む >></a></p>
                            </li>
                        <?php endforeach; ?>


                </ul>
                <div class="btn-waku"><span>もっと見る</span></div>
            </div>
            <?php endif; ?>
        <?php require '_include/footer.php'; ?>
    </body>
</html>
