<?php
class DbConnect {
  private $host = 'localhost';
  private $dbName = 'inventory';
  private $user = 'root';
  private $pass = '';

  public function connect() {
    try {
      $conn = new PDO('mysql:host=' . $this->host . '; dbname=' . $this->dbName, $this->user, $this->pass);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $conn;
    } catch(PDOException $e) {
      echo 'Database Error: ' . $e->getMessage();
    }
  }
}

// Function to load a sql query with PDO
function load($sql){
  $db = new DbConnect;
  $conn = $db->connect();
  $result = $conn->prepare($sql);
  $result->execute();
  return $result;
}

//Loads / orders all rows from the items table.  Also used for searching
function loadItems(){
  $sort = $_POST['sortType'];
  $ascDesc = $_POST['ascDesc'];
  $search = "";
  if(isset($_POST['search']))
    $search = $_POST['search'];
  $stmt = "SELECT * FROM items";
  if($search != null)
    $stmt = $stmt . " WHERE UPPER($sort) LIKE UPPER('%$search%')";
  $stmt  = $stmt . " ORDER BY $sort $ascDesc";
  $items = load($stmt);
  return $items;
}

// Used to parse data from the database to the program and vice versa
$months = array('baseline', 'jan', 'feb', 'mar', 'april', 'may', 'june', 'july', 'aug', 'sep', 'oct', 'nov', 'dec');
// Used to provide titles for columns 
$titles = array('Baseline', 'Janurary', 'Feburary', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
'October', 'November', 'December');

?>
