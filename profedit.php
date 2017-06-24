<?php
ob_start();
session_start();
$file_path = '';
require_once ($file_path.'apps/autoload.php');
require_once ($file_path.'apps/functions.php');

$user = new User();
$now_user = $user->getUser($_SESSION['id']);
// 基本情報変更
if(!empty($_POST['email']) && !empty($_POST['name'])){
    if($now_user['name'] != $_POST['name']) $user->validDuplicateName($_POST['name'], 'name');
    if($now_user['email'] != $_POST['email']) $user->validDuplicateEmail($_POST['email'], 'email');
    if(empty($user->err_msg)){
        $user->updateBasicProfile($_POST['name'], $_POST['email'], $_SESSION['id']);
    }
}
// password変更
if(!empty($_POST['password']) && !empty($_POST['password_retype'])){

    $user->validPassType($_POST['password'], 'password');

    if(empty($user->err_msg)){

        $user->validPassLenShot($_POST['password'], 'password');
        $user->validPassLenLarg($_POST['password'], 'password');

        if(empty($user->err_msg)){

            $user->validPassRetype($_POST['password'], $_POST['password_retype'], 'password');
            if(empty($user->err_msg)){

                $user->resetPassword($_POST['password'], $_SESSION['id']);
            }
        }
    }
}
if(!empty($_FILES)){
    $user->updateProfImg($_FILES['prof_img'], $_POST['crop_x'], $_POST['crop_y'], $_POST['crop_width'], $_POST['crop_height'], $file_path, $_SESSION['id']);
}

 ?>
 <?php require '_include/header.php';?>
 <div class="wraper">
             <div class="edit-list-box">
                 <ul>
                     <li class="active" data-target="basic">基本登録情報</li>
                     <li data-target="prof-img">プロフィール写真</li>
                 </ul>
             </div>
             <div class="main-box">
                 <div class="message-btn">
                     <p><img src="images/leftright.png" alt="">Edit Profile</p>
                 </div>
                 <div class="edit-forms">
                     <div id="basic" class="prof-form active">
                         <p class="title">基本登録情報</p>
                         <form class="" action="" method="post">
                             <p><input type="text" name="name" placeholder="Name"></p>
                             <p><input type="text" name="email" placeholder="E-mail"></p>
                             <p class="submit"><input type="submit" value="Edit"></p>
                         </form>
                         <p class="title">パスワード変更</p>
                         <form class="" action="" method="post">
                             <p><input type="password" name="password" placeholder="New Password"></p>
                             <p><input type="password" name="password_retype" placeholder="Password Retype"></p>
                             <p class="submit"><input type="submit" value="Edit"></p>
                         </form>
                     </div>
                     <div id="prof-img" class="prof-form">
                         <p class="title">プロフィール写真変更</p>
                         <p class="img"><img src="images/<?php echo h($now_user['img']); ?>" alt=""></p>
                         <p class="btn"><span>Change Image</span></p>
                     </div>
                 </div>
             </div>
         </div>
         <div class="cover">
         <div class="popup befor">
             <div class="img-edit">
             <form action="" method="post" enctype="multipart/form-data">
                 <input type="file" name="prof_img" id="prof-image">
                 <input type="hidden" name="crop_width" id="crop-width">
                 <input type="hidden" name="crop_height" id="crop-height">
                 <input type="hidden" name="crop_x" id="crop-x">
                 <input type="hidden" name="crop_y" id="crop-y">
                 <input type="submit" id="submit"  class="co-logo">
             </form>
             <div class="cropper-box">
                 <div class="cropper">
                     <img id="img" class="img-responsive" src="" alt="">
                 </div>
             </div>
             <p class="close"><img src="images/close.png" alt="x"></p>
             </div>
         </div>
     </div>
         <?php require '_include/footer.php'; ?>
         <script src="apps/js/cropper.min.js"></script>
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
                 $('.m-nav').on('click', '.m-btn', function () {
                     $('.top-nav').addClass('m-btn-c');
                 });
                 $('.m-nav').on('click', '.m-btn-c', function () {
                     $('.top-nav').removeClass('m-btn-c');
                 });
                 var wraperHeight;
                 var messageHeight;
                 if($(window).width() > 930){
                     wraperHeight = $(window).height() - ($('header').height() + $('.top-nav').height());
                     messageHeight = wraperHeight - 80;
                 }else{
                     wraperHeight = $(window).height() - $('header').height();
                     messageHeight = wraperHeight - 140;
                 }

                 $('.forms').height(messageHeight);
                 $('.wraper').height(wraperHeight);
                 $('textarea').height(150);

                 $('.edit-list-box ul li').on('click', function () {
                     $('.active').removeClass('active');
                     $(this).addClass('active');
                     $('#'+$(this).attr('data-target')).addClass('active');
                 });

                 var asp=1;
                 $('.btn span').on('click', function(){
                     asp =1;
                     $('.img-edit').show();
                     $('#crop-name').val('prof');
                     $('.pre-box').attr('id', 'prof');
                     $('.cover').fadeIn(function(){
                         $('.popup').removeClass('befor');
                     });
                 });
                 $('.close').on('click', function(){
                     $('.cover').fadeOut();
                     $('#prof-img').val('');
                     $('.pre-box').attr('id', '');
                     $image.attr('src', '');
                     $image.cropper('destroy');
                 });
                 $('#prof-image').change(function() {
                     console.log(111);
                     var file = $(this).prop('files')[0];
                         // 画像以外は処理を停止
                     if (! file.type.match('image.*')) {
                         // クリア
                         $(this).val('');
                         return;
                     }
                         //cropper リセット
                     var reader = new FileReader();
                     reader.readAsDataURL(file);
                     reader.onload = function() {
                         $image.cropper('destroy');
                         $image.attr('src', reader.result);
                         $image.cropper({
                             aspectRatio: asp,
                             crop: function(data) {
                                 $dataX.val(Math.round(data.x));
                                 $dataY.val(Math.round(data.y));
                                 $dataHeight.val(Math.round(data.height));
                                 $dataWidth.val(Math.round(data.width));
                             }
                         });
                     }
                 });

                 // 切り抜きした画像のデータ
                 var $dataWidth = $('#crop-width');
                 var $dataHeight = $('#crop-height');
                 var $dataX = $('#crop-x');
                 var $dataY = $('#crop-y');
                 //cropper　初期設定
                 var $image = $('.cropper > img');
                 $image.cropper({
                   aspectRatio: asp,
                   crop: function(data) {
                     $dataX.val(Math.round(data.x));
                     $dataY.val(Math.round(data.y));
                     $dataHeight.val(Math.round(data.height));
                     $dataWidth.val(Math.round(data.width));
                   }
                 });
             });

         </script>
     </body>
 </html>
