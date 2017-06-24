<?php
ob_start();
session_start();
$file_path = '../';
require_once ($file_path.'apps/autoload.php');
require_once ($file_path.'apps/functions.php');

$adviser = new Adviser();
$question = new Question();

$adv = $adviser->getAdviser($_SESSION['id']);
$ba = $adviser->countBestAnsewer($_SESSION['id']);

$new_qestions = $question->getNewQestion();
?>
        <?php require '_include/header.php'; ?>
        <div class="wraper">
            <div class="profile-box">
                <div class="prof">
                    <p><img src="../images/<?php echo h($adv['img']); ?>" alt=""></p>
                 </div>
                 <div class="career">
                     <p class="name"><?php echo h($adv['name']); ?><a href="profedit.php">Edit Profile</a></p>
                     <ul>
                         <li><span>留学校</span><span class="block"><?php echo h($adv['school']); ?></span></li>
                         <li><span>留学期間</span><span class="block"><?php echo h($adv['period']); ?>週間</span></li>
                         <li><span>ベストアンサー</span><span class="block"><?php echo h($ba); ?>回</span></li>
                     </ul>
                 </div>
            </div>
            <?php if(!empty($new_qestions)): ?>
            <div class="question-list">
                <ul>




                </ul>
            </div>
            <?php endif; ?>
        <?php require $file_path.'_include/footer.php'; ?>
    </body>
</html>
