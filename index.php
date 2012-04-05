<?php
include "mysql.php";
define('pedantic', true, true);

if(isset($_REQUEST['login'])){
   @$user   = preg_replace('/[^a-zA-Z0-9--_\.\- :]/', '', $_REQUEST['username']);
      $user = strtolower($user);
   @$pwd    = preg_replace('/[^a-zA-Z0-9--_\.\- :]/', '', $_REQUEST['passwd']);
      $pwd  = md5($pwd);
      unset($_REQUEST['passwd']);
   @$dlys   = preg_replace('/[^0-9,\-]/'                , '', $_REQUEST['delays']);

   $pass = false; //If authentication is successful
   if(mysql_("SELECT * FROM users WHERE Username='$user' AND Password='$pwd'",true)){
      $pass = true;

      if(pedantic){
         $delays = mysql_("SELECT Delays FROM users WHERE Username='$user' AND Password='$pwd'");
         $_d = explode(",", $delays); //The validity intervals
         $d  = explode(",", $dlys);   //The delays to be validated

         //Validate the delays
         if(mysql_("SELECT Familiar FROM users WHERE  Username='$user' AND Password='$pwd'")>0){
            validate:{
               $valid = 0;     //The number of correct delays
               $err   = 0;     //The miliseconds by which is mistaken
                $summ = 0;     //The summ/total ms for the password
               $pass  = false; //If verification is successful

               foreach($_d as $k=>$v){
                  list($f, $t) = explode("-", $v);
                  $c = (isset($d[$k]))? (int)$d[$k] : -1;

                  if($f<$c  &&  $c<$t)
                     $valid++;
                  else
                     $err += min(abs($f-$c) , abs($t-$c));

                  //Summing
                  $summ += round(($f+$t)/2);
               }
               unset($k, $v);
               unset($c, $f, $t);

               //if( ($valid*100)/count($_d) >= 65 ) //If 65% are valid
               //   $pass = true;

                         $treshold = ceil($summ/10);
               if($err < $treshold) //If is mistaken by less than 10% of the total expected ms
                  $pass = true;
            }
         }

         //Record increased familiarity every time
         if($pass)
            mysql_("UPDATE users SET Familiar=(Familiar+1) WHERE  Username='$user' AND Password='$pwd'");
            // $familiar can be used for more complex $treshold and update values

         //Update validity intervals
         update:{
            if($pass){

               //If the person is new/unfamiliar
               if(empty($_d) || trim(reset($_d))==''){
                  $_d = array();
                  foreach($d as $j=>$t)
                     $_d[$j] = ($t-40).'-'.($t+40);
               }

               $d_ = $_d; //The new validity intervals
               foreach($_d as $k=>$v){
                  list($f, $t) = explode("-", $v);
                  if(!isset($d[$k])) continue;
                  $c = (int)$d[$k];

                  $d_[$k] = (round(($c+$f)/2)-20)."-".(round(($c+$t)/2)+20);
               }
               mysql_("UPDATE users SET Delays='".join(",", $d_)."' WHERE Username='$user' AND Password='$pwd'");
            }
      }
      }
   }

   if($pass){
      echo '<table width="100%" height="100%">'."\n";
      echo '   <tr>'."\n";
      echo '      <td width="10%" height="100%"></td>'."\n";
      echo '      <td width="80%" height="100%" align="center" valign="middle">'."\n";
      echo '         <span style="color:darkgreen;">Login Successful</span><br />'."\n";
      echo '         <a href="javascript: window.history.back();">Back</a>'."\n";
      echo '         <script type="text/javascript">setTimeout(\'window.location.href=\"?\";\', 3000);</script>'."\n";
      echo '      </td>'."\n";
      echo '      <td width="10%" height="100%"></td>'."\n";
      echo '   </tr>'."\n";
      echo '</table>'."\n";
   }
   else{
      echo '<table width="100%" height="100%">'."\n";
      echo '   <tr>'."\n";
      echo '      <td width="10%" height="100%"></td>'."\n";
      echo '      <td width="80%" height="100%" align="center" valign="middle">'."\n";
      echo '         <span style="color:red;">Login Failed</span><br />'."\n";
      echo '         <a href="javascript: window.history.back();">Back</a>'."\n";
      echo '         <script type="text/javascript">setTimeout(\'window.location.href=\"?\";\', 3000);</script>'."\n";
      echo '      </td>'."\n";
      echo '      <td width="10%" height="100%"></td>'."\n";
      echo '   </tr>'."\n";
      echo '</table>'."\n";
   }

   exit;
}
?>
<html>
<head>
   <title>Login</title>

   <script type="text/javascript" src="cookie.js"></script>

   <style type="text/css">
      a{
         text-decoration: none;
         font-weight: bold;
         color: #5784FF;
      }
      a:hover{
         text-decoration: underline;
         color: #4467C6;
      }
   </style>
</head>
<body>

<table width="100%" height="100%" style="position:absolute; left:0%;">
   <tr>
      <td width="10%" height="100%"></td>
      <td width="80%" height="100%" align="center" valign="middle">

         <form action="?login" method="POST" id="frm">
            <table>
               <tr>
                  <td><label for="user">Username</label></td>
                  <td><input type="text" id="user" name="username" tabindex="1" style="width:100px;" /></td>
                  <td>
                     <input type="checkbox" id="ru" title="Remeber Username" />
                     <script type="text/javascript">
                     window.onload = function(){
                        frm = document.getElementById('frm');
                        u   = document.getElementById('user');
                        p   = document.getElementById('pwd');
                        ru  = document.getElementById('ru');

                        if(cookie('rem_user')){
                           ru.checked = true;
                           user.value = cookie('rem_user');
                           p.focus();
                        }else
                           u.focus();

                        frm.onsubmit = function(){
                           if(ru.checked)
                              cookie('rem_user', u.value, '30d');
                           else
                              cookie('rem_user', 'null', -1);
                        }
                     }
                     </script>
                  </td>
               </tr>

               <tr>
                  <td><label for="pwd">Password</label></td>
                  <td>
                     <input type="password" id="pwd" name="passwd" tabindex="2" style="width:100px;" />
                     <input type="hidden"   id="con" name="delays" value="" />
                  </td>
                  <td>
                     <input type="checkbox" id="al" title="Auto Login || Stay logged in" />
                     <script type="text/javascript">
                     //TEMPORAL
                     al = document.getElementById('al');

                     al.onchange = function(){
                        al.checked = false; // Temporally disabled!
                     }
                     //TEMPORAL/
                     </script>
                  </td>

                     <script type="text/javascript">
                        inp  = document.getElementById('pwd');
                        con  = document.getElementById('con');
                        form = document.getElementById('frm');
                        _tricky = true; //If backspace should be recorded as an intended keypress

                        //When page is refreshed, values are usually preserved
                        //What is visible is easy to delete/correct but since 'con' is hidden
                        con.value = '';

                        inp.onkeydown = function(event){
                           //When trying to delete (Backspace)
                           if(event.keyCode == 8){
                              //If inp is completely empty, reset the delays
                              if(inp.value.length == 0){
                                 con.value = '';
                                 delete inp.tmpTime;
                                 return true;
                              }

                              //If "intended deletions" is disabled, erase the last delay
                              if(!_tricky){
                                 var dlys = con.value.split(",");
                                 dlys.pop();
                                 con.value = dlys.join(",");
                                 inp.tmpTime = new Date();
                                 return true;
                              }
                           }

                           //When using the tab key
                           if(event.keyCode == 9)
                              return false;

                           //When using the enter key
                           if(event.keyCode == 13){
                              form.submit();
                              return false;
                           }

                           //Record delay
                           var curTime = new Date();

                           if(inp.tmpTime){
                              if(con.value.length > 0)
                                 con.value += ',';
                              con.value += (curTime - inp.tmpTime);
                           }else
                              con.value = '';

                           inp.tmpTime = curTime;
                        }
                     </script>
               </tr>

               <tr>
                  <td colspan="2" align="center">
                     <input type="submit" value="Submit" tabindex="3" />
                  </td>
                  <td align="center">
                     <a href="transition.php?to=reg.php" title="Don't have an account yet? Sign Up Now!">
                      &rarr; <!-- &uarr; -->
                     </a>
                  </td>
               </tr>
            </table>
         </form>

      </td>
      <td width="10%" height="100%"></td>
   </tr>
</table>

</body>
</html>