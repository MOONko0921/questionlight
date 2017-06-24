<?php
function h($str){
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function timeEnc($date){
    return substr($date, 11, 5);
}
