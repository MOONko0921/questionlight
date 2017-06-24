<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="../apps/js/menu.js"></script>
<script>
    var addnav = '<li><a href="../apps/logout.php">Logout</a></li>';
    if($(window).width() < 930){
        $('.top-nav ul').append(addnav);
    }
</script>
