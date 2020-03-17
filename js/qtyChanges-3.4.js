// Select for QTY menu of selected item
function qtyMenu(){
  var select = $("#qtyMenu").val();
  switch(select){
    case "viewLog":
      viewLog();
      break;
    case "logQtyChange":
      logQtyChange();
      break;
  }
  window.scrollTo(0,document.body.scrollHeight);
}

function loadQtyTable(month){ //Loads to cost table
  $.ajax({
    url:"php/qtyChanges.php",
    type:"POST",
    data:({month: month, action: 'table'}),
    success: function(data){
      $("#mainTable2").html(data);
    }
  });
}

// Gets values of elements to log qty change
function getLogValues(){
  sendLog = {
    "date": $("#getDate").val(),
    "month": $("#qtyMonth").val(),
    "action": $("#qtyAction").val(),
    "amount": $("#amount").val(),
    "department": $("#department").val(),
    "comments": $("#comments").val(),
    "itemId": $("input[name='sel']:checked").val()
  }
  return sendLog;
}
// View all logs for selected item
function viewLog(itemId){
  if(itemId == undefined)
    itemId = $("input[name='sel']:checked").val();

  $.ajax({
    url:"php/qtyChanges.php",
    type:"POST",
    data:({itemId: itemId, action: 'viewLog'}),
    success: function(data){
      $("#edit").html(data);
    }
  });
}


function logQtyChange(){
  var string = `<br><table><tr><th>Date</th> <th>Month</th>
  <th>Action</th> <th>Amount</th> <th>Cost</th> <th>Department</department> <th>Comments</th></tr>

  <tr><td align='center'><input type='date' id='getDate'></td>
  <td align='center'><select id=qtyMonth onchange='getCost();'></select></td>
  <td align='center'><select id='qtyAction' onchange='getCost();'><option value='Bought'>Buying</option>
  <option value='Sold'>Selling</option><option value='Set To'>Set to</option></select></td>
  <td align='center'><input type='number' id='amount' onkeyup='getCost();' onChange='getCost();'></td>

  <td align='center'><input type='text' value='$0' id='cost' readonly></td>
  <td align='center'><input id='department' list='listDepartments'><datalist id='listDepartments'></datalist></td>
  <td align='center'><textarea rows="1" cols="20" id='comments'></textArea></td></tr></table>
  <br><button type='button' onClick='submitQtyLog();'>Log Qty Change</button>`;
  $("#edit").html(string);
  $("#qtyMonth").append(selectMonths());
  $("#qtyMonth").val(getDefaultMonth());
  $("#getDate").val(new Date().toDateInputValue());
  $.ajax({
    url:"php/department.php",
    type: "POST",
    data: ({action: "getDepartments", month: "full"}),
    success: function(data){
      data = JSON.parse(data);
      let str = "";
      for (var i = 0; i < data.length; i++)
        str += "<option>"+data[i]+"</option>";
      $("#listDepartments").html(str);

    }
  });
}

function submitQtyLog(){
  var values = JSON.stringify(getLogValues());
  $.ajax({
    url:"php/qtyChanges.php",
    type:"POST",
    data:({log: values, action: "log"}),
    success: function(data){
      alert(data);
      loadTable(true);
    }
  });
}

function deleteQty(logId){
  $.ajax({
    url: 'php/qtyChanges.php',
    type: "POST",
    data: ({logId: logId, action: "delete"}),
    success: function (data) {
      alert(data);
      loadTable(true);
    }
  });
}

// Get costs of selected items
function getCost(){
  $.ajax({
    url: 'php/qtyChanges.php',
    type: "POST",
    data: ({itemId: getLogValues().itemId,
      amount: getLogValues().amount, month: getLogValues().month,
      action: "getCost", qtyAction: $("#qtyAction").val()}),
    success: function (data) {
      $("#cost").val(data);
    }
  });
}

function qtySortChange(){
  $("#sort").html("<option value='date'>Date</option> <option value='issued'>Issued</option> <option value='sold'>Sold</option> <option value='month'>Month</option> <option value='action'>Action</option> <option value='cost'>Cost</option> <option value='department'>Department</option>");
}

// Called when issue/sell change is made
function issueAndSell(type,id,bool){
  if(bool==="TRUE")
    bool="FALSE";
  else
    bool = "TRUE";
  $.ajax({
    url: 'php/qtyChanges.php',
    type: 'POST',
    data: ({action:"issueAndSell",type:type, id:id, bool:bool}),
    success: function(data){
      alert(data);
    }
  })
}

// Selects department for monthly report
function selectDepartment(department){
  $.ajax({
    url: 'php/department.php',
    type: 'POST',
    data: ({action:"selectDepartment", department: department, month:$("#month").val()}),
    success: function(data){
      $("#edit").html(data);
    }
  });
}
