<?php
include "mysql.php";

if(isset($_REQUEST['register'])){
   $user    = preg_replace('/[^a-zA-Z0-9à-ÿÀ-ß_\.\- :]/', '', $_REQUEST['username']);
      $user = strtolower($user);
   $pwd     = preg_replace('/[^a-zA-Z0-9à-ÿÀ-ß_\.\- :]/', '', $_REQUEST['passwd']);
      $pwd  = md5($pwd);
      unset($_REQUEST['passwd']);
   //$dlys   = preg_replace('/[^0-9,\-]/'                , '', $_REQUEST['delays']);

   if(mysql_("INSERT INTO users (Username, Password) VALUES ('$user', '$pwd')")){
      echo '<table width="100%" height="100%">'."\n";
      echo '   <tr>'."\n";
      echo '      <td width="10%" height="100%"></td>'."\n";
      echo '      <td width="80%" height="100%" align="center" valign="middle">'."\n";
      echo '         <span style="color:darkgreen;">Registration Successful</span><br />'."\n";
      echo '         <a href="javascript: window.history.back();">Back</a>'."\n";
      echo '         <script type="text/javascript">setTimeout(\'window.location.href=\"index.php\";\', 3000);</script>'."\n";
      echo '      </td>'."\n";
      echo '      <td width="10%" height="100%"></td>'."\n";
      echo '   </tr>'."\n";
      echo '</table>'."\n";
   }else{
      echo '<table width="100%" height="100%">'."\n";
      echo '   <tr>'."\n";
      echo '      <td width="10%" height="100%"></td>'."\n";
      echo '      <td width="80%" height="100%" align="center" valign="middle">'."\n";
      echo '         <span style="color:red;">Failure to register!</span><br />'."\n";
      echo '         <a href="javascript: window.history.back();">Back</a>'."\n";
      echo '         <script type="text/javascript">setTimeout(\'window.location.href=\"reg.php\";\', 3000);</script>'."\n";
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
   <title>Register</title>

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

<table width="100%" height="100%">
   <tr>
      <td width="10%" height="100%"></td>
      <td width="80%" height="100%" align="center" valign="middle">

         <form action="?register" method="POST" onsubmit="if(document.getElementById('pwd').value != document.getElementById('cnf').value){alert('Passwords must match!'); return false;}">
            <table>
               <tr>
                  <td><label for="user">Username</label></td>
                  <td><input type="text" id="user" name="username" style="width:100px;" /></td>
                  <td width="30"><span id="user:tooltip-arrow" style="display:none;">&larr;</span></td>
                  <td rowspan="3" width="250" height="130">
                     <div id="user:tooltip" style="display:none;">
                        The <b>username</b> by which you will login.<br />
                        This is also your <b>display name</b>.<br />
                        <br />
                        Note: logging in is case-<b>in</b>sensitive<br />
                        so use upper case as you wish
                     </div>

                     <div id="pwd:tooltip" style="display:none;">
                        Your <b>Password</b><br />
                        <br />
                        <u>IMPORTANT</u>:<br />
                        The speed with which you write<br />
                        your password is recorded<br />
                        Try to <u>write it as you usually do</u>
                     </div>
                  </td>
               </tr>

               <tr>
                  <td><label for="pwd">Password</label></td>
                  <td><input type="password" id="pwd" name="passwd" style="width:100px;" /></td>
                  <td width="30"><span id="pwd:tooltip-arrow" style="display:none;">&larr;</span></td>
               </tr>

               <tr>
                  <td><label for="cnf">Confirm</label></td>
                  <td><input type="password" id="cnf" name="confirm" style="width:100px;" /></td>
                  <td width="30"><span id="cnf:tooltip-arrow" style="display:none;">&larr;</span></td>
               </tr>

               <script type="text/javascript">
                     u         = document.getElementById('user');
                     u_arrow   = document.getElementById('user:tooltip-arrow');
                     u_tooltip = document.getElementById('user:tooltip');

                     p         = document.getElementById('pwd');
                     pc        = document.getElementById('cnf');
                     p_arrow   = document.getElementById('pwd:tooltip-arrow');
                     pc_arrow  = document.getElementById('cnf:tooltip-arrow');
                     p_tooltip = document.getElementById('pwd:tooltip');
                  </script>
               <script type="text/javascript">
                  u.onfocus = function(){
                     u_arrow.style.display   = '';
                     u_tooltip.style.display = '';

                     p_arrow.style.display   = 'none';
                     pc_arrow.style.display  = 'none';
                     p_tooltip.style.display = 'none';
                  }
                  u.onblur = function(){
                     u_arrow.style.display   = 'none';
                     u_tooltip.style.display = 'none';
                  }
               </script>
               <script type="text/javascript">
                  p.onfocus = pc.onfocus = function(){
                     p_arrow.style.display   = '';
                     pc_arrow.style.display  = '';
                     p_tooltip.style.display = '';

                     u_arrow.style.display   = 'none';
                     u_tooltip.style.display = 'none';
                  }
                  p.onblur = pc.onblur = function(){
                     p_arrow.style.display   = 'none';
                     pc_arrow.style.display  = 'none';
                     p_tooltip.style.display = 'none';
                  }
               </script>

               <tr>
                  <td colspan="2" align="center">
                     <input type="submit" value="Sign Up" />

                     <a href="index.php" style="float:left;">&larr;</a>
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