<?php
// MySQL variables
$mysql_host = 'localhost';
$mysql_user = 'root';
$mysql_pass = 'ju44rff';
$mysql_db   = 'pwd';

function mysql_($query, $p=false){
   global $mysql_host, $mysql_user, $mysql_pass, $mysql_db;

   if(!mysql_connect($mysql_host, $mysql_user, $mysql_pass))
      return false;
   if(!mysql_select_db($mysql_db))
      return false;

   
   $q = mysql_query($query);
        
   if(is_bool($q))
      return $q;

   if(is_bool($p))
      if($p)
         return mysql_num_rows($q);
      else
         $p = MYSQL_BOTH;

   if(mysql_num_rows($q)>0)
      if(mysql_num_rows($q)==1)
         if(mysql_num_fields($q)==1)
            return mysql_result($q, 0, 0);
         else
            return mysql_fetch_array($q, $p);
      else{
         while($r = mysql_fetch_array($q, $p))
            $a[] = $r;
         return $a;
      }
   else
      return false;
}

?>