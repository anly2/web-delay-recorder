<?php
$main      = isset($_REQUEST['backward'])? 'secondary' : 'main';
$secondary = isset($_REQUEST['backward'])? 'main'     : 'secondary';

if(isset($_REQUEST['to']))
   $to = $_REQUEST['to'];

$speed = 800; //ms per exit/entrance
?>
<html>
<head>
   <title>Transition</title>
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
      body{
         overflow-x: hidden;
      }
   </style>
</head>
<body>

<table width="100%" height="100%" id="<?php echo $main; ?>">
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
                  </td>
               </tr>

               <tr>
                  <td><label for="pwd">Password</label></td>
                  <td>
                     <input type="password" id="pwd" name="passwd" tabindex="2" style="width:100px;" />
                  </td>
                  <td>
                     <input type="checkbox" id="al" title="Auto Login || Stay logged in" />
                  </td>
               </tr>

               <tr>
                  <td colspan="2" align="center">
                     <input type="submit" value="Submit" tabindex="3" />
                  </td>
                  <td align="center">
                     <a href="reg.php" title="Don't have an account yet? Sign Up Now!">&rarr; <!-- &uarr; --></a>
                  </td>
               </tr>
            </table>
         </form>

      </td>
      <td width="10%" height="100%"></td>
   </tr>
</table>



<table width="100%" height="100%" id="<?php echo $secondary; ?>">
   <tr>
      <td width="10%" height="100%"></td>
      <td width="80%" height="100%" align="center" valign="middle">

         <form action="?register" method="POST">
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

<script type="text/javascript" src="jQuery.js"></script>
<script type="text/javascript">
window.onload = function(){
   var leaving  = document.getElementById("main");
   var entering = document.getElementById("secondary");

   leaving.style.position = 'absolute';
   leaving.style.left = '0%';

   entering.style.display = 'none';

   $('#'+leaving.id).animate({left: '<?php if(isset($_REQUEST['backward'])) echo '-'; ?>100%'}, <?php echo $speed; ?>, 'swing');
   setTimeout(enter, <?php echo floor($speed/2); ?>);
}

function enter(){
   var entering = document.getElementById("secondary");

   entering.style.display = '';

   entering.style.position = 'absolute';
   entering.style.left = '<?php if(!isset($_REQUEST['backward'])) echo '-'; ?>100%';

   $('#'+entering.id).animate({left: '0%'}, <?php echo $speed; ?>, 'swing', load);
}

function load(){
<?php
if(isset($to))
   echo '   window.location.href = "'.$to.'";'."\n";
?>
}
</script>

</body>
</html>