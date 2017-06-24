<?php
ob_start();
session_start();

require_once 'autoload.php';

$question = new Question();

$question->selectBestAnser($_GET['question_id'], $_GET['answer_id']);

header('location: ../index.php');
