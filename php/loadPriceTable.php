<?php require('../conn.php');
  $action = $_POST['action'];
  if(isset($_POST['id']))
    $id = $_POST['id'];

  // PRICETABLE

  if($action=="priceTable"){

    $table= load("SELECT * FROM pricechanges");

    $month = $_POST['month'];

    $table = "SELECT * FROM pricechanges ";
    if($month != 'full')
      $table = $table . "WHERE month = '$month'";
    $table = $table . " ORDER BY date DESC, id desc ";
    $table = load($table);

    echo("<table class='mainTable'><th>Edit</th> <th>Item</th> <th>Date</th> <th>Month</th> <th>Old List</th> <th>Old STD</th>
    <th>Updated List</th> <th>Updated STD</th> <th>Comments</th><th>Delete</th>");

    foreach($table as $t){
      $itemId = $t['itemId'];
      $getItem = load("SELECT * FROM items WHERE '$itemId'= id");
      $item = $getItem->fetch();
      $date = date('l, F d, Y', strtotime($t['date']));

      echo("<tr><td  width='5px' align='center'>
      <input type='radio' name='sel' value= ".$item['id']."></td>");

      echo("<td>".$item['description1']."</td>");
      echo("<td>".$date."</td>");
      echo("<td>".$titles[$t['month']]."</td>");
      echo("<td>".$t['oldList']."</td>");
      echo("<td>".$t['oldStd']."</td>");
      echo("<td>".$t['newList']."</td>");
      echo("<td>".$t['newStd']."</td>");
      echo("<td>".$t['comments']."</td>");
      echo("<td align='center'><button type='button' id='delete".$t['id']."' value='".$t['id']."' onClick=deletePrice($('#delete".$t['id']."').val());>Delete</button></td></tr>");
    }
    echo("</table>");
  }

  // DELETE

  elseif($action == "delete"){
    load("DELETE FROM `pricechanges` WHERE id = '$id'");
    echo("Log deleted successfully.");
  }

  // VIEW LOG

  elseif($action == "viewLog"){
    $log = load("SELECT * FROM pricechanges WHERE itemId = '$id'");
    echo("<table><th>Date</th><th>Month</th><th>Old List</th> <th>Old Std</th>
    <th>Updated List</th> <th>Updated STD</th> <th>Comments</th>");
    foreach($log as $l){
      $date = date('l, F d, Y', strtotime($l['date']));
      echo("<tr><td>".$date."</td>");
      echo("<td>".$titles[$l['month']]."</td>");
      echo("<td>".$l['oldList']."</td>");
      echo("<td>".$l['oldStd']."</td>");
      echo("<td>".$l['newList']."</td>");
      echo("<td>".$l['newStd']."</td>");
      echo("<td>".$l['comments']."</td></tr>");
    }
    echo("</table>");
  }

  // PRINT
  elseif($action == "print"){
    $getItem = load("SELECT * FROM items WHERE id='$id'");
    $item = $getItem -> fetch();


    echo("<table class='scroll'><th>Type</th>");
    for ($i=0; $i < 13; $i++) {
      echo("<th>".$titles[$i]."</th>");
    }

    echo("</tr><tr><td>List</td>");
    for ($i=0; $i < 13; $i++) {
      $list = $item[$months[$i] . "List"];
      echo("<td class='lightBorder'><input type='text' value=".$list." id=".$months[$i] . "List"."></td>");
    }

    echo("</tr><tr><td>STD</td>");
    for ($i=0; $i < 13; $i++) {
      $std = $item[$months[$i] . "Std"];
      echo("<td class='lightBorder'><input type='text' value=".$std." id=".$months[$i] . "Std"."></td>");
    }
   }

   //SUBMIT

  elseif($action == "submit"){
    $prices = json_decode($_POST['prices'], true);

    for ($i=0; $i < 13; $i++) {
      $list = $months[$i].'List';
      $listPrice = $prices[$list];

      $std = $months[$i].'Std';
      $stdPrice = $prices[$std];

      $stmt = "UPDATE items SET $list = '$listPrice', $std = '$stdPrice'
      where id = '$id'";
      load($stmt);
    }
    echo("Price changes submitted successfully.");
  }

  //LOG

  elseif($action == "log"){
    $log = json_decode($_POST['log'], true);
    $getItem = load("SELECT * FROM items WHERE id='$id'");
    $item = $getItem -> fetch();


    $date = $log['date'];
    $month = $log['month'];
    $oldList = $item[$months[$month]."List"];
    $oldStd = $item[$months[$month]."Std"];
    $newList = $log['newList'];
    $newStd = $log['newStd'];
    $comments = $log['comments'];

    load("INSERT INTO pricechanges (date, month, oldList, oldStd, newList, newStd, itemId, comments)
      VALUES ('$date', '$month', '$oldList', '$oldStd', '$newList', '$newStd', '$id' ,'$comments')");

    $list = $months[$month]."List";
    $std = $months[$month]."Std";
    load("UPDATE items SET $list = '$newList', $std = '$newStd' where id='$id'");
    echo("Price change logged successfully.");
  }
 ?>
