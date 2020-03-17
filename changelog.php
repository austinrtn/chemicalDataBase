<?php require("conn.php"); ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <h1 align='center'><u>Changelog</u></h1>
    <?php
      $logs = load("SELECT * FROM `versions` WHERE `show_in_changelog` = 'TRUE' ORDER BY `versions`.`id` DESC");
      foreach ($logs as $log) {
        $date = date('m-d-Y', strtotime($log['date']));
        echo("<h3>".$log['versionId']. " | ".$date. "</h3>".$log['description']."<br>");
      }
     ?>
     Changelog is undergoing maintance.
  <form action="index.php">
    <button type="submit" name="button">Back</button>
  </form>
  </body>
</html>
