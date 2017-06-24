<?php
require_once('../functions.php');
require_once('../autoload.php');
$question = new Question();

$list = $question->getQuestionsList($_POST['get_start'], @$_POST['keyword'], @$_POST['freeword']);


?>
<?php foreach ($list as $value) : ?>
    <li>
        <p class="title"><?php echo $value['title']; ?></p>
        <p class="disc"><span class="q">Q</span>：<?php echo h(substr($value['content'], 0, 110)); ?>....</p>
        <p class="ans-disc"><span class="a">A</span>：<?php echo h(substr($value['answer'], 0, 110)); ?>....</p>
        <p class="meta"><span class="best">ベストアンサー：<?php echo h($value['name']); ?></span><span class="cate"><?php echo h($value['keyword']); ?></span></p>
        <p class="btn"><a href="page.php?id=<?php echo h($value['id']); ?>">続きを読む >></a></p>
    </li>
<?php endforeach; ?>
