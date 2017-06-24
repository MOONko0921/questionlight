<?php
ob_start();
session_start();
$file_path = '../';
require_once ($file_path.'apps/autoload.php');
require_once ($file_path.'apps/functions.php');


?>
        <?php require '_include/header.php'; ?>
        <div class="wraper">
            <div class="question-list">
                <p class="loading">Loading...</p>
                <ul>

                </ul>
                <div class="btn-waku"><span>もっと見る</span></div>
            </div>
        </div>
        <?php require '_include/footer.php'; ?>
        <script>
        $(function(){
            var freewordPost, keywordPost;
            var freeword = $('#freeword');
            var keyword = $('#keyword');
            keyword.on('change', function(){
                keywordPost = keyword.val();
            });
            freeword.on('keyup', function(){
                freewordPost = freeword.val();
            });
            var getStart = 0;
            function getQuestionsList(){
                $('.question-list ul').empty();
                $('.loading').show();
                $.ajax({
                    url:'../apps/ajax/newquestionslist.php',
                    type:'post',
                    dataType: 'html',
                    data: {
                        get_start:getStart,
                        keyword:keywordPost,
                        freeword:freewordPost
                    },
                    success: function(data){
                        $('.loading').hide();
                        $(data).appendTo('.question-list ul');
                    }
                });
            }
            getQuestionsList();
            $('#serch').on('click', function(){
                getQuestionsList();
            });
        });
        </script>
    </body>
</html>
