<?php require('../conn.php');

//Dev Notes


  if($_POST["action"] == "check")
    check();
  elseif($_POST["action" == "update"])
    update();

  function check(){
    $update_stmt =  'ALTER TABLE items DROP COLUMN costcenter; ALTER TABLE items ADD costcenter bigint; ALTER TABLE `qtychanges` ADD `department` TEXT NOT NULL AFTER `amount`; LTER TABLE `items` ADD `min` INT NOT NULL AFTER `qty`, ADD `max` INT NOT NULL AFTER `min`; ALTER TABLE `qtychanges` ADD `issued` TEXT NOT NULL AFTER `date`, ADD `sold` TEXT NOT NULL AFTER `issued`; ALTER TABLE `items` ADD `category` TEXT NOT NULL AFTER `description2`;';    //LEAVE NULL IF NO SQL UPDATES
    $description = `<ul><li>Cost Center Improvments</li><li>Removed the Cost Table</li> <li>You are now able to sort the Qty Change log</li><li>Added Issued and Sold Columns to qty change logs</li>
                    <li>Searching is no longer case sensitive and you can search for multiple items</li><Category property added></li><li>Main Table is now refered to the AS 400 table</li><li>QTY Errors table: This table allows you to view items that have a quantity that is under/over the min/max</li><li>The options button has now become a drop down menu</li>
                    <li>New Default Month option added</li> <li>New Department Spending table added</li> <li>More minor changes</li></ul>`;

    $show_in_changelog = "FALSE";
    $date = date("Y/m/d");
    $currentVersion = $_POST["version"];

    $oldVersion = load("SELECT * FROM `versions` ORDER BY `id` DESC");
    $oldVersion = $oldVersion->fetch();

    if($oldVersion['versionId'] != $currentVersion){
      load("INSERT INTO `versions` (`description`, `show_in_changelog`, `versionId`, `date`, `id`) VALUES ('$description', '$show_in_changelog', '$currentVersion', '$date', NULL)");
      if($update_stmt != null)
        load($update_stmt);
        echo("Your software has been updated! Check the changelog for all the updates made.  If there are any errors, try refreshing the page.  Your options may have been reset.");
    }
  }
 ?>
