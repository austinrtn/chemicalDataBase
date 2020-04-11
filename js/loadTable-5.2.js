var tableUrl = 'php/loadTable.php';
var buttonScheme = "default";
var action = 'main';
var orderSort = "ASC";
var search = null;

/* This function is called when the user uses the table select box in the top right corner of the program,
or when an item is searched for.  In each scenerio, the data is recieived via PHP and Ajax, and the data
is sent to the 'table' Div */
function changeTable(select){
  buttonScheme = "default";

  if(select == undefined)
    select = $("#tableSelect").val();

  switch(select){
    case "main":
      if(tableUrl !== 'php/loadTable.php')
        itemsSortChange();
      tableUrl='php/loadTable.php';
      action = 'main';
      break;

    case "bulkAddItems":
      buttonScheme = "bulkAdd";
      action = "load";
      tableUrl = "php/bulkTable.php";
      break;

    case "qtyErrors":
      tableUrl='php/loadTable.php';
      itemsSortChange();
      action = 'qtyErrors';
      break;

    case "department":
      tableUrl = "php/department.php";
      action = "main";
      break;

    case "costs":
      if(tableUrl !== 'php/qtyChanges.php')
        qtySortChange();
      tableUrl='php/qtyChanges.php';
      action = 'table';
      break;

    case "priceChanges":
      tableUrl="php/loadPriceTable.php";
      action = 'priceTable';
      break;

    case "search":
      tableUrl='php/loadTable.php';
      action = 'main';
      search = $("#search").val();
      break;
  }

  loadTable();
}

function loadTable(resetTbl2){
  var sort = $("select#sort").val();
  var month = $("select#month").val();

  if(month === 'full' && tableUrl === 'php/loadTable.php')
    action='complete';
  else if(tableUrl === 'php/loadTable.php' && action !== "qtyErrors")
    action='main';

  $.ajax({
    url: tableUrl,
    type: "POST",
    data: ({action: action, sortType: sort, ascDesc: orderSort, month: month, search: search, options: displayOptions()}),
    success: function (data) {
      clearDivs(resetTbl2);
      document.getElementById('table').innerHTML = data;

      if(tableUrl == "php/bulkTable.php") loadBulkTable();

    }
  });
  search = null;
}

function clearDivs(resetTbl2){
  $("#buttons").html(buttonSchemes[buttonScheme]);


  $("#edit").html("");
  $("#itemDiv").html("");
  $("#priceGraph").html("");
  $("#qtyGraph").html("");
  if(tableUrl !== "php/costTable.php")
    $("#mainTable2").html("");
  if(resetTbl2 === true)
    $("#mainTable2").html(loadQtyTable($("#month").val()));
}

  // Switches the sort order when the #sortOrder button is clicked
function sortOrder(){
  if (orderSort === "ASC"){
    $("#sortOrder").html("DESC");
    orderSort = "DESC";
  } else if(orderSort === "DESC"){
    $("#sortOrder").html("ASC");
    orderSort = "ASC";
  }
  loadTable();
}

//Highlights the selected row yellow in the AS400 table.
function highlight(trId){
  var radio = document.getElementsByName('sel');
  var r = document.getElementById('sel');

  var color;

    for (var i = 0; i < radio.length; i++) {
      if (radio[i].checked || radio[i].value === trId){
        color= "#FFFF00";
        selectItem();
      }
      else if(i%2 === 0)
        color="#ffffff";

      else
        color="#dddddd";
    document.getElementById(radio[i].value).style.background = color;
    }

    if(r.checked === false)
      document.getElementById(trId).style.background = "#FFFF00";
}

/* When the table is chagend, the #sort select box is also changed.*/
function itemsSortChange(){
  $("#sort").html(`<option value='description1'>Description 1</option>
  <option value='description2'>Description 2</option>
  <option value='category'>Category</option>
  <option value='rmNum'>Rm Number</option>
  <option value='vendorId'>Vendor ID</option>
  <option value='vendorName'>Vendor Name</option>
  <option value='um'>U/M</option>
  <option value='costcenter'>Cost Center</option>`);
}

var buttonSchemes = {
  default: "<form style='display: inline;' target='_blank' action='https://github.com/austinrtn/chemicalDatabase/blob/master/CHANGE_LOG.md'> <button type='button' onclick='addItem();'>Add Item</button> <button type='submit' name='button'>ChangeLog</button></form> <form style='display: inline;' action='instructions.html' method='post'>"+
    "<button type='submit' name='button'>Instructions</button> <select id='optionsMenu' onchange='selectOption();'> <option disabled selected hidden>Options</option> <option value='Display'>Display</option> <option value='DefaultMonth'>Default Month</option><option value='copyPrices'>Copy Prices</option><option value='feedback'>Feedback</option></select> </select> </form>",
  addItem: "<button type='button' onclick='submitItem();'>Submit Item</button> <button type=button onClick=changeTable('main');>Cancel</button>",
  bulkAdd: "<button onclick='submitBulkData();'>Add All Items</button>"
}
