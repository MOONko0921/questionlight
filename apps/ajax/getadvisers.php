<?php
require '../autoload.php';
require '../functions.php';
$adviser = new Adviser();

$advisers = $adviser->getAdvisers($_POST['getStart']);
 ?>
 <div class="three-col">
     <?php foreach($advisers as $value): ?>
     <div class="prof-box row">
         <p class="prof-img"><img src="images/<?php echo h($value['img']); ?>" alt=""></p>
         <p class="name"><?php echo h($value['name']); ?></p>
         <p class="school">留学校：<?php echo h($value['school']); ?></p>
         <p class="school">期間：<?php echo h($value['period']); ?>週間</p>
         <p class="menu"><a href="profile.php?id=<?php echo h($value['id']); ?>"><img src="images/prof.png" alt=""></a><a href="message.php?to=<?php echo h($value['id']); ?>"><img src="images/message.png" alt=""></a></p>
     </div>
    <?php endforeach; ?>
</div>
