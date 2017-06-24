<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <link rel="stylesheet" type="text/css" href="<?php echo h(@$file_path); ?>css/reset.css">
		<link rel="stylesheet" type="text/css" href="<?php echo h(@$file_path); ?>css/style.css">
        <link rel="stylesheet" href="../css/cropper.min.css">
	</head>
    <body>
        <header>
            <div class="header-box">
                <p class="logo"><a href=""><img src="<?php echo h(@$file_path); ?>images/logo.png" alt=""></a></p>
                <div class="hr">
                    <div class="bn">
                        <img src="../images/bana.png" alt="">
                    </div>
                    <ul>
                        <li><a href="../apps/logout.php">Logout</a></li>
                    </ul>
                </div>
                <div class="m-nav">
                    <p class="m-btn"><img src="<?php echo h(@$file_path); ?>images/m-menu.png" alt=""></p>
                </div>
            </div>
        </header>
        <nav class="top-nav">
            <div class="m-nav">
                <p class="m-btn-c"><img src="<?php echo h(@$file_path); ?>images/rightright.png" alt=""></p>
            </div>
            <ul>
                <li><a href="index.php">Top</a></li>
                <li><a href="message.php">Message</a></li>
                <li><a href="mypage.php">My Page</a></li>
                <li><a href="../index.php">User Page</a></li>
            </ul>
        </nav>
