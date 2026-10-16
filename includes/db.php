<?php
$host_name = HOST;$database = DATABASE;$username = USERNM;$password = PASSWRD;
  try {
$db = new PDO("mysql:host=$host_name; dbname=$database", $username, $password);   //PDO connection to database
//$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
$db->exec("SET NAMES 'utf8mb4'");      // Sets encoding UTF-8
} catch (PDOException $e) { echo 'Connection failed: ' . $e->getMessage();}

function selectQuery($table_name,$fields,$where="",$show = 0){
   global $db;$i =0;
   if($where == "")
   $sql = "SELECT  ".$fields." FROM ".$table_name;
   else
   $sql = "SELECT  ".$fields." FROM ".$table_name." WHERE ".$where;
   //PDO Select Query
   if($show == 1)
   return $sql;
   $results =$db->query($sql);
   $result=array();
   if(!empty($results)){
   foreach($results as $row){$result[$i++]= $row;}
   }
   return $result;
}

function simpleQuery($query){
   global $db;$i =0;
   //echo "-------------------------<br>". $query;
    // echo "<br>-------------------------<br>";
   $results =$db->query($query);
   $result=array();
   if(!empty($results)){
   foreach($results as $row){$result[$i++]= $row;}
   }

   return $result;
}

function insertQuery($table_name,$form_data){
global $db;
$fields = array_keys($form_data);
$sql = "INSERT INTO ".$table_name."(`".implode('`,`', $fields)."`)VALUES('".implode("','", $form_data)."')";
$results =$db->query($sql);
return $db->lastInsertId();
}

function updateQuery($table_name, $form_data, $where_clause=''){
    global $db;   $sets = array();
 // check for optional where clause
    $whereSQL = '';
    if(!empty($where_clause)){
        // check to see if the 'where' keyword exists
        if(substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE'){
            // not found, add key word
            $whereSQL = " WHERE ".$where_clause;
        } else{ $whereSQL = " ".trim($where_clause);}
    }
    // start the actual SQL statement
    $sql = "UPDATE ".$table_name." SET ";

   // loop and build the column /
  if(!empty($form_data)){
    foreach($form_data as $column => $value){$sets[] = "`".$column."` = '".$value."'";}
  }
    $sql .= implode(', ', $sets);
    // append the where statement
    $sql .= $whereSQL;
    // echo $sql;
   $results =$db->query($sql);
   return ($results);
}

function deleteQuery($table_name,$id){
global $db;
   $sql = "DELETE FROM ".$table_name." WHERE ".$id;
  // echo $sql;
  $results =$db->query($sql);
   return $results;
}

function showQuery($table_name,$where = null){
    global $db;$i =0; $result=array();
    if($where == "")
       $sql = "SHOW COLUMNS FROM ".$table_name;
   else
       $sql = "SHOW COLUMNS FROM ".$table_name." WHERE ".$where;
     
    $results =$db->query($sql);
   
   if(!empty($results)){
   foreach($results as $row){ $result[$i++]= $row; }
   }
   return $result; 
}

 function alterQuery($table_name,$cond){
    global $db;
   $sql = "ALTER TABLE ".$table_name." ".$cond;
   $results =$db->query($sql);
   return $results;
}

function createQuery($query){
 global $db;
   $sql = $query;
   $results =$db->query($sql);
   return ($results);
}

function dropQuery($query){
   global $db;
   $sql = $query;
   $results =$db->query($sql);
   return ($results);
}

?>