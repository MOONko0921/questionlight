$('.m-nav').on('click', '.m-btn', function () {
    $('.top-nav').addClass('m-btn-c');
});
$('.m-nav').on('click', '.m-btn-c', function () {
    $('.top-nav').removeClass('m-btn-c');
});
$('.adv').on('click', function (e) {
    $(this).next().show();
});
$('.close').on('click', function (e) {
    $(this).parent().hide();
});
