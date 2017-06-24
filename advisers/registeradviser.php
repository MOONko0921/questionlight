<?php
ob_start();
session_start();
require_once('../apps/autoload.php');
require_once('../apps/functions.php');


if(!empty($_POST)){

    //adviser class インスタンス生成
    $adviser = new Adviser();

    //入力チャック
    $adviser->validRequired($_POST['email'], 'email');
    $adviser->validRequired($_POST['password'], 'password');

    if(empty($adviser->err_msg)){

        //login チェック
        $adviser->login($_POST['email'], $_POST['password'], 'email');

        if(empty($user->err_msg)){
            $adviser->registerAdviser($adviser->id);
            header('location: mypage.php');
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <link rel="stylesheet" type="text/css" href="../css/reset.css">
		<link rel="stylesheet" type="text/css" href="../css/style.css">
	</head>
    </head>
    <body>
        <div class="login-box">
            <p class="label">Adviser 登録</p>
            <form action="" method="post">
                <p><span class="error"><?php if(!empty($user->err_msg['email'])) echo h($user->err_msg['email']); ?></span>
                <input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo h($_POST['email']); ?>" placeholder="E-mail"></p>
                <p><span class="error"><?php if(!empty($user->err_msg['password'])) echo h($user->err_msg['password']); ?></span>
                <input type="password" name="password" value="<?php if(!empty($_POST['password'])) echo h($_POST['password']); ?>" placeholder="Password"></p>
                <p><input type="submit" value="登録"></p>
            </form>
        </div>
    </body>
</html>
