// Gets the values form the HTML elements that are used to edit items in the database
function getChangeElements(){
  var editItem = {description1: $("#changeDescription1").val(),
  description2: $("#changeDescription2").val(),
  category: $("#changeCategory").val(),
  rmNum: $("#changeRmNum").val(),
  vendorId: $("#changeVendorId").val(),
  vendorName: $("#changeVendorName").val(),
  um: $("#changeUm").val(),
  qty: $("#changeQty").val(),
  min: $("#changeMin").val(),
  max: $("#changeMax").val(),
  costcenter: $("#costcenter").val(),
  id: $("input[name='sel']:checked").val(),
  action: $("#actions").val()};

  return editItem;
}

/* Generates the HTML elements that are used to edit elements.  (I know its a bet sloppy)*/
function itemBox(item){
  var string =
  "<br><table><tr><th>Description 1</th> <th>Description 2</th> <th>Category</th> <th>RM Number</th> <th>Vendor ID</th> <th>Vendor Name</th> <th>U/M</th> <th>QTY</th> <th>Min</th> <th>Max</th> <th>Cost Center</th></tr>" +
    "<tr><td><input type='text' id='changeDescription1' value='" + item.description1+ "'></td>" +
    "<td><input type='text' id='changeDescription2' value='" + item.description2 + "'></td>"+
    "<td><input type='text' id='changeCategory' value='" + item.category + "'></td>"+
    "<td><input type='text' id='changeRmNum' value='" + item.rmNum + "'></td>" +
    "<td><input type='number' id='changeVendorId' value='" + item.vendorId + "'></td>" +
    "<td><input type='text' id='changeVendorName' value='" + item.vendorName + "'></td>" +
    "<td><input type='text' id='changeUm' value='" + item.um + "'></td>" +
    "<td><input type='number' id='changeQty' value='" + item.qty + "'></td>" +
    "<td><input type='number' id='changeMin' value='" + item.min + "'></td>" +
    "<td><input type='number' id='changeMax' value='" + item.max + "'></td>" +
    "<td><select id='costcenter'> <option>"+item.costcenter+"</option> <option>7151300017</option> <option>7161400013</option> <option>7161400016</option> <option>7320200001</option> </select></td>" +
    "</td></table><br>"
    return string;
}
// Selects the item that is being edited
function selectItem(id){
  if(id == null)
    id = $("input[name='sel']:checked").val();
  if (id){
    $.ajax({
      url: 'php/selectItem.php',
      type: "POST",
      data: ({id: id, action: 'select'}),
      success: function (data) {
      var item = JSON.parse(data);
      document.getElementById('itemDiv').innerHTML = itemBox(item) +

        "<button type='button' onclick='submitChanges();'>Submit Changes</button> " +
        "<button type='button' onclick='deleteItem();'>Delete</button> " +

        "<select id='priceMenu' onChange='priceMenu();'> <option disabled selected hidden>Price Menu</option>" +
        "<option value='viewLog'>View Log</option> <option value='logPriceChange'>Log Price Change</option>" +
        "<option value='editPrices'>Edit Prices</option> </select>" +
        "<select id='qtyMenu' onChange='qtyMenu();'> <option disabled selected hidden>Qty Menu</option><option value='viewLog'>View Log</option><option value=logQtyChange>Log Qty Change</option></select>";

        console.log("Item Id of " + item.description1 + ": " + item.id);
      }
    });
          $("#edit").html("");
  }
  else
    alert("No item selected");
}

//Submits the changes made to the selected item
function submitChanges(){
  $.ajax({
    url: 'php/selectItem.php',
    type: "POST",
    data: ({item: getChangeElements(), action: 'edit'}),
    success: function (data) {
      alert(data);
      loadTable();
    }
  });
}

function deleteItem(){
  var ask = confirm("Are you sure you want to delete this item?");
  if(ask == true){
  $.ajax({
    url: 'php/selectItem.php',
    type: "POST",
    data: ({item: getChangeElements(), action: 'delete'}),
    success: function (data) {
      loadTable();
      document.getElementById('itemDiv').innerHTML = "";
    }
  });}
}

// Searches for item in the search box when enter is pressed
function enter(event){
  if (event.keyCode === 13) {
      changeTable('search');
      return false;
  }
}
