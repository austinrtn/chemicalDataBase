const costCenterNums = "<option>7151300017</option> <option>7161400013</option> <option>7161400016</option> <option>7320200001</option>";
//https://files.dreamhost.com/#/c/_/_/eyJ0IjoiZnRwIiwiYyI6eyJ2IjoxfX0%3D
function getAddElements(){
  // Gets all the elements needed to add a new item to the inventory.items
  var addItem = {description1: $("#addDescription1").val(),
    description2: $("#addDescription2").val(),
    category: $("#addCategory").val(),
    rmNum: $("#addRmNum").val(),
    vendorId: $("#addVendorId").val(),
    vendorName: $("#addVendorName").val(),
    um: $("#addUm").val(),
    qty: $("#addQty").val(),
    min: $("#addMin").val(),
    max: $("#addMax").val(),
    costcenter: $("#addCostCenter").val(),
    list: $("#addList").val(),
    std: $("#addStd").val()
  };
  return addItem;
}


function addItemBox(type, id, placeholder){
  this.type = type;
  this.id = id;
  this.placeholder = placeholder;
}

/* This array is used to generate html elements based off the rows of the inventory.items table
The values of these elements then are added to the database as a new item*/
var addItemAr = [new addItemBox("text","addDescription1","Description1"), new addItemBox("text","addDescription2","Description2"), new addItemBox("text", "addCategory", "Category"),
new addItemBox("text","addRmNum","RM Number"), new addItemBox("number","addVendorId","Vendor ID"), new addItemBox("text","addVendorName","Vendor Name"),
new addItemBox("text","addUm","UM"), new addItemBox("number","addQty","QTY"), new addItemBox("number", "addMin", "Min"), new addItemBox("number", "addMax", "Max"), new addItemBox("costCenter", "addCostCenter", null),
new addItemBox("number", "addList", "List Price"), new addItemBox("number", "addStd", "STD Price") ];

// Generates the HTML elememnts to add item
function addItem(){
  buttonScheme = "addItem";
  clearDivs();

  var table = document.getElementById("mainTable");
  var row = table.insertRow(2);
  row.insertCell(0);

  for(var i = 0; i < addItemAr.length; i++){
    var cell = row.insertCell(i+1);
    if(addItemAr[i].id == "addCostCenter"){
      costCenterIndex = i;
      cell.innerHTML = "<div class='bulkAdd'><select id='addCostCenter'> "+costCenterNums+" </select></div>";
    }
    else cell.innerHTML = "<div class='bulkAdd'><input type='"+addItemAr[i].type+"' id='"+addItemAr[i].id+"' placeholder="+addItemAr[i].placeholder+"></div>";
  }

  table.insertRow(3).insertCell(0); // BLANK CELL FOR SPACE
}

function submitItem(){
  $.ajax({
    url: 'php/selectItem.php',
    type: "POST",
    data: ({item: getAddElements(), action: 'add'}),
    success: function (data) {
      changeTable("main");
      alert(data);
    }
  });
}

/***************************
******** BULK ADD **********
***************************/

const numOfCols = 12;
const numOfRows = 12; // Number of rows to be filled out
var inputIds = [];

function loadBulkTable(){
  inputIds = [];

  var table = document.getElementById("mainTable");

  for(var i = 1; i < numOfRows; i++){
    var rowIds = [];
    var row = table.insertRow(i);

    for(var x = 0; x < numOfCols; x++){
      var cell = row.insertCell(x);
      var id = "row"+ i +"-col" + x;
      rowIds.push(id);
      cell.innerHTML = "<div class='bulkAdd'><input id='"+id+"' type='text'></div>";
    }

    var costCenter = row.insertCell(x);
    var id = "row" + i +"-col"+numOfRows;
    rowIds.push(id);
    costCenter.innerHTML = "<div class='bulkAdd'><select id="+id+">"+costCenterNums+"</select></div>";

    inputIds.push(rowIds)
  }
}

function submitBulkData(){
  var data = getBulkData();
  if(data == null) alert("The table is missing information!  Please make sure each item as an RM Number.");

  else{
    $.ajax({
      url: "php/bulkTable.php",
      type: "POST",
      data:({action:"submit", data: data}),
      success: function(output){
        alert(output);
        changeTable('main');
      }
    });
  }
}

function getBulkData(){
  var data = [];

  for(var row of inputIds){
    if($("#"+row[3]).val() == "") continue;

    else{
      var cols = [];

      for(var column of row){
        var value = $("#"+column).val();
        if(value == "") value = null;
        cols.push(value);
      }
      data.push(cols);
    }
  }
  if(data.length == 0) return null;
  else return data;
}
