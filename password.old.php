<?php
$file = basename(__FILE__); // Required! Link between Javascript and Server-side PHP


session_start(); //Required ONLY by the Current Storage Method

rec_valids:
//Change according to your Storage Method
// Currently, values are stored in the $_SESSION array
// INAPPLICABLE for real purposes
//Required!
if(isset($_REQUEST['valids'])){
   foreach($_REQUEST['valids'] as $i => $val)
      $_SESSION['valids'][$i] = $val;
      
   exit;
}

// Handling the Current Storage Method
// Should not exists when library is used  for real
if(isset($_REQUEST['test'])){
   if($_REQUEST['test']=='reset')
      unset($_SESSION['valids']);

   if(isset($_SESSION['valids']))
      foreach($_SESSION['valids'] as $key => $val)
         echo $key.':<br />&nbsp;&nbsp;&nbsp;&nbsp;from:'.$val[0].'<br />&nbsp;&nbsp;&nbsp;&nbsp;to:'.$val[1].'<hr align="left" width="300"/>'."\n";
   else
      echo 'No valid timings supplied!';

   exit;
}



js:
if(isset($_REQUEST['js'])){
   if(!isset($_SERVER['HTTP_REFERER']) || parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) != $_SERVER['HTTP_HOST'])
      exit;

   if($_REQUEST['js']=='dr_main'){
      echo '//Valids'."\n";
      echo 'var valids = '.(isset($_SESSION['valids'])? 'new Array();' : 'false')."\n\n";

      if(isset($_SESSION['valids']))
         foreach($_SESSION['valids'] as $i=>$times)
            echo '   valids['.$i.'] = {from: '.$times[0].', to: '.$times[1].'};'."\n";

      echo "\n\n\n";
   }
   if($_REQUEST['js']=='dr_main'){
      echo 'var dr_count = 0;'."\n";

      echo 'function delayRecorder(name){'."\n";
      echo "\n";
      echo '   //Construct'."\n";
      echo '   this.id      = \'dr\' + (dr_count++);'."\n";
      echo '   this.delays  = new Array();'."\n";
      echo '   this.tmpTime = false;'."\n";
      echo '   if(name==null) name = this.id;'."\n"; // name pattern:    name:delayIndex
      echo "\n";
      echo '   document.writeln(\'<input type="password" id="\'+this.id+\'" name="\'+name+\'" onkeydown="\'+( (typeof window.event == "undefined")? \'window.event = event;\' : \'\' )+\' this.dr.effect();"/><b onclick="this.previousSibling.dr.reset();">R</b>\');'."\n";
      echo '   document.getElementById(this.id).dr = this;'."\n";
      echo "\n";
      echo "\n";
      echo '   //Function that applies the Effect'."\n";
      echo '   this.effect = function(){'."\n";
      echo '      // Handle exceptions in which effects should not take place'."\n";
      echo "\n";
      echo '         //When trying to delete a null value'."\n";
      echo '         if(event.keyCode == 8 && document.getElementById(this.id).value.length == 0){'."\n";
      echo '            this.reset();'."\n";
      echo '            return false;'."\n";
      echo '         }'."\n";
      echo "\n";
      echo '         //When using the tab key'."\n";
      echo '         if(event.keyCode == 9)'."\n";
      echo '            return false;'."\n";
      echo "\n";
      echo '         //When using the enter key'."\n";
      echo '         if(event.keyCode == 13)'."\n";
      echo '            return false;'."\n";
      echo "\n";
      echo '      // Record delay'."\n";
      echo '      var curTime = new Date();'."\n";
      echo "\n";
      echo '      if(this.tmpTime)'."\n";
      echo '         this.delays.push(curTime - this.tmpTime);'."\n";
      echo "\n";
      echo '      this.tmpTime = curTime;'."\n";
      echo "\n";
      echo '      // Call the broadcasting function'."\n";
      echo '      if(this.announce)'."\n";
      echo '         this.announce();'."\n";
      echo '   }'."\n";
      echo '   //Function that resets records'."\n";
      echo '   this.reset  = function(){'."\n";
      echo '   	//Reset field timings'."\n";
      echo '      this.delays  = new Array();'."\n";
      echo '      this.tmpTime = false;'."\n";
      echo "\n";
      echo '      //Reset the input value'."\n";
      echo '      document.getElementById(this.id).value = \'\';'."\n";
      echo "\n";
      echo '      //Reset the displayed values by announcing the emptied arrays'."\n";
      echo '      if(this.announce)'."\n";
      echo '         this.announce();'."\n";
      echo "\n";
      echo '      //Return focus to the input field reset'."\n";
      echo '      document.getElementById(this.id).focus();'."\n";
      echo '   }'."\n";
      echo '   //Function that verifies the records'."\n";
      echo '   this.verify = function(beMean){'."\n";
      echo '      // Set the container variables'."\n";
      echo '      var valid = total = 0;'."\n";
      echo "\n";
      echo '      // Compare records with the valids and count the results'."\n";
      echo '      for(i in this.delays){'."\n";
      echo '         if( valids[i].from < this.delays[i] && this.delays[i] < valids[i].to )'."\n";
      echo '            valid++;'."\n";
      echo "\n";
      echo '         total++;'."\n";
      echo '      }'."\n";
      echo "\n";
      echo '      // If 65% or more records are invalid the result is negative'."\n";
      echo '      if(Math.round(valid*100 /total)>65)'."\n";
      echo '         return true;'."\n";
      echo '      else{'."\n";
      echo '         if(beMean)'."\n";
      echo '            this.reset();'."\n";
      echo "\n";
      echo '         return false;'."\n";
      echo '      }'."\n";
      echo '   }'."\n";
      echo '}'."\n";
   }
   if($_REQUEST['js']=='dr_pack'){
      // JS = CALL
      echo 'xmlhttp = new Array();'."\n";
      echo 'function call(url, callback, sync){'."\n";
      echo '   async = (typeof callback == "boolean")? !callback : (sync!=null)? !sync : true;'."\n";
      echo "\n";
      echo '   if(window.XMLHttpRequest)'."\n";
      echo '      xh = new XMLHttpRequest();'."\n";
      echo '   else'."\n";
      echo '   if(window.ActiveXObject)'."\n";
      echo '      xh = new ActiveXObject("Microsoft.XMLHTTP");'."\n";
      echo '   else'."\n";
      echo '      return false;'."\n";
      echo "\n";
      echo '   xh.open("GET", url, async);'."\n";
      echo '   xh.send(null);'."\n";
      echo "\n";
      echo "\n";
      echo '   if(callback!=null)'."\n";
      echo '      xh.onreadystatechange = function(){'."\n";
      echo '         if (this.readyState==4 && this.status==200)'."\n";
      echo '            callback(this.responseText);'."\n";
      echo '         xmlhttp.splice(xmlhttp.indexOf(this), 1);'."\n";
      echo '      }'."\n";
      echo "\n";
      echo '   xmlhttp.push(xh);'."\n";
      echo '   return async? xh : xh.responseText;'."\n";
      echo '}'."\n";
   }
   if($_REQUEST['js']=='dr_pack'){
//      echo 'delayRecorder.prototype.calc = function(x,y){'."\n";
//      echo '   avg = (x-(-y))/2;'."\n";
//      echo '   //dif = Math.abs(x-y);'."\n";
//      echo '   //sqr = Math.sqrt(avg*dif);'."\n";
//      echo "\n";
//      echo '   return Math.round(avg);'."\n";
//      echo '}'."\n";
//      echo "\n";
//      echo 'delayRecorder.prototype.calculate = function(){'."\n";
//      echo '      if(!valids){'."\n";
//      echo '         valids = new Array();'."\n";
//      echo '         for(i in this.delays)'."\n";
//      echo '            valids[i] = {from: (this.delays[i]-55) , to: (this.delays[i]-(-55)) };'."\n";
//      echo '      }else'."\n";
//      echo '         for(i in this.delays)'."\n";
//      echo '            valids[i] = {from: (this.calc(valids[i].from, this.delays[i])-15), to: (this.calc(valids[i].to, this.delays[i])-(-15)) };'."\n";
//      echo "\n";
//      echo '      return valids;'."\n";
//      echo '}'."\n";
      echo 'delayRecorder.prototype.pack = function(){'."\n";
      echo '   document.'."\n";
      echo '}'."\n";
   }
   if($_REQUEST['js']=='dr_fun' ){
      // Be very careful with this functionality!
      // It allows view of the recorded delays. FATAL when used for real!
      
      echo 'delayRecorder.prototype.announceTarget   = false; // You must supply this manually (var p = new dR(); p.aT=SMTH )'."\n";
      echo 'delayRecorder.prototype.announceDetailed = false; // Optional. Supplied manually'."\n";
      echo "\n";
      echo 'delayRecorder.prototype.announce = function(target, detailed){'."\n";
      echo '   if(!target)'."\n";
      echo '      if(!this.announceTarget)'."\n";
      echo '         return false;'."\n";
      echo '      else'."\n";
      echo '         target = this.announceTarget;'."\n";
      echo "\n";
      echo "\n";
      echo '   if(typeof target == \'string\')'."\n";
      echo '      target = document.getElementById(target);'."\n";
      echo "\n";
      echo '   content = \'\';'."\n";
      echo "\n";
      echo '      if(!valids)'."\n";
      echo '         content = this.delays.join(" , ");'."\n";
      echo "\n";
      echo '      else{'."\n";
      echo '         for(i in this.delays){'."\n";
      echo '            if(content != \'\') content += " , "; //Add delimiter'."\n";
      echo "\n";
      echo '            if( (valids[i].from) < this.delays[i] && this.delays[i] < (valids[i].to) )'."\n";
      echo '               content += \'<font color="green">\'+this.delays[i]+\'</font>\';'."\n";
      echo '            else'."\n";
      echo '               content += \'<font color="red">\'  +this.delays[i]+\'</font>\';'."\n";
      echo '         }'."\n";
      echo '      }'."\n";
      echo "\n";
      echo '      if(this.announceDetailed || detailed){'."\n";
      echo '      	timeSpent = 0;'."\n";
      echo '      	for(si in this.delays)'."\n";
      echo '      	  timeSpent += this.delays[si];'."\n";
      echo "\n";
      echo '         var color = Math.round(timeSpent/this.delays.length);'."\n";
      echo "\n";
      echo '            cs_red     = ( Math.max(0, Math.min(255, color)) ).toString(16);'."\n";
      echo '               if(cs_red.length<2) cs_red = \'0\'+cs_red;'."\n";
      echo '            cs_green   = ( Math.max(0, Math.min(255, (255-color))) ).toString(16);'."\n";
      echo '               if(cs_green.length<2) cs_green = \'0\'+cs_green;'."\n";
      echo '         	cs_blue    = \'00\';'."\n";
      echo "\n";
      echo '         	cs = cs_red + cs_green + cs_blue; // cs /Color String/'."\n";
      echo '            content += " | <font style=\'color:#"+cs+";\'>"+timeSpent+"</font> ms for "+(this.delays.length-(-1))+" characters";'."\n";
      echo '      }'."\n";
      echo "\n";
      echo '   target.innerHTML = content;'."\n";
      echo '   return true;'."\n";
      echo '}'."\n";
   }
   exit;
}
?>