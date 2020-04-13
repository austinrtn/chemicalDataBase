<!DOCTYPE html>
<html lang="en" dir="ltr">
<!-- INSTRUCTIONS ARE STILL BEING ADDED -->
  <head>
    <meta charset="utf-8">
    <style media="screen">
      html{
        scroll-behavior: smooth
      }
      div.instructions{
        font-size: large;
      }
      p{
        max-width: 400px;
      }
    </style>
    <title>Instructions</title>
  </head>
  <body>
    <div class="instructions">
    <u><h1>Instruction Manual</h1></u>

    <h2>Table of Contents</h2>

    <a href="index.php">Back to program</a>

    <ul><li><a href="#mainTable">AS 400 Table</a></li>
      <ul><li><a href="#sorting">Sorting</a></li>
      <li><a href="#searching">Searching</a></li>
      <li><a href="#selecting">Selecting</a></li>
      <li><a href="#displayOptions">Display Options</a></li></ul>
    <li><a href="#items">Items</a></li>
      <ul><li><a href="#adding">Adding Items</a></li>
      <li><a href="#editing">Editing/Deleting Items</a></li></li></ul><br><br>

    <h2 id='mainTable'><u>AS 400 Table</u></h2> <p>When you first open the program, you will be presented with the "AS 400 Table."  In this table you will find all of your "items" along with their "properties".  Using this table, you will be able to view and make changes to your item database.</p>
    <h3  id='sorting'>Sorting</h3> <p>You can sort how the items are listed in the table using the "Sort" drop down menu at the top left of the page.  By default, items are sorted by "Description 1", you can change this by clicking the Sort drop down menu and selecting which property you would like to sort by.</p>
    <h3  id='searching'>Searching</h3> <p>You can search for items using the search bar in the top left corner of the page.  You can search for an item using any property.  By default, items are searched by "Description 1", but you can change this by choosing a different property in the Sort drop down menu.  When you search for an item, the items table will display all the items that contain the string you searched for.  If you want the reset the table to show all items, simply click the
                                          reset </p>
    <h3 id='selecting'>Selecting</h3> <p>In order to select an item, you must click on the item's check bubble in the "Edit" column.  Once selected the row will become highlighted and you can click the "Select" button underneath the Items table to edit the item's properties, as well as edit it's prices and quantity.  You can highlight the item just by clicking on it's row; however, you will not be able to edit the item unless the check bubble is checked.</p>
    <h3 id='displayOptions'>Display Options</h3> <p>The display options allow to select which columns you would like to view.  Click on the options button and check which properties you would like to see on the table and click "Submit Changes".  You can click "Show All" to show all the properties.</p>
    <br><br>
    <h2 id='items'><u>Items</u></h2> <p>Items are what populate the Main table.  Each item has the same "properties" (Description, RM Number, ect).  Items can be added, edited and deleted at your convience.</p>
    <h3 id='adding'>Adding Items</h3> <p>Adding items to your database is simple.  Click on the "Add Item" button below the items table.  Here you can fill out all the properties of the new item.  Any properties that are not filled out will be left blank on the table.  Click the new "Add Item" button and the item will be added, followed by a message letting the user know that the item has been added succsessfully.</p>
    <h3 id='editing'>Editing/Deleting Items</h3> <p>Editing items is made easy.  Select the item and click the Select button.  Here, you can changed the properties of the selected item by editing the properties in the table and clicking Submit Changes.  If you want to delete an item, simply select the item and click the Delete button</p>
    </div>
  </body>
</html>
