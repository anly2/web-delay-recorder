<?php
echo '<html>'."\n";
echo '<head>'."\n";
echo '   <title>Delay Recorder</title>'."\n";
echo "\n";
echo '   <script type="text/javascript" src="password.old.php?js=dr_main"></script>'."\n";
//echo '   <script type="text/javascript" src="password.old.php?js=dr_pack"></script>'."\n";
echo '   <script type="text/javascript" src="password.old.php?js=dr_fun" ></script>'."\n";
echo '</head>'."\n";
echo '<body>'."\n";
echo '   <form action="?" method="post" >'."\n"; //onsubmit=" if(!valids || passAdv.verify(true)){ passAdv.calculate(); passAdv.pack(); return true;}else{ return false; }"
echo '      <script type="text/javascript">var passAdv = new delayRecorder("password"); passAdv.announceTarget = "cont"; passAdv.announceDetailed = true;</script>'."\n";
echo '      <div id="cont"></div>'."\n";
echo '      <br /><br />'."\n";
echo '      <input type="submit" value="submit" />'."\n";
echo '   </form>'."\n";
echo '</body>'."\n";
echo '</html>'."\n";
?>