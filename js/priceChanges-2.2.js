// Select for price menu when item is selelcted
function priceMenu(){
  var select = $("#priceMenu").val();
  switch(select){
    case "viewLog":
      viewPriceLog();
      break;
    case "logPriceChange":
      logPriceChange();
      break;
    case "editPrices":
      editPrices();
      break;
  }
  window.scrollTo(0,document.body.scrollHeight);
}

// Gets prices from the "edit prices" table for a selected item
function getPrices(){
  sendPrices = {
    "baselineList" : $("#baselineList").val(),
    "baselineStd" : $("#baselineStd").val(),
    "janList" : $("#janList").val(),
    "janStd" : $("#janStd").val(),
    "febList" : $("#febList").val(),
    "febStd" : $("#febStd").val(),
    "marList" : $("#marList").val(),
    "marStd" : $("#marStd").val(),
    "aprilList" : $("#aprilList").val(),
    "aprilStd" : $("#aprilStd").val(),
    "mayList" : $("#mayList").val(),
    "mayStd" : $("#mayStd").val(),
    "juneList" : $("#juneList").val(),
    "juneStd" : $("#juneStd").val(),
    "julyList" : $("#julyList").val(),
    "julyStd" : $("#julyStd").val(),
    "augList" : $("#augList").val(),
    "augStd" : $("#augStd").val(),
    "sepList" : $("#sepList").val(),
    "sepStd" : $("#sepStd").val(),
    "octList" : $("#octList").val(),
    "octStd" : $("#octStd").val(),
    "novList" : $("#novList").val(),
    "novStd" : $("#novStd").val(),
    "decList" : $("#decList").val(),
    "decStd" : $("#decStd").val()
  }
  return JSON.stringify(sendPrices);
}
// Gets values to log price change
function getLogPrice(){
  sendLog = {
      "date" : $("#getDate").val(),
      "month": $("#priceMonth").val(),
      "newList": parseFloat($("#newList").val()),
      "newStd": $("#newStd").val(),
      "comments": $("#comments").val(),
  }
  return JSON.stringify(sendLog);
}

Date.prototype.toDateInputValue = (function() {
  var local = new Date(this);
  local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
  return local.toJSON().slice(0,10);
});

function viewPriceLog(){
  var id = $("input[name='sel']:checked").val();

  $.ajax({
    url: 'php/loadPriceTable.php',
    type: "POST",
    data: ({id: id, action: "viewLog"}),
    success: function (data) {
      $("#edit").html(data);
    }
  });
}

function deletePrice(logId){
  $.ajax({
    url: 'php/loadPriceTable.php',
    type: "POST",
    data: ({id: logId, action: "delete"}),
    success: function (data) {
      alert(data);
      loadTable();
    }
  });
}

//  Displays elements for price change
function logPriceChange(){
  var string = `<table><th>Date</th> <th>Month</th> <th>New List</th> <th>New STD</th> <th>Comments</th>
    <tr><td><input type='date' id='getDate'></td> <td><select id='priceMonth'></select></td>
    <td align='center'><input type='number' id='newList'></td> <td align='center'><input type='number' id='newStd'></td>
    <td><textarea rows="1" cols="20" id='comments'></textArea></td> </tr></table>
    <br><button type='button' onClick='logPrice();'>Submit Price Change</button>`;
  $("#edit").html(string);
  $("#priceMonth").append(selectMonths());
  $("#priceMonth").val(getDefaultMonth());
  $("#getDate").val(new Date().toDateInputValue());
}

function logPrice(){
  var id = $("[name='sel']:checked").val();

  $.ajax({
    url: "php/loadPriceTable.php",
    type: "POST",
    data: ({id: id, action: "log", log: getLogPrice()}),
    success: function(data){
      alert(data);
      loadTable();
    }
  });
}

function editPrices(){
  var id = $("input[name='sel']:checked").val();

  $.ajax({
    url: 'php/loadPriceTable.php',
    type: "POST",
    data: ({id: id, action: "print"}),
    success: function (data) {
      $("#edit").html(data);
      $("#edit").append("<button type='button' id='editPrice' onClick='submitPrices();'>Submit Prices</button>");
    }
  });
}

function submitPrices(){
  id = $("input[name='sel']:checked").val();

  $.ajax({
    url: 'php/loadPriceTable.php',
    type: "POST",
    data: ({id: id, prices: getPrices(), action: "submit"}),
    success: function (data) {
      alert(data);
      loadTable();
    }
  });
}
