<?php require('../conn.php');
if($_POST['action'] == "copyPrices") copyPrices();
elseif($_POST['action'] == "submitFeedback") submitFeedback();
elseif($_POST['action'] == "loadFeedback") loadFeedback();

/////////////////////////////////////////////////////////////
function copyPrices(){
  $items = load("SELECT * FROM `items` WHERE 1");

  foreach($items as $item){
    global $months;
    $copyMonthStd = $item[$months[$_POST['copyMonth']] . "Std"];
    $copyMonthList = $item[$months[$_POST['copyMonth']] . "List"];
    $pasteMonthStd = $months[$_POST['pasteMonth']] . "Std";
    $pasteMonthList = $months[$_POST['pasteMonth']] . "List";
    $id = $item['id'];
    load("UPDATE `items` SET $pasteMonthStd = $copyMonthStd WHERE id = '$id'");
    load("UPDATE `items` SET $pasteMonthList = $copyMonthList WHERE id = '$id'");

  }
  echo("Prices coppied successfully.");
}

function submitFeedback(){
  $text = $_POST['text'];
  $stmt = ("INSERT INTO `comments` (text) VALUES ('$text') ORDER BY `id` DESC");
  load($stmt);
  echo('Your comment has been submitted.  Thank you for your feedback!');
}

function loadFeedback(){
  $comments = load("SELECT * FROM `comments` WHERE 1");
  foreach($comments as $comment)
    echo("[".$comment['id']."] ".$comment['text']);
    echo("<br>________________________________________________________________________________________________________<br>");
}
 ?>
