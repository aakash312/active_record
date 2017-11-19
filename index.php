<?php
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
?>

