<?php
spl_autoload_register(function ($class){
    require 'class/'.$class.'.class.php';
});
