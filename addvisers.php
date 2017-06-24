<?php
session_start();
require 'apps/autoload.php';
require 'apps/functions.php';
$adviser = new Adviser();

 ?>
        <?php require '_include/header.php'; ?>
        <div class="wraper">
            <div class="advisers-box">

            </div>
            <p class="loading" style="display:none;">Loading...</p>
            <p class="btn"><span>もっと見る</span></p>
        </div>
        <?php require '_include/footer.php'; ?>
        <script>
            $(function(){
                $('.btn span').on('click', function(){
                    getAdvisers();
                });
                var getStart = 0;
                function getAdvisers(){
                    $('.loading').show();
                    $('.ajax-box').empty();
                    $.ajax({
                        url:'apps/ajax/getadvisers.php',
                        type:'post',
                        dataType: 'html',
                        data: {
                            getStart:getStart
                        },
                        success: function(data){
                            $('.loading').hide();
                            $(data).appendTo('.advisers-box');
                        }
                    });
                    getStart+=3;
                }
                getAdvisers();
                getAdvisers();
            });
        </script>
    </body>
</html>
