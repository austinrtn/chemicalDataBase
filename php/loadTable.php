<?php require('../conn.php');
$tableType = $_POST['action'];
$display = json_decode($_POST['options'], true);
$colTitles = array_keys($display);
$numOfMonths = 13;
$colsBeforePrices = sizeof($display)+1;
$selMonth = getSelMonth();

echo("<table class='mainTable'>");
echo("<th>Edit</th>");

foreach($colTitles as $tc){
  if($display[$tc] == true)
    echo("<th>".$tc."</td>");
  else
    $colsBeforePrices--;
}

  $items = loadItems();
  if($tableType=="main"){
    loadMain($selMonth['title'], $colsBeforePrices);
  }
  elseif($tableType=="complete"){
    loadComplete($numOfMonths, $colsBeforePrices, $titles);
  }
  elseif($tableType=="qtyErrors"){
    loadMain($selMonth['title'], $colsBeforePrices);
    $sort = $_POST['sortType'];
    $ascDesc = $_POST['ascDesc'];
    $items = load("SELECT * FROM `items` WHERE qty < min  OR qty > max ORDER BY $sort $ascDesc;");
  }


  $list = $selMonth['month'] . "List";
  $std = $selMonth['month'] . "Std";


 foreach($items as $item){
   echo("<tr id= ".$item['id'] . " onclick='highlight(".$item['id'].");'>");
   echo("<td  width='5px' align='center'>
   <input type='radio' id='sel' name='sel' value= ".$item['id']."></td>");

  if($display['Description 1'])
    echo("<td>" .$item['description1']. "</td>");
  if($display['Description 2'])
   echo("<td>" .$item['description2']. "</td>");
  if($display['Category'])
    echo("<td>" .$item['category']. "</td>");
  if($display['RM Number'])
   echo("<td>" .$item['rmNum']. "</td>");
  if($display['Vendor Id'])
   echo("<td>" .$item['vendorId']. "</td>");
  if($display['Vendor Name'])
   echo("<td>" .$item['vendorName']. "</td>");
  if($display['U/M'])
   echo("<td>" .$item['um']. "</td>");
  if($display['QTY'])
   echo("<td align='center'>" .$item['qty']. "</td>");
  if($display['Min'])
    echo("<td align='center'>" .$item['min']. "</td>");
  if($display['Max'])
     echo("<td align='center'>" .$item['max']. "</td>");
  if($display['Cost Center'])
   echo("<td align='center'>" .$item['costcenter']. "</td>");

   if($tableType=='main' || $tableType=="qtyErrors"){
    echo("<td align='center' class='lightBorder'>" .$item[$list]. "</td> <td align='center'>" .$item[$std]. "</td></tr>");
  }

  elseif($tableType=='complete'){
    for ($i=0; $i < 13; $i++) {
      $list = $months[$i] . "List";
      $std = $months[$i] . "Std";
      echo("<td align='center' class='lightBorder'>" .$item[$list]. "</td>
      <td align='center'>" .$item[$std]. "</td>");
    }
    echo("</tr>");
  }
}


//////////////////////////////////////////////////////////////////


function loadMain($selMonth, $cols){
  echo("<th colspan='2' text-align='center'>".$selMonth."</th></tr><tr>");

  for ($i=0; $i < $cols; $i++) {
    echo("<td></td>");
  }

  echo("<th align='center'>List</th><th align='center'>STD</th></tr>");
}

function loadComplete($numOfMonths, $cols, $titles){
  for ($i=0; $i < $numOfMonths; $i++) {
    echo("<th colspan='2'>".$titles[$i]."</th>");
  }
  echo("</tr><tr>");

 for($i = 0; $i < $cols; $i++){
   echo("<td></td>");
 }

 for($i = 0; $i < $numOfMonths; $i++){
   echo("<th align='center'>List</th>");
   echo("<th align='center'>Std</th>");
 }
}

 function getSelMonth(){
   $month ='';
   $title = '';

   switch ($_POST['month']) {
      case 0:
        $month = "baseline";
        $title = "Baseline";
        break;
      case 1:
        $month = "jan";
        $title = "Janurary";
        break;
      case 2:
        $month = "feb";
        $title = "Feburary";
        break;
      case 3:
        $month = "mar";
        $title = "March";
        break;
      case 4:
        $month = "april";
        $title = "April";
        break;
      case 5:
        $month = "may";
        $title = "May";
        break;
      case 6:
        $month = "june";
        $title = "June";
        break;
      case 7:
        $month = "july";
        $title = "July";
        break;
      case 8:
        $month = "aug";
        $title = "August";
        break;
      case 9:
        $month = "sep";
        $title = "September";
        break;
      case 10:
        $month = "oct";
        $title = "October";
        break;
      case 11:
        $month = "nov";
        $title = "November";
        break;
      case 12:
        $month = "dec";
        $title = "December";
        break;
  }
  $monthInfo = array('month'=>$month, 'title'=>$title);
  return $monthInfo;
 }
?>
