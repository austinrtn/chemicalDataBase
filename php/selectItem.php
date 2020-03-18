<?php require('../conn.php');

if($_POST['action'] == "select"){
  selectItem();
} elseif($_POST['action'] == "edit"){
  editItem();
} elseif($_POST['action'] == "delete"){
  deleteItem();
}elseif($_POST['action'] == "add"){
  addItem();
}

function selectItem(){
  $id = $_POST['id'];
  $item = load("SELECT * FROM items WHERE id='$id'");
  $selItem = $item -> fetch();

    $itemVals1 = array("description1" => $selItem["description1"], "description2" => $selItem["description2"], "category" => $selItem["category"], "rmNum" => $selItem["rmNum"], "vendorId" => $selItem["vendorId"], "vendorName" => $selItem["vendorName"]);
    $itemVals2 = array("um" => $selItem["um"], "qty" => $selItem["qty"], "min" => $selItem["min"], "max" => $selItem["max"], "costcenter" => $selItem["costcenter"]);
    $itemVals = array_merge($itemVals1,$itemVals2);
    echo json_encode($itemVals);
}

function editItem(){
  $item = $_POST['item'];

  $description1 = $item['description1'];
  $description2 = $item['description2'];
  $category = $item['category'];
  $rmNum = $item['rmNum'];
  $vendorId = $item['vendorId'];
  $vendorName = $item['vendorName'];
  $um = $item['um'];
  $qty = $item['qty'];
  $min = $item['min'];
  $max = $item['max'];
  $costcenter = $item['costcenter'];
  $id = $item['id'];

  $sql = "UPDATE items SET
  description1 = '$description1', description2 = '$description2', category = '$category',
  rmNum= '$rmNum', vendorId= '$vendorId', vendorName='$vendorName', um='$um', qty='$qty', min='$min', max='$max', costcenter = '$costcenter' WHERE id = '$id'";

  load($sql);
  echo("Item updated successfully.");
}

function deleteItem(){
  $item = $_POST['item'];
  $id = $item['id'];

  $sql = "DELETE FROM items WHERE id = '$id'";
  load($sql);
}

function addItem(){
  $item = $_POST['item'];

  $description1 = $item['description1'];
  $description2 = $item['description2'];
  $category = $item['category'];
  $rmNum = $item['rmNum'];
  $vendorId = $item['vendorId'];
  $vendorName = $item['vendorName'];
  $um = $item['um'];
  $qty = $item['qty'];
  $min = $item['min'];
  $max = $item['max'];
  $costcenter = $item['costcenter'];


  $items = load("SELECT * FROM `items` WHERE `rmNum` = '$rmNum'");

  if ($items->rowCount() == 0){
    $sql = "INSERT INTO items (description1, description2, category, rmNum, vendorId, vendorName, um, qty, min, max, costcenter)
    VALUES ('$description1', '$description2', '$category', '$rmNum', '$vendorId', '$vendorName', '$um', '$qty', '$min', '$max', '$costcenter')";
    load($sql);
    echo("Item added successfully.");
  }
  else
    echo("This RM number already exsists.  The item has not been added");
}
?>
