<?php
ob_start();
session_start();
require_once('apps/autoload.php');
require_once('apps/functions.php');

if(empty($_SESSION['id']) || $_SESSION['time'] + 3600 < time()){
    header('location:login.php');
}
$_SESSION['time'] = time();

if(!empty($_POST)){
    $val = new Validate();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title></title>
    </head>
    <body>
        <form action="" method="post">
            <input type="text" name="title" value="">
            <textarea name="content"></textarea>
            <select id="keyword">
                <option value="">カテゴリー</option>
                <option value="school">学校情報</option>
                <option value="loacal">現地情報</option>
                <option value="else">その他</option>
            </select>
            <input type="submit" value="送信">
        </form>
    </body>
</html>
