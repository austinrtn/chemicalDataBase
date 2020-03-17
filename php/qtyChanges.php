<?php require('../conn.php');
  $action = $_POST['action'];
  if(isset($_POST['log']))
    $log = json_decode($_POST['log'], true);

  if($action == "table")
    table($titles);
  elseif($action == "delete")
    delete();
  elseif($action == "viewLog")
    viewLog($titles);
  elseif($action == 'log')
    logQtyChange($log, $months);
  elseif($action == 'getCost')
    getCost($months);
  elseif($action == 'issueAndSell')
    issueAndSell();



  function table($titles){
    $month = $_POST['month'];
    if(isset($_POST['sortType']))
      $sort = $_POST['sortType'];
    else
      $sort = "date";

    if(isset($_POST['ascDesc']))
      $ascDesc = $_POST['ascDesc'];

    $qtychanges = "SELECT * FROM `qtychanges`";
    if($month != 'full')
      $qtychanges = $qtychanges . "WHERE month = '$month'";
    if((isset($_POST['ascDesc'])))$qtychanges = $qtychanges . " ORDER BY  `qtychanges`.$sort $ascDesc ;";
    $qtychanges = load($qtychanges);


    echo("<table class='mainTable'><tr><th>Edit</th> <th>Description</th> <th>Date</th> <th>Issued</th> <th>Sold</th> <th>Month</th>
    <th>Action</th> <th>Cost</th>  <th>Previous Qty</th>
    <th>Amount</th>  <th>New Qty</th> <th>Dept</th> <th>Delete</th></tr>");

    foreach($qtychanges as $qty){
      $itemId = $qty['itemId'];
      $getItem = load("SELECT * FROM items WHERE '$itemId'= id ORDER BY '$sort'");
      $item = $getItem->fetch();

      $price = 0;
      $type = "";
      if($qty["action"] == "Sold")
        $price = $qty['list'];
      else if($qty["action"] == "Bought")
        $price = $qty['std'];

      $date = date('m-d-Y', strtotime($qty['date']));
      echo("<tr><td  width='5px' align='center'>
      <input type='radio' name='sel' value= ".$item['id']."></td>");

      $issued = "";
      if($qty['issued'] == "TRUE") $issued="checked='checked'";
      $sold = "";
      if($qty['sold'] == "TRUE") $sold="checked='checked'";

      echo("<td>".$item['description1']."</td>");
      echo("<td>".$date."</td>");
      echo("<td align='center'> <input style='transform: scale(2);' onChange=issueAndSell('issue','".$qty['id']."','".$qty['issued']."'); type='checkbox' ".$issued.">");
      echo("<td align='center'> <input  style='transform: scale(2);' onChange=issueAndSell('sell','".$qty['id']."','".$qty['sold']."');  type='checkbox' ".$sold.">");
      echo("<td>".$titles[$qty['month']]."</td>");
      echo("<td align='center'>".$qty['action']. $type."</td>");
      echo("<td align='center'>$".$price."</td>");
      echo("<td align='center'>".$qty['previousQty']."</td>");
      echo("<td align='center'>".$qty['amount']."</td>");
      echo("<td align='center'>".$qty['newQty']."</td>");
      echo("<td>".$qty['department']."</td>");
      echo("<td align='center'><button type='button' id='delete".$qty['id']."' value='".$qty['id']."' onClick=deleteQty($('#delete".$qty['id']."').val());>Delete</button></td></tr>");

    }
  }

  function delete(){
    $id = $_POST['logId'];
    load("DELETE FROM `qtychanges` WHERE id = '$id'");
    echo("Log deleted successfully.");
  }

  function viewLog($titles){
    $itemId = $_POST['itemId'];
    $qtychanges = load("SELECT * FROM qtychanges WHERE itemId = $itemId");

    echo("<table><tr><th>Date</th> <th>Issued</th> <th>Sold</th> <th>Month</th> <th>Cost</th> <th>Previous Qty</th>
    <th>Amount</th> <th>Action</th> <th>New Qty</th> <th>Department</th> <th>Comments</th></tr>");

    foreach($qtychanges as $qty){
      $issued = "";
      if($qty['issued'] == "TRUE") $issued="checked='checked'";
      $sold = "";
      if($qty['sold'] == "TRUE") $sold="checked='checked'";

      $date = date('l, F d, Y', strtotime($qty['date']));
      echo("<tr><td>".$date."</td>");
      echo("<td align='center'> <input style='transform: scale(1.2);' onChange=issueAndSell('issue','".$qty['id']."','".$qty['issued']."'); type='checkbox' ".$issued.">");
      echo("<td align='center'> <input  style='transform: scale(1.2);' onChange=issueAndSell('sell','".$qty['id']."','".$qty['sold']."');  type='checkbox' ".$sold.">");
      echo("<td>".$titles[$qty['month']]."</td>");
      if($qty['action'] == "Bought")
        $cost = $qty['std'];
      elseif($qty['action'] == "Sold")
        $cost = $qty['list'];
      echo("<td align='center'>$".$cost."</td>");
      echo("<td align='center'>".$qty['previousQty']."</td>");
      echo("<td align='center'>".$qty['amount']."</td>");
      echo("<td align='center'>".$qty['action']."</td>");
      echo("<td align='center'>".$qty['newQty']."</td>");
      echo("<td align='center'>".$qty['department']."</td>");
      echo("<td>".$qty['comments']."</td></tr>");
    }
    echo("</table>");
  }

  function logQtyChange($log, $months){
    $date = $log['date'];
    $month = $log['month'];
    $qtyAction = $log['action'];
    $amount = $log['amount'];
    $department = $log['department'];
    $comments = $log['comments'];
    $itemId = $log['itemId'];
    $item = load("SELECT * FROM items WHERE id = '$itemId'");
    $item = $item -> fetch();
    $previousQty = $item['qty'];
    $newQty;

    if($qtyAction == 'Set To')
      $newQty = $amount;
    elseif($qtyAction == 'Sold')
      $newQty = $previousQty - $amount;
    elseif($qtyAction == 'Bought')
      $newQty = $previousQty + $amount;

    $list = $amount * ($item[$months[$month]."List"]);
    $std = $amount * ($item[$months[$month]."Std"]);

    load("INSERT INTO qtychanges (date, month, department, action, previousQty, amount, newQty, list, std, comments, itemId)
      VALUES ('$date', '$month', '$department', '$qtyAction', '$previousQty', '$amount', '$newQty', '$list', '$std', '$comments', $itemId)");

    load("UPDATE items SET qty = '$newQty' WHERE id = '$itemId'");
    echo("Quantity change logged successfully.");

  }

  function getCost($months){
    $itemId = $_POST['itemId'];
    $item = load("SELECT * FROM items WHERE id = '$itemId'");
    $item = $item -> fetch();

    $amount = $_POST['amount'];
    if($amount == ""){
      $amount = 0;}

    $listPrice = $months[$_POST['month']] ."List";
    $listPrice = $item[$listPrice] * $amount;

    $stdPrice = $months[$_POST['month']] ."Std";
    $stdPrice = $item[$stdPrice] * $amount;
    if($_POST["qtyAction"] == "Bought")
      echo("STD: $".$stdPrice);
    else if($_POST["qtyAction"] == "Sold")
      echo("List: $".$listPrice);
  }


  function issueAndSell(){
    $type = $_POST['type'];
    $id = $_POST['id'];
    $bool = $_POST['bool'];

    $stmt = "";
    if($type == 'issue')
      $stmt = "UPDATE `qtychanges` SET issued = '$bool' WHERE id='$id'";
    elseif($type == 'sell')
      $stmt = "UPDATE `qtychanges` SET sold = '$bool' WHERE id='$id'";

    load($stmt);
    echo("Log updated successfully");
  }

 ?>
