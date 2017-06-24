<?php
require_once('../functions.php');
require_once('../autoload.php');
$question = new Question();

$new_qestions = $question->getNewQestion();
 ?>


<?php foreach ($new_qestions as $value) : ?>
    <li>
        <p class="title"><?php echo $value['title']; ?></p>
        <p class="disc"><span class="q">Q</span>：<?php echo h(mb_substr($value['content'], 0, 110)); ?>....</p>
        <p class="meta"><span class="best">依頼者：<?php echo h($value['name']); ?></span><span class="cate"><?php echo h($value['keyword']); ?></span></p>
        <p class="btn"><a href="answer.php?id=<?php echo h($value['id']); ?>">回答を投稿する >></a></p>
    </li>
<?php endforeach; ?>
