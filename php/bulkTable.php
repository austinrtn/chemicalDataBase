<?php require("../conn.php");

  $action = $_POST['action'];
  if($action == "load") loadTable();
  elseif($action == "submit") submit();

function loadTable(){
  $table = "<table id='mainTable' class='mainTable'>
    <th>Desc 1</th>
    <th>Desc 2</th>
    <th>Category</th>
    <th>RM Number</th>
    <th>Vendor ID</th>
    <th>Vendor Name</th>
    <th>U/M</th>
    <th>QTY</th>
    <th>Min</th>
    <th>Max</th>
    <th>LIST</th>
    <th>STD</th>
    <th>Cost Center</th>
    </table>";
  echo($table);
}

function submit(){
  global $months;
  $data = $_POST['data'];
  //INDEXES
  $description1 = 0;
  $description2 = 1;
  $category = 2;
  $rmNumber = 3;
  $vendorId = 4;
  $vendorName = 5;
  $um = 6;
  $qty = 7;
  $min = 8;
  $max = 9;
  $list = 10;
  $std = 11;
  $costCenter = 12;

  foreach($data as $d){
  
    load("INSERT INTO items (description1, description2, category, rmNum, vendorId, vendorName, um, qty, min, max, costcenter)
    VALUES ('$d[$description1]', '$d[$description2]', '$d[$category]', '$d[$rmNumber]', '$d[$vendorId]', '$d[$vendorName]', '$d[$um]', '$d[$qty]', '$d[$min]', '$d[$max]',
      '$d[$costCenter]')");

    $stmt = "UPDATE items SET ";

    foreach($months as $month){
      $stmt .= $month . "Std = " . $d[$std] . ", ";
      $stmt .= $month . "List = " . $d[$list];
      if($month != "dec") $stmt .= ", ";
    }
    $stmt .= " WHERE rmNum = '$d[$rmNumber]'";
    load($stmt);
  }
  echo("Items have been added successfully");
}
 ?>
