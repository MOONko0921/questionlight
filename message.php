<?php

ob_start();
session_start();
require_once('apps/autoload.php');
require_once('apps/functions.php');
if(empty($_SESSION['id'] )){
    $_SESSION['login_filepath'] = 'message.php';
    header('location: login.php');
}


$msg = new Message();


if(!empty($_GET['to'])){
    $msg->startMessage($_SESSION['id'], $_GET['to']);
}

$msg_list = $msg->getMessagesList($_SESSION['id']);
?>
<?php require '_include/header.php'; ?>
        <div class="wraper">
            <div class="chat-list-box">
                <ul>
                    <?php if($msg_list): ?>
                        <?php foreach($msg_list as $value): ?>
                            <?php if(@$_GET['to'] == $value[0]['id']): ?>
                                <li class="active" data-to="<?php echo h($value[0]['id']); ?>"><img src="images/<?php echo h($value[0]['img']); ?>" alt=""><span class="name"><?php echo h($value[0]['name']); ?></span></li>
                            <?php else: ?>
                                <li data-to="<?php echo h($value[0]['id']); ?>"><img src="images/<?php echo h($value[0]['img']); ?>" alt=""><span class="name"><?php echo h($value[0]['name']); ?></span></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="no-msg" data-to="<?php echo h($value['id']); ?>">メッセージはありません</li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="main-box">
                <div class="message-btn">
                    <p><img src="images/leftright.png" alt="">Message List</p>
                </div>
                <div class="message-box">
                    <ul>
                    </ul>
                </div>
                <div class="form">
                    <p class="textarea"><textarea name="text"></textarea></p>
                    <p class="submit"><button>submit</button></p>
                </div>
            </div>
        </div>
        <?php require '_include/footer.php'; ?>
        <script>
            $(function () {
                var i=1;
                $('.message-btn img').on('click', function(){

                    if(i == 1){
                        $('.main-box').addClass('move');
                        $('.message-btn img').attr('src', 'images/leftleft.png');
                        i=2;
                    }else {
                        i=1;
                        $('.message-btn img').attr('src', 'images/leftright.png');
                        $('.main-box').removeClass('move');
                    }
                });

                var wraperHeight;
                var messageHeight;
                if($(window).width() > 930){
                    wraperHeight = $(window).height() - ($('header').height() + $('.top-nav').height());
                    messageHeight = wraperHeight - 135;
                }else{
                    wraperHeight = $(window).height() - $('header').height();
                    messageHeight = wraperHeight - 180;
                }
                $('.message-box').height(messageHeight);
                $('.wraper').height(wraperHeight);
                function sendMessage(message) {
                    $.ajax({
                        url: 'apps/ajax/sendmessage.php',
                        type: 'post',
                        dataType: 'html',
                        data: {
                            message: message,
                            to_user_id: to,
                            user_id: <?php echo h($_SESSION['id']); ?>
                        },
                        success: function (data) {
                            $('.message-box ul').html($(data));
                        }
                    });

                }
                $('.form button').on('click', function(){
                    var message = $('textarea').val();
                    $('textarea').val('');
                    if(message != '' && to != ''){
                        sendMessage(message);
                    }
                });
                function getMessage(to, getStart) {
                    $.ajax({
                        url: 'apps/ajax/getmessage.php',
                        type: 'post',
                        dataType: 'html',
                        data: {
                            to_user_id: to,
                            user_id: <?php echo h($_SESSION['id']); ?>
                        },
                        success: function (data) {
                            $('.message-box ul').html($(data));
                        }
                    });
                }
                var to = '';
                <?php if(!empty($_GET['to'])): ?>
                    to = <?php echo h($_GET['to']); ?>;
                    getMessage(to, 0);
                <?php endif; ?>
                $('.chat-list-box ul li').on('click', function () {
                    $('.chat-list-box ul li').removeClass('active');
                    $(this).addClass('active');
                    to = $(this).attr('data-to');
                    getMessage(to, 0);
                    i=1;
                    $('.message-btn img').attr('src', 'images/leftright.png');
                    $('.main-box').removeClass('move');
                    $('.message-box').delay(100).animate({
                        scrollTop: 30000
                    },1000);
                });
            });
        </script>
    </body>
</html>
