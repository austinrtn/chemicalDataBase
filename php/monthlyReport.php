<?php

class  departmentObj{
  private $departmentName = null;
  private $addToList = false;

  private $cc1 = []; //7151300017
  private $cc2 = []; //7161400013
  private $cc3 = []; //7161400016
  private $cc4 = []; //7320200001
  function getAllCcs(){
    return  [$this->cc1, $this->cc2, $this->cc3, $this->cc4];
  }

  function __construct($departmentName) {
    $this->departmentName = $departmentName;
  }

  function getDepartmentName(){
    return $this->departmentName;
  }

  function getAddToLIst(){
    return $this->addToList;
  }

  function setAddToList($bool){
    $this->addToList = $bool;
  }

  function pushCc1($item){
    array_push($this->cc1, $item);
  }

  function getCc1(){
    return $this->cc1;
  }

  function pushCc2($item){
    array_push($this->cc2, $item);
  }

  function getCc2(){
    return $this->cc2;
  }

  function pushCc3($item){
    array_push($this->cc3, $item);
  }

  function getCc3(){
    return $this->cc3;
  }

  function pushCc4($item){
    array_push($this->cc4, $item);
  }

  function getCc4(){
    return $this->cc4;
  }


}
 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../styles-2.1.css">
    <title></title>
  </head>
  <body>
    <?php require("./department.php");
      $month = $_REQUEST['month'];
      $departmentObjs = [];
      $departments = getDepartments(false, $month);

      for ($i=0; $i < count($departments); $i++){
          $department = $departments[$i];
          $selDepartment = new departmentObj($department);

          $usedItemIds = [];

          $stmt = ("SELECT * FROM `qtychanges` WHERE department = '$department' AND action='Sold'");
          $logs = load($stmt);

          foreach ($logs as $log) {
            if($log['action'] == "Sold"){

              $itemId = $log['itemId'];
              $selDepartment->setAddToList(true);

              if(in_array($itemId, $usedItemIds) == false){
                $itemLogs = load($stmt . " AND itemId = '$itemId'");

                $amount = 0;
                $costs = 0;

                foreach ($itemLogs as $itemLog) {
                    $amount += $itemLog['amount'];
                    $costs += $itemLog['list'];
                  }

                  $item = load("SELECT * FROM `items` WHERE id='$itemId'")->fetch();
                  $itemAr = ["item"=>$item, "amount"=>$amount, "costs"=>$costs, "dept"=>$department];

                  if($item['costcenter'] == 7151300017)
                    $selDepartment->pushCc1($itemAr);
                  if($item['costcenter'] == 7161400013)
                    $selDepartment->pushCc2($itemAr);
                  if($item['costcenter'] == 7161400016)
                    $selDepartment->pushCc3($itemAr);
                  if($item['costcenter'] == 7320200001)
                    $selDepartment->pushCc4($itemAr);

                  array_push($usedItemIds, $itemId);}

              }
            }
            if($selDepartment->getAddToList() == true)
              array_push($departmentObjs, $selDepartment);
          }

      echo("<table class='mainTable'><tr> <td>Item #</td> <td>Discription</td> <td>Dept</td> <td>UM</td> <td>".$titles[$month]."</td> <td>Price</td> <td>Actual</td> <td>Sub-Total</td> <td>Sub Department</td></tr>");

      foreach($departmentObjs as $departmentObj){
        foreach($departmentObj->getAllCcs() as $cc){
          $costs = 0;
          foreach($cc as $itemAr){
            $endTr = false;
            $lastCc = end($cc);
            $costs += $itemAr['costs'];
            if($itemAr == $lastCc)
              $endTr = true;
            printMRItem($itemAr, $endTr, $costs);

          }
        }
        echo("<tr>");

        for ($i=0; $i < 9; $i++) {
          if($i ==6 || $i == 7) echo("<td bgcolor='#fff000'>&nbsp;</td>");
          else echo("<td>&nbsp;</td>");
        }
        echo("</tr>");
      }
      echo("</table>");
     ?>
     <br>
     <form action="../index.php" method="post">
       <button name="button">Go Back</button>
     </form><br><br>
  </body>
</html>

<?php


  function printMRItem($itemAr, $endTr, $costs){
    global $months, $month;
    $item = $itemAr['item'];
    echo("<tr><td>".$item['rmNum']."</td>");
    echo("<td>".$item['description1']."</td>");
    echo("<td>".$itemAr['dept']."</td>");
    echo("<td>".$item['um']."</td>");
    echo("<td>".$itemAr['amount']."</td>");
    echo("<td>$".$item[$months[$month]."List"]."</td>");
    echo("<td align='center' bgcolor='#fff000'>$".$itemAr['costs']."</td>");
    if($endTr) echo("<td align='center' bgcolor='#fff000'>$".$costs."</td>");
    else echo("<td bgcolor='#fff000'></td>");
    echo("<td>".$item['costcenter']."</td></tr>");
  }



 ?>
