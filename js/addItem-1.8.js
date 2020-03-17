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
    costcenter: $("#addCostCenter").val()};
  return addItem;
}

/* This array is used to generate html elements based off the rows of the inventory.items table
The values of these elements then are added to the database as a new item*/
var addItemAr = [new addItemBox("text","addDescription1","Description1"), new addItemBox("text","addDescription2","Description2"), new addItemBox("text", "addCategory", "Category"),
new addItemBox("text","addRmNum","RM Number"), new addItemBox("number","addVendorId","Vendor ID"), new addItemBox("text","addVendorName","Vendor Name"),
new addItemBox("text","addUm","UM"), new addItemBox("number","addQty","QTY"), new addItemBox("number", "addMin", "Min"), new addItemBox("number", "addMax", "Max"), new addItemBox(null,"addCostCenter","Cost Center", true)];

// Generates the HTML elememnts to add item
function addItem(){
  let str = "<br><table>";
  for(var i = 0; i < addItemAr.length; i++)
    str += "<th>"+addItemAr[i].placeholder+"</th>";
  str +="<tr>";
  for(var i = 0; i < addItemAr.length; i++){
    if(addItemAr[i].noPrint !== true)
      str += "<td align='center'><input type='"+addItemAr[i].type+"' id='"+addItemAr[i].id+"' placeholder="+addItemAr[i].placeholder+"></td>";
  }
  str+="<td><select id='addCostCenter'> <option>7151300017</option> <option>7161400013</option> <option>7161400016</option> <option>7320200001</option> </select></td>";
  str+= "</tr></table><br><button type='button' onclick='submitItem();'>Add Item</button> <button type=button onClick=$('#itemDiv').html('');>Cancel</button>";
  document.getElementById("itemDiv").innerHTML = str;
}

function addItemBox(type, id, placeholder,noPrint){
  this.type = type;
  this.id = id;
  this.placeholder = placeholder;
  this.noPrint = noPrint;
}

function submitItem(){
  $.ajax({
    url: 'php/selectItem.php',
    type: "POST",
    data: ({item: getAddElements(), action: 'add'}),
    success: function (data) {
      loadTable();
      alert(data);
    }
  });
}
