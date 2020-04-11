<!DOCTYPE html>
<html>
<!--
AUTHOR:
  AUSTIN KOLONGOWSKI
DESCIRPTION:
  This program was desingned for a chemical manager who wanted a program that would
  be able to keep track of their inventory stock, prices, and orders.  This program
  utilizes AJAX Requests to manipulate the database-->

<script>
  function selectMonths(){
    var string =
    `<option value=0>BaseLine</option>
    <option value=1>Janurary</option>
    <option value=2>Feburary</option>
    <option value=3>March</option>z
    <option value=4>April</option>
    <option value=5>May</option>
    <option value=6>June</option>
    <option value=7>July</option>
    <option value=8>August</option>
    <option value=9>September</option>
    <option value=10>October</option>
    <option value=11>November</option>
    <option value=12>December</option>`;
    return string;
  }
</script>

<head>
  <link rel="stylesheet" href="./styles-2.1.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
  <script src="./js/options-2.3.js"></script>

  <title>Database</title>
  <meta charset="UTF-8" />

  <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
  <script src="./js/qtyChanges-3.4.js"></script>
  <script src="./js/addItem-2.7.js"></script>
  <script src="./js/editItem-3.8.js"></script>
  <script src="./js/priceChanges-2.2.js"></script>
  <script src="./js/loadTable-5.2.js"></script>

</head>

<body>
<strong>Sort/Search By:</strong>
<select id='sort' onchange="changeTable();">
</select> <script>itemsSortChange();</script>

<button type="button" style="height:20px"; id='sortOrder' onclick='sortOrder();'>ASC</button>
<br>

    <div align='right'>
    <select id="month" name='month' onchange="changeTable();">
        <script>
          $("#month").append(selectMonths());
          $("#month").append("<option value='full'>Full</option>");
          $("#month").val(getDefaultMonth());
        </script>
      </select>
    <select align='center' id='tableSelect' onChange='changeTable();'>
      <option value='main'>AS 400</option>
      <option value='bulkAddItems'>Bulk Add Items</option>
      <option value="qtyErrors">QTY Errors</option>
      <option value="department">Department Spending</option>
      <option value='costs'>Orders and Issues</option>
      <option value='priceChanges'>Price Changes</option>
    </select></div>

    <strong>Search: </strong>  <input type="text" id="search" onkeypress="return enter(event);">
    <button type="button" onClick=changeTable('search');>Search</button> <button type="button" onClick='changeTable();'>Reset</button>

    <br><br>

    <div id="table" class="scroll"> <script> changeTable(); </script></div><br>
    <div id='buttons'>

    </div>

    <div id="itemDiv"></div><br>
    <div id="edit"></div><br>

<h6> <div id='versionTag'>Version: 1.1</div>

</div> </h6>

</body>
</html>
