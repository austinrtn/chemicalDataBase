
<?php require("../conn.php");
$month = $_REQUEST['month'];

if(isset($_REQUEST['action'])){
  if($_REQUEST['action'] == "main")
    departmentChart($month);
  elseif($_REQUEST['action'] == "selectDepartment")
    selectDepartment($month);
  elseif($_REQUEST['action'] == "getDepartments")
    $departments = getDepartments(true, $month);
  elseif($_REQUEST['action'] == "monthlyReport")
    monthlyReport($month);
  }

function departmentChart($month){
  $totalBought = 0;
  $totalSold = 0;
  $departments = getDepartments(false, $month);
  $selDepartment = null;

  echo("<table class='mainTable'> <th width='10'>View Log</th> <th>Department</th> <th>Amount Bought</th> <th>Amount Sold</th>");

  for ($i=0; $i < count($departments) ; $i++) {
    $selDepartment = $departments[$i];
    $bought = 0;
    $sold = 0;

    $logs = load("SELECT * FROM `qtychanges` WHERE department ='$selDepartment' AND month='$month'");
    foreach ($logs as $log) {
      if($log['action'] == "Bought"){
        $bought += $log['std'];
        $totalBought += $log['std'];
      }
      elseif($log['action' == "Sold"]){
        $sold += $log['list'];
        $totalSold += $log['list'];
      }
    }
    $dept = str_replace(" ", "!~!", $departments[$i]);
    echo("<tr><td style='text-align: center'><button type='button' onclick=selectDepartment('".$dept."')>Select</button><td>".$departments[$i]."</td><td align='center'>$".$bought."</td><td align='center'>$".$sold."</td>");
    }
  echo("<tr><td> <form action='php/monthlyReport.php'><input type='hidden' name='month' value=".$month."><button>Monthly Report</button></form></td><td align='center'>TOTAL</td><td align='center'>$".$totalBought."</td><td align='center'>$".$totalSold."</td></table>");
  }

function selectDepartment($month){
  $boughtTable = ("<table width='500px'> <th>Description</th> <th>Amount Bought</th> <th>Qty</th> <th>View Log</th></tr>");
  $soldTable = ("<table width='500px'> <th>Description</th> <th>Amount Sold</th> <th>Qty</th> <th>View Log</th></tr>");
  $department = $_POST['department'];
  $department = str_replace("!~!", " ", $department);

  $usedItemIds = [];
  $totalQtyBought = 0;
  $totalQtySold = 0;
  $std = 0;
  $list = 0;
  $logs = load("SELECT * FROM `qtychanges` WHERE department= '$department' AND month = '$month' ORDER BY `itemId`");

  foreach ($logs as $log) {
    if(in_array($log['itemId'], $usedItemIds) == false){
      $itemStd = 0;
      $itemList = 0;
      $amountBought = 0;
      $amountSold = 0;

      $itemId = $log['itemId'];
      $item = load("SELECT * FROM `items` WHERE id='$itemId'");
      $item = $item -> fetch();

      $itemLogs = load("SELECT * FROM `qtychanges` WHERE itemId='$itemId' AND department='$department' AND month='$month';");
      foreach ($itemLogs as $itemLog) {
        if($itemLog['action'] == "Bought"){
          $std += $itemLog['std'];
          $itemStd += $itemLog['std'];
          $amountBought+= $itemLog['amount'];
          $totalQtyBought += $itemLog['amount'];
        }
        elseif($itemLog['action'] == "Sold"){
          $list += $itemLog['list'];
          $itemList += $itemLog['list'];
          $amountSold+= $itemLog['amount'];
          $totalQtySold += $itemLog['amount'];
        }
      }

      if($itemStd > 0)
        $boughtTable .= "<tr><td>".$item['description1']."</td><td  align='center'>$".$itemStd."</td><td>".$amountBought."</td> <td align='center'><button onclick='viewLog(".$itemId.")'>Select</tr>";
      if($itemList > 0)
        $soldTable .= "<tr><td>".$item['description1']."</td><td  align='center'>$".$itemList."</td><td>".$amountSold."</td> <td align='center'><button onclick='viewLog(".$itemId.")'>Select</tr>";
    array_push($usedItemIds, $itemId);
    }
  }
  $boughtTable .= "<tr bgcolor='##03fc24'><td>Total</td><td  align='center'>$".$std."</td><td>".$totalQtyBought."</td><td></td></tr></table>";
  $soldTable .= "<tr bgcolor='##03fc24'><td>Total</td><td  align='center'>$".$list."</td><td>".$totalQtySold."</td><td></td></tr></table>";
  echo($boughtTable ."<br><br>" . $soldTable);
}

function getDepartments($doEcho, $month){
  $departments = [];
  $selDepartment = null;

  $logs = ("SELECT * FROM `qtychanges` ");
  if($month != "full")
    $logs = $logs . "WHERE month=$month";
  $logs .= " ORDER BY department";
  $logs = load($logs);
  foreach ($logs as $log) {
    if(in_array($log['department'], $departments) == false && $log['department'] != ""){
      $selDepartment = $log['department'];
      array_push($departments, $selDepartment);
    }
  }
  if($doEcho)
    echo(json_encode($departments));
  else
    return $departments;
}

function monthlyReport($month){
  echo("<button>Go Back</button>");
}
?>
