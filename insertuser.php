<?php
ob_start();
require_once ('apps/autoload.php');
require_once ('apps/functions.php');


if(!empty($_POST)){
    $user = new User();

    $user->validRequired($_POST['name'], 'name');
    $user->validRequired($_POST['email'], 'email');
    $user->validRequired($_POST['password'], 'password');
    $user->validRequired($_POST['password_retype'], 'password_retype');

    if(empty($user->err_msg)){

        $user->validEmail($_POST['email'], 'email');
        $user->validPassType($_POST['password'], 'password');

        if(empty($user->err_msg)){

            $user->validPassLenShot($_POST['password'], 'password');
            $user->validPassLenLarg($_POST['password'], 'password');
        }

        if(empty($user->err_msg)){

            $user->validPassRetype($_POST['password'], $_POST['password_retype'], 'password');

            if(empty($user->err_msg)){

                $user->validDuplicateEmail($_POST['email'], 'email');
                $user->validDuplicateName($_POST['name'], 'name');

                if(empty($user->err_msg)){

                     $user->insertUser($_POST['name'], $_POST['email'], $_POST['password']);
                     $_SESSION['id'] = $user->id;
                     header('location: index.php');
                }
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
            <p class="label">新規登録</p>
            <form action="" method="post">
                <p><span><?php if(!empty($user->err_msg['name'])) echo h($user->err_msg['name']); ?></span>
                <input type="text" name="name" value="<?php if(!empty($_POST['name'])) echo h($_POST['name']); ?>" placeholder="Name"></p>
                <p><span><?php if(!empty($user->err_msg['email'])) echo h($user->err_msg['email']); ?></span>
                <input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo h($_POST['email']); ?>" placeholder="E-meil"></p>
                <p><span><?php if(!empty($user->err_msg['password'])) echo h($user->err_msg['password']); ?></span>
                <input type="password" name="password" value="<?php if(!empty($_POST['password'])) echo h($_POST['password']); ?>" placeholder="Password"></p>
                <p><span><?php if(!empty($user->err_msg['password_retype'])) echo h($user->err_msg['password_retype']); ?></span>
                <input type="password" name="password_retype" value="<?php if(!empty($_POST['password_retype'])) echo h($_POST['password_retype']); ?>" placeholder="password Again"></p>
                <p class="submit"><input type="submit" value="会員登録"></p>
            </form>
        </div>
    </body>
</html>
