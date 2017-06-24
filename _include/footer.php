<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="apps/js/menu.js"></script>
<script>
    <?php if(!empty($_SESSION['id'])): ?>
    var addnav = '<li><a href="apps/logout.php">Logout</a></li>'
    <?php else: ?>
    var addnav = '<li><a href="login.php">Login</a></li><li><a href="insertuser.php">Sing up</a></li>';
    <?php endif; ?>
    if($(window).width() < 930){
        $('.top-nav ul').append(addnav);
    }
</script>
