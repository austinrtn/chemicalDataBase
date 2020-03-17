function selectOption(){
  let select = $("#optionsMenu").val();
  switch (select) {
    case "Display":
      printDisplayOptions();
      break;
    case "DefaultMonth":
      printDefaultMonth();
      break;
    case "copyPrices":
      printCopyPrices();
      break;
    case "feedback":
      printFeedback();
      break;
  }
  $("#optionsMenu").val("Options");
}

/*/////////////////////////////////////////////////////////
ALLOWS THE USER TO CHOOSE WHICH COLUMNS ARE SHOWN IN AS400
*////////////////////////////////////////////////////////
if(localStorage.getItem("display") === null)
  localStorage.setItem("display", colDisplay())

function displayOptions(){
  return localStorage.getItem("display");
}

// If true, column is shown.  All columns are show by default
function colDisplay(){
  var colDisplay = {
    "Description 1": true,
    "Description 2": true,
    "Category": true,
    "RM Number": true,
    "Vendor Id": true,
    "Vendor Name": true,
    "U/M": true,
    "QTY": true,
    "Min": true,
    "Max": true,
    "Cost Center": true};
  return JSON.stringify(colDisplay)};

// Prints the check boxes for the user to check columns to be shown
function printDisplayOptions(){
  let str = "<br>";
  let options = JSON.parse(displayOptions());
  let keys = Object.keys(options);
  for (var i = 0; i < keys.length; i++)
    str+= "<label>"+ keys[i] + "</label><input name='displayBox' type='checkbox' id='"+keys[i]+"' value='"+keys[i]+"'><br>";
  str+="<br><button type='button' onclick='editDisplay();'>Submit Changes</button>"
  str+=" <button type='button' onclick='editDisplay("+true+");'>Show All</button>"
  $("#edit").html(str);
  for (var i = 0; i < keys.length; i++)
    if(options[keys[i]]) document.getElementById(keys[i]).checked = true;
}

// Submites changes to column display
function editDisplay(showAll){
  let newDisplay = {};
  let boxes = document.getElementsByName("displayBox");
  for(var i = 0; i<boxes.length; i++){
    if(showAll)
      newDisplay[boxes[i].value] = true;
    else
      newDisplay[boxes[i].value] = boxes[i].checked;
  }

  newDisplay = JSON.stringify(newDisplay);
  localStorage.setItem("display", newDisplay);
  loadTable();
}


/*////////////////////////////////////////////////
SELECT THE DEFAULT MONTH FOR ALL SELECT BOXES
/////////////////////////////////////////////*/

if(localStorage.getItem("defaultMonth") === null)
  localStorage.setItem("defaultMonth", "0");

function getDefaultMonth(){
  return localStorage.getItem("defaultMonth");
}

function printDefaultMonth(){
  $("#edit").html("Default Month: <select id='changeDefaultMonth'></select> <br><br><button type='button' onclick='submitDefaultMonth();'>Submit Changes</buton>");
  $("#changeDefaultMonth").append(selectMonths());
  $("#changeDefaultMonth").val(getDefaultMonth());
}

function submitDefaultMonth(){
  localStorage.setItem("defaultMonth", $("#changeDefaultMonth").val());
  alert("Your default month has been changed.");
  location.reload();
}

/*/////////////////////////////////////////
COPY ITEM PRICES FROM ONE MONTH TO ANOTHER
///////////////////////////////////////*/
function printCopyPrices(){
  let str = "Copy <select id='copyMonth'></select> to <select id='pasteMonth'></select> <br><br><button type='button' onClick='submitPriceCopy();'>Submit Changes</button>";
  $("#edit").html(str);
  $("#copyMonth").append(selectMonths());
  $("#copyMonth").val(getDefaultMonth());
  $("#pasteMonth").append(selectMonths());
  $("#pasteMonth").val(getDefaultMonth());
}

function submitPriceCopy(){
  if(confirm("Are you sure you would like to overwrite the selected month's pricess?  You will not be able to undo these changes.")){
    $("#table").html("Coppying prices... This may take a few moments.");
    $.ajax({
      url:"php/moreOptions.php",
      type: "POST",
      data: ({copyMonth: $("#copyMonth").val(), pasteMonth: $("#pasteMonth").val(), action: "copyPrices"}),
      success: function(data){
        loadTable();
        alert(data);
      }
    });
  }
}

/*////////////////////////////////////
ALLOWS USER TO SUBMIT FEEDBACK TO DEV
//////////////////////////////////*/
function printFeedback(){
  $("#edit").html("<textarea id='feedbackTextArea' rows='4' cols='50' placeholder='Leave a comment on how the program can be improved here.'>");
  $("#edit").append("<br><br><button type='button' onclick='submitFeedback();'>Submit Feedback</button>");
}

function submitFeedback(){
  $.ajax({
    url: "php/moreOptions.php",
    type: "POST",
    data: ({text: $("#feedbackTextArea").val(), action: "submitFeedback"}),
    success: function(data){
      alert(data);
      $("#edit").html("");
    }
  });
}

function loadFeedback(){
  $.ajax({
    url: "php/moreOptions.php",
    type: "POST",
    data: ({text: $("#feedbackTextArea").val(), action: "loadFeedback"}),
    success: function(data){
      $("#table").html(data);
    }
  });
}
