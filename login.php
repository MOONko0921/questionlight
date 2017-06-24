<?php
ob_start();
session_start();
require_once('apps/autoload.php');
require_once('apps/functions.php');


if(!empty($_POST)){

    //user class インスタンス生成
    $user = new User();

    //入力チャック
    $user->validRequired($_POST['email'], 'email');
    $user->validRequired($_POST['password'], 'password');

    if(empty($user->err_msg)){

        //login チェック
        $user->login($_POST['email'], $_POST['password'], 'email');

        if(empty($user->err_msg)){
            $_SESSION['id'] = $user->id;
            $_SESSION['status'] = $user->status;
            $_SESSION['time'] = time();
            if($user->status == 1){
                if(!empty($_SESSION['login_filepath'])){
                    header('location:'.$_SESSION['login_filepath']);
                }else{
                    header('location: index.php');
                }
            }else{
                header('location: advisers/');
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <link rel="stylesheet" type="text/css" href="css/reset.css">
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
    </head>
    <body>
        <div class="login-box">
            <p class="label">Login</p>
            <form action="" method="post">
                <p><span class="error"><?php if(!empty($user->err_msg['email'])) echo h($user->err_msg['email']); ?></span>
                <input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo h($_POST['email']); ?>" placeholder="E-mail"></p>
                <p><span class="error"><?php if(!empty($user->err_msg['password'])) echo h($user->err_msg['password']); ?></span>
                <input type="password" name="password" value="<?php if(!empty($_POST['password'])) echo h($_POST['password']); ?>" placeholder="Password"></p>
                <p><input type="submit" value="ログイン"></p>
            </form>
        </div>
    </body>
</html>
