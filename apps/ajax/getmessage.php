<?php
ob_start();
session_start();
require_once('../autoload.php');
require_once('../functions.php');

$msg = new Message();

$messages = $msg->getMessages($_POST['user_id'], $_POST['to_user_id']);

?>
<?php foreach($messages as $value): ?>
    <?php if($value['user_id'] == $_SESSION['id']): ?>
        <li class="me"><?php echo h(timeEnc($value['created'])); ?><span><?php echo nl2br(h($value['content'])); ?></span></li>
    <?php else: ?>
        <li class="od"><span><?php echo nl2br(h($value['content'])); ?></span><?php echo h(timeEnc($value['created'])); ?></li>
    <?php endif ?>
<?php endforeach ?>
