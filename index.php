<?php
echo "<center>";
 echo"<center> <h1> <u> Active Record </u> </h1> </center>";  // Displays Active record in center in bold with underline 
 echo "<body style='background-color:#B0C4DE'>"; // Sets background color 
 echo  "<center> <b>Attn: Prof. Keith Willams </b> <br> </center> "; // Displays Prof. Keith Williams in center in bold 
 echo "<center> <b>TA: Ikjyot Singh Gujral </b><br></center> "; // Displays Ikjyot... in center in bold
 echo "<center> <b>TA: Brandon Major </b><br></center> "; // Displays Barndon Major in center in bold
 $a= date('m/d/y',time()) ; // s variable which stores current date 
 echo "<center><b> Date: $a </b></center>"; // displays date in center in bold 
//turn on debugging messages
ini_set('display_errors', 'On');
error_reporting(E_ALL);
define('DATABASE', 'ab997');
define('USERNAME', 'ab997');
define('PASSWORD', 'pKFturo1');
define('CONNECTION', 'sql1.njit.edu');
class dbConn
{
    //Holds a connection object
    protected static $db;
    //private construct
    public function __construct()
    {
        try {
            // assigning a PDO object to db variable
            self::$db = new PDO( 'mysql:host=' . CONNECTION .';dbname=' . DATABASE, USERNAME, PASSWORD );
            self::$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
             echo 'Connected successfully<br>';
            }
        catch (PDOException $e) {
            //Output error - would normally log this to error file rather than output to user.
            echo "Connection Error: " . $e->getMessage();
            }
    }
    // get connection function. Static method - accessible without instantiation
    public static function getConnection() 
    {
        //Guarantees single instance, if no connection object exists then create one.
        if (!self::$db) {
            //new connection object.
            new dbConn();
        }
        //return connection.
        return self::$db;
    }
}
class collection {
protected $display;
    static public function create() {
      $model = new static::$modelName;
      return $model;
    }
    static public function findAll() {
        $db = dbConn::getConnection();
        $tableName = get_called_class();
        $sql = 'SELECT * FROM ' . $tableName;
        $statement = $db->prepare($sql);
        $statement->execute();
        $class = static::$modelName;
        $statement->setFetchMode(PDO::FETCH_CLASS, $class);
        $recordsSet =  $statement->fetchAll();
        return $recordsSet;
    }
    static public function findOne($id) {
        $db = dbConn::getConnection();
        $tableName = get_called_class();
        $sql = 'SELECT * FROM ' . $tableName . ' WHERE id =' . $id;
        $statement = $db->prepare($sql);
        $statement->execute();
        $class = static::$modelName;
        $statement->setFetchMode(PDO::FETCH_CLASS, $class);
        $recordsSet =  $statement->fetchAll();
        return $recordsSet;
    }
}
class accounts extends collection {
    protected static $modelName = 'account';
}
class todos extends collection {
    protected static $modelName = 'todo';
}
class model {

protected $tableName;
public function save()
    
    {
        if ($this->id != '') {
            $sql = $this->update($this->id);
        } else {
           $sql = $this->insert();
        }
        $db = dbConn::getConnection();
        $statement = $db->prepare($sql);
        $array = get_object_vars($this);
        foreach (array_flip($array) as $key=>$value){
            $statement->bindParam(":$value", $this->$value);
        }
        $statement->execute();
    }
    private function insert() {
        $modelName=get_called_class();
        $tableName = $modelName::getTablename();
        $array = get_object_vars($this);
        $columnString = implode(',', array_flip($array));
        $valueString = ':'.implode(',:', array_flip($array));
        print_r($columnString);
        $sql =  'INSERT INTO '.$tableName.' ('.$columnString.') VALUES ('.$valueString.')';
        return $sql;
    }
    private function update($id) {
        $modelName=get_called_class();
        $tableName = $modelName::getTablename();
        $array = get_object_vars($this);
        $comma = " ";
        $sql = 'UPDATE '.$tableName.' SET ';
        foreach ($array as $key=>$value){
            if( ! empty($value)) {
                $sql .= $comma . $key. ' = "'. $value .'"';
                $comma = ", ";
            }
        }
        $sql .= ' WHERE id='.$id;
        return $sql;
    }
    public function delete($id) {
        $db = dbConn::getConnection();
        $modelName=get_called_class();
        $tableName = $modelName::getTablename();
        $sql = 'DELETE FROM '.$tableName.' WHERE id='.$id;
        $statement = $db->prepare($sql);
        $statement->execute();
    }
}
    

class account extends model {
    public $id;
    public $email;
    public $fname;
    public $lname;
    public $phone;
    public $birthday;
    public $gender;
    public $password;
    public static function getTablename(){
        $tableName='accounts';
        return $tableName;
    }
}

class todo extends model {
    public $id;
    public $owneremail;
    public $ownerid;
    public $createddate;
    public $duedate;
    public $message;
    public $isdone;
    public static function getTablename(){
        $tableName='todos';
        return $tableName;
    }
}

$records = accounts::findAll();

  $display = '<table border = 1 bgcolor="#ffb3b3" style="border-collapse:collapse" text-align :"center"><tbody>';

  
  $display.= '<tr>';
    foreach($records[0] as $key=>$value)
        {
            $display .= '<th>' . htmlspecialchars($key) . '</th>';
        }
       
    $display .= '</tr>';

    foreach($records as $key=>$value)
    {
        $display .= '<tr>';
        
        foreach($value as $key2=>$value2)
        {
            $display .= '<td>' . htmlspecialchars($value2) . '<br></td>';
        }
        $display .= '</tr>';
      

    }
    $display .= '</tbody></table>';
    print_r($display);

$record = accounts::findOne(3);

  print_r("Todo Table ID Number - 3");
  
  $display = '<table border = 1 bgcolor="#ffb3b3" style="border-collapse:collapse"><tbody>';
  $display .= '<tr>';
    
    foreach($record[0]as $key=>$value)
        {
            $display .= '<th>' . htmlspecialchars($key) . '</th>';
        }
       
    $display .= '</tr>';
  
    foreach($record as $key=>$value)
    {
       $display .= '<tr>';
        
       foreach($value as $key2=>$value2)
        {
            $display .= '<td>' . htmlspecialchars($value2) . '<br></td>';
        }
        $display .= '</tr>';
      
    
    }
    $display .= '</tbody></table>';
    
    print_r($display);

echo "<h2>Delete a Record</h2>";
$record= new account();
$id=7;
$record->delete($id);
echo '<h3>Record With ID Number -- '.$id.' Gets Deleted</h3>';

$record = accounts::findAll();

$display = '<table border = 1 bgcolor="#ffb3b3" style="border-collapse:collapse"><tbody>';

  
  $display .= '<tr>';
    
    foreach($record[0] as $key=>$value)
        {
            $display .= '<th>' . htmlspecialchars($key) . '</th>';
        }
       
    $display .= '</tr>';
    
    foreach($record as $key=>$value)
    {
        $display .= '<tr>';
        
        foreach($value as $key2=>$value2)
        {
            $display .= '<td>' . htmlspecialchars($value2) . '<br></td>';
        }
        $display .= '</tr>';
      
   
    }
    $display .= '</tbody></table>';
echo "<h3>After Deleting A Record </h3>";
print_r($display);

echo "<h2>Update A Record</h2>";
$id=4;
$record = new account();
$record->id=$id;
$record->fname="fname_Update";
$record->lname="lname_Update";
$record->gender="gender_Update";
$record->save();
$record = accounts::findAll();
echo "<h3>Record Updated With ID Number -- ".$id."</h3>";
        
$display = '<table border = 1 bgcolor="#ffb3b3" style="border-collapse:collapse"><tbody>';
 
  
  $display .= '<tr>';
    
    foreach($record[0] as $key=>$value)
        {
            $display .= '<th>' . htmlspecialchars($key) . '</th>';
        }
       
    $display .= '</tr>';
    
    foreach($record as $key=>$value)
    {
        $display .= '<tr>';
        
        foreach($value as $key2=>$value2)
        {
            $display .= '<td>' . htmlspecialchars($value2) . '<br></td>';
        }
        $display .= '</tr>';
      
    
    }
    $display .= '</tbody></table>';
 
 print_r($display);

 $records = todos::findAll();
 echo "<h2> Todo Table </h2>";
  
  $display = '<table border = 1 bgcolor="#ffb3b3" style="border-collapse:collapse"><tbody>';
  
  $display .= '<tr>';
    foreach($records[0] as $key=>$value)
        {
            $display .= '<th>' . htmlspecialchars($key) . '</th>';
        }
       
    $display .= '</tr>';
    
    foreach($records as $key=>$value)
    {
        $display .= '<tr>';
        
        foreach($value as $key2=>$value2)
        {
            $display .= '<td>' . htmlspecialchars($value2) . '<br></td>';
        }
        $display .= '</tr>';
      
      
    }
    $display .= '</tbody></table>';
     print_r($display);

 $record = todos::findOne(147);

  print_r("Todo Table ID number - 147");
  
  $display = '<table border = 1 bgcolor="#ffb3b3" style="border-collapse:collapse"><tbody>';
  $display .= '<tr>';
    
    foreach($record[0]as $key=>$value)
        {
            $display .= '<th>' . htmlspecialchars($key) . '</th>';
        }
       
    $display .= '</tr>';
    
    
    foreach($record as $key=>$value)
    {
       $display .= '<tr>';
        
       foreach($value as $key2=>$value2)
        {
            $display .= '<td>' . htmlspecialchars($value2) . '<br></td>';
        }
        $display .= '</tr>';
      
     
    }
    $display .= '</tbody></table>';
    
    print_r($display);

   echo "<h2>Inserting One Record </h2>";

        $record = new todo();
        $record->owneremail="ab9971234@njit.edu";
        $record->ownerid=32;
        $record->createddate="11-21-2017";
        $record->duedate="11-28-2017";
        $record->message="Active PD0 Assignment";
        $record->isdone=13;
        $record->save();
        $records = todos::findAll();
        echo"<h2>After Inserting One Record</h2>";
        
 
     $display = '<table border =1 bgcolor="#ffb3b3" style="border-collapse:collapse"><tbody>';
 
  
      $display .= '<tr>';
      foreach($records[0] as $key=>$value)
{
$display .= '<th>' . htmlspecialchars($key) . '</th>';
}
$display .= '</tr>';
foreach($records as $key=>$value)
{
$display .= '<tr>';
foreach($value as $key2=>$value2)
{
$display .= '<td>' . htmlspecialchars($value2) . '<br></td>';
}
$display .= '</tr>';
}
$display .= '</tbody></table>';
print_r($display);
echo "</center>";
?>

