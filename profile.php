<?php
ob_start();
require_once ('apps/autoload.php');
require_once ('apps/functions.php');
$adviser = new Adviser();

$adv = $adviser->getAdviser($_GET['id']);
$ba = $adviser->countBestAnsewer($_GET['id']);
?>
        <?php require '_include/header.php'; ?>
        <div class="wraper">
            <div class="profile-box">
                <div class="prof">
                    <p><img src="images/<?php echo h($adv['img']); ?>" alt=""></p>
                 </div>
                 <div class="career">
                     <p class="name"><?php echo h($adv['name']); ?></p>
                     <ul>
                         <li><span>留学校</span><span class="block"><?php echo h($adv['school']); ?></span></li>
                         <li><span>留学期間</span><span class="block"><?php echo h($adv['period']); ?>週間</span></li>
                         <li><span>ベストアンサー</span><span class="block"><?php echo h($ba); ?>回</span></li>
                     </ul>
                 </div>
            </div>
            <div class="prof">
                <div class="message">
                    <p class="title">Message</p>
                    <p class="content"><?php echo nl2br(h($adv['message'])); ?></p>
                </div>
                <div class="data">
                    <p class="title">Test Score</p>
                    <p class="color"><span class="label">TOEIC</span><span class="body"><?php echo h($adv['toeic_point']); ?></span></p>
                    <p class=""><span class="label">TOEFL</span><span class="body"><?php echo h($adv['toefl_point']); ?></span></p>
                    <p class="color"><span class="label">IELTS</span><span class="body"><?php echo h($adv['ielts_point']); ?></span></p>
                    <p class=""><span class="label">その他</span><span class="body"><?php echo h($adv['license']); ?></span></p>
                </div>
            </div>
        </div>
        <?php require '_include/footer.php'; ?>
    </body>
</html>
