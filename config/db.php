<?php
 ini_set('display_errors','On');
 error_reporting(E_ALL);

/**
 * USE THIS CLASS FOR SHOW SPECIFIC DATABASE
 */

class db {
  private $mysql_username;
  private $mysql_password;
  private $mysql_host;
  private $mysql_database;
  private $conn;
  
  function db(){
    
    
  }
  public function connect()
  {
    


          $this->mysql_host = 'localhost';
          $this->mysql_username = 'root';
          $this->mysql_password = 'Jedi21funk';
          $this->mysql_database = 'tredis';
        
      $this->conn = mysql_connect($this->mysql_host, $this->mysql_username, $this->mysql_password)
       or die ("Could not connect: x " . mysql_error() ." ". $this->mysql_host);
    
    mysql_select_db($this->mysql_database);
    return $this->conn;
  }
  
  public function do_query($sql) {

  //echo "Do Query DB: $db <br>";
    
    $result = @mysql_query($sql);
    $total = @mysql_num_rows($result);
    if (@mysql_error() <> "") {     
        echo " <br><font face=\"Verdana\" size=\"1\"><small><b><p align=\"center\">Sorry, there has been an unexpected database error. The webmaster has been informed of this error.</p></b></small></font>";    
        // Error number   
        $error_message = "<table border=\"0\" cellpadding=\"3\" cellspacing=\"1\" style=\"border: 1px solid #bbbbbb;\" bgcolor=\"#ffffff\" width=\"80%\" align=\"center\"><tr><td align=\"right\" width=\"25%\"><font face=\"Verdana\" size=\"1\"><small><b>Error Number:</b></small></font></td><td width=\"75%\"><font face=\"Verdana\" size=\"1\"><small>" . @mysql_errno() . "</small></font></td></tr>";
        // Error Description
        $error_message .= "<tr><td align=\"right\"><font face=\"Verdana\" size=\"1\"><small><b>Error Description:</b></small></font></td><td><font face=\"Verdana\" size=\"1\"><small>" . @mysql_error() . "</small></font></td></tr>";
        // Error Date / Time  
        $error_message .= "<tr><td align=\"right\"><font face=\"Verdana\" size=\"1\"><small><b>Error Time:</b></small></font></td><td><font face='Verdana' size='1'><small>" . date("H:m:s, jS F, Y") . "</small></font></td></tr>";    
        // Script 
        $error_message .= "<tr><td align=\"right\"><font face=\"Verdana\" size=\"1\"><small><b>Script:</b></small></font></td><td><font face=\"Verdana\" size=\"1\"><small>" . $_SERVER["SCRIPT_NAME"] . "</small></font></td></tr>";
        // Line Number
        $error_message .= "<tr><td align=\"right\"><font face=\"Verdana\" size=\"1\"><small><b>Line:</b></small></font></td><td><font face=\"Verdana\" size=\"1\"><small>" . $line . "</small></font></td></tr></table>";
        // SQL  
    
        $error_message .= "<table border=\"0\" cellpadding=\"3\" cellspacing=\"1\" style=\"border: 1px solid #bbbbbb;\" bgcolor=\"#ffffff\" width=\"80%\" align=\"center\"><tr><td align=\"right\"><font face=\"Verdana\" size=\"1\"><small><b>Query:</b></small></font></td><td><font face=\"Verdana\" size=\"1\"><small>" . $sql . "</small></font></td></tr>";   
        $error_message .= "<tr><td align=\"right\" valign=\"top\" width=\"25%\"><font face=\"Verdana\" size=\"1\"><small><b>Processes:</b></small></font></td><td><font face=\"Verdana\" size=\"1\"><small>";
      
        $result = @mysql_list_processes();
        while ($row = @mysql_fetch_assoc($result)){
            $error_message .= $row["Id"] . " " . $row["Command"] . " " . $row["Time"] . "<br>";
        }
        @mysql_free_result($result);
      
        $error_message .= "</small></font></td></tr></table>";  
    echo "<br>".$error_message."<br>";
      
        //mail($email, "[MySQL Error] ". $client, $error_message, $headers);
        die();
    }
    
    return $result;
}

 public function do_insert($sql) {

  //echo "Do Query DB: $db <br>";
    
    $result = @mysql_query($sql);
    $total = @mysql_num_rows($result);
    if (@mysql_error() <> "") {     
        echo " <br><font face=\"Verdana\" size=\"1\"><small><b><p align=\"center\">Sorry, there has been an unexpected database error. The webmaster has been informed of this error.</p></b></small></font>";    
        // Error number   
        $error_message = "<table border=\"0\" cellpadding=\"3\" cellspacing=\"1\" style=\"border: 1px solid #bbbbbb;\" bgcolor=\"#ffffff\" width=\"80%\" align=\"center\"><tr><td align=\"right\" width=\"25%\"><font face=\"Verdana\" size=\"1\"><small><b>Error Number:</b></small></font></td><td width=\"75%\"><font face=\"Verdana\" size=\"1\"><small>" . @mysql_errno() . "</small></font></td></tr>";
        // Error Description
        $error_message .= "<tr><td align=\"right\"><font face=\"Verdana\" size=\"1\"><small><b>Error Description:</b></small></font></td><td><font face=\"Verdana\" size=\"1\"><small>" . @mysql_error() . "</small></font></td></tr>";
        // Error Date / Time  
        $error_message .= "<tr><td align=\"right\"><font face=\"Verdana\" size=\"1\"><small><b>Error Time:</b></small></font></td><td><font face='Verdana' size='1'><small>" . date("H:m:s, jS F, Y") . "</small></font></td></tr>";    
        // Script 
        $error_message .= "<tr><td align=\"right\"><font face=\"Verdana\" size=\"1\"><small><b>Script:</b></small></font></td><td><font face=\"Verdana\" size=\"1\"><small>" . $_SERVER["SCRIPT_NAME"] . "</small></font></td></tr>";
        // Line Number
        $error_message .= "<tr><td align=\"right\"><font face=\"Verdana\" size=\"1\"><small><b>Line:</b></small></font></td><td><font face=\"Verdana\" size=\"1\"><small>" . $line . "</small></font></td></tr></table>";
        // SQL  
    
        $error_message .= "<table border=\"0\" cellpadding=\"3\" cellspacing=\"1\" style=\"border: 1px solid #bbbbbb;\" bgcolor=\"#ffffff\" width=\"80%\" align=\"center\"><tr><td align=\"right\"><font face=\"Verdana\" size=\"1\"><small><b>Query:</b></small></font></td><td><font face=\"Verdana\" size=\"1\"><small>" . $sql . "</small></font></td></tr>";   
        $error_message .= "<tr><td align=\"right\" valign=\"top\" width=\"25%\"><font face=\"Verdana\" size=\"1\"><small><b>Processes:</b></small></font></td><td><font face=\"Verdana\" size=\"1\"><small>";
      
        $result = @mysql_list_processes();
        while ($row = @mysql_fetch_assoc($result)){
            $error_message .= $row["Id"] . " " . $row["Command"] . " " . $row["Time"] . "<br>";
        }
        @mysql_free_result($result);
      
        $error_message .= "</small></font></td></tr></table>";  
    echo "<br>".$error_message."<br>";
      
        //mail($email, "[MySQL Error] ". $client, $error_message, $headers);
        die();
    }
    
    return  mysql_insert_id();
}



  public function close(){
    mysql_close($this->conn);
  }
}