<html>
<head>
<title>Delay Recorder</title>
<script type="text/javascript">

delay_recorder = {
   instance: new Array(),  // Input field instances
   vt: new Array(),        // vt /Valid Timings/

   vt_done: false,   // vt_done /Valid Timings - Done/
   sAnnounce: true, // sAnnounce /Should Announce/

   // A function that cycles the page and calles the preparing function for each element necessery
   initialize: function(){

      //Get Elements with class "dr"/"delay recorder"/"delay_recorder" and prepare them

      // Fetch all elements
      var elements = (document.all)? document.all : document.getElementsByTagName("*");
      // Cycle through all the elements      	
      for(i=0,j=0; i<elements.length; i++) {
         // If element is of class "dr" / "delay recorder" / "delay_recorder"      	
         if( (elements[i].className.indexOf("dr") != -1) || ( elements[i].className.indexOf("delay recorder") != -1) || (elements[i].className.indexOf("delay_recorder") != -1) )
            // Prepare the element
         	delay_recorder.prepare(elements[i]);
      }
      <?php
      if(isset($_POST['vt'])){
         echo '      // Temporal'."\n";
         echo '      // If there are Valid Timings passed already, use them'."\n";
         echo "\n";
         foreach($_POST['vt'] as $key=>$value){
            echo '      delay_recorder.vt['.$key.'] = {from: '.$value[0].', to: '.$value[1].'};'."\n";
         }
         echo "\n";
         echo '      delay_recorder.vt_done = true;'."\n";
      }
      ?>
   },

   // A function that is called at the loading of the page which prepares the elements
   prepare: function(element){
      // id is required, therefore if element has none, attach one
      if(!element.id) element.id = "dr"+delay_recorder.instance.length;

      // Create the instance object
      var obj = {id: element.id, da: new Array, td: false}; // da /Delay Array/  ,  td /Temporal Date/
      var I = delay_recorder.instance.push(obj) -1;

      //Prepare the Element
      //There should be cases where input-text/password is the element but for now, work only with div/span
      element.innerHTML = '<input type="password" id="'+element.id+':input" onkeydown="delay_recorder.effect(\''+I+'\');" /> <b onclick="delay_recorder.refresh(\''+I+'\');">R</b> &nbsp; &nbsp; <span id="'+element.id+':announce"></span>';
   },

   // A function that does the actual recording of timings
   effect: function(I){
      with(delay_recorder.instance[I]){
         // Handle exceptions in which effects should not take place

            //When using the tab key
            if(event.keyCode == 9)  return false;

            //When using the enter key
            if(event.keyCode == 13) return false;

            //When trying to delete a null value
            if(!document.getElementById(delay_recorder.instance[I].id+":input"))
               var input_id = delay_recorder.instance[I].id;
            else
               var input_id = delay_recorder.instance[I].id+":input";
            var elem = document.getElementById(input_id);
            if(td != false && elem.value.length == 0) return false;

         // Record delay
         var cd = new Date(); // cd /Current Date/
         if(td!=false) da.push(cd - td);
         td = cd;
      }

      // Call the broadcasting function
      delay_recorder.announce(I);
   },

   // A function that verifies if the instance[I] 's DelayArray is in the Valid Timings Range
   verify: function(I){
      // Define the container variables
      var valid = invalid = total = 0;

      // Fetch valid and invalid timings and calculate the total timings
      with(delay_recorder.instance[I]){
         for(di in da){
            if(delay_recorder.vt[di].from < da[di] && da[di] < delay_recorder.vt[di].to)
               valid++;
            else
               invalid++;
            total++;
         }
      }

      // If 20% or more are invalid the result is negative
      if(Math.round(invalid*100 /total)>20)
         return false;
      else
         return true;
   },

   // A function that broadcasts (displays) recorded delays
   announce: function(I, tts){
      //Check if should announce at all
      if(!delay_recorder.sAnnounce) return false;
                        
      // If this is the total-time-spent announcment
      if(tts){
         with(delay_recorder.instance[I]){
         	var elem = document.getElementById(id+":announce");

         	ds = 0; // ds /Date Summary/  ,  si /Sum Index/
         	for(si in da) ds += da[si];

         	var cs = Math.round(ds/da.length); // cs /Color String/
         	cs_red   = ( ((cs-da.length*10)<0)? 0 : ( ((cs-da.length*10)>255)? 255 : (cs-da.length*10) ) ).toString(16);
         	  if(cs_red.length<2) cs_red = '0'+cs_red;
            cs_green = ( ((255-(cs-da.length*10))>255)? 255 : ( ((255-(cs-da.length*10))<0)? 0 : (255-(cs-da.length*10)) ) ).toString(16);
               if(cs_green.length<2) cs_green = '0'+cs_green;
         	cs_blue  = '00';
         	
         	cs = cs_red + cs_green + cs_blue;
            elem.innerHTML = elem.innerHTML + " | <font style='color:#"+cs+";'>"+ds+"</font> ms for "+(da.length-(-1))+" characters";
         }
         return true;
      }

      // If this is an ordinary announcment
      if(!delay_recorder.vt_done){
         with(delay_recorder.instance[I]){
            var elem = document.getElementById(id+":announce");
            elem.innerHTML = da.join(" , ");
         }
      }

      // If the announcment includes information about validity of timings
      if(delay_recorder.vt_done){
         with(delay_recorder.instance[I]){
            // Announcment String
            var ann_str = '';

            // Apply color indication for validity of timings
            for(i in da){
               // Add delimiter
               if(ann_str != '') ann_str += " , ";
               // If is in range -> green
               if(delay_recorder.vt[i].from < da[i] && da[i] < delay_recorder.vt[i].to)
                  ann_str += '<font color="green">'+da[i]+'</font>';
               // Else -> red
               else
                  ann_str += '<font color="red">'+da[i]+'</font>';
            }

            // Deploy Announcment String
            var elem = document.getElementById(id+":announce");
            elem.innerHTML = ann_str;
         }
      }
   },

   // A function tgat refreshes (resets) timings (and displayed data)
   refresh: function(I){
      //Reset field timings
      delay_recorder.instance[I].da = new Array();
      delay_recorder.instance[I].td = false;

      //Get the correct object ID
      if(!document.getElementById(delay_recorder.instance[I].id+":input"))
         var input_id = delay_recorder.instance[I].id;
      else
         var input_id = delay_recorder.instance[I].id+":input";

      //Reset the input value
      document.getElementById(input_id).value = '';

      //Reset the displayed values
      if(delay_recorder.sAnnounce)
         document.getElementById(delay_recorder.instance[I].id+":announce").innerHTML = '';

      //Return focus to the input field reset
      document.getElementById(input_id).focus();
   },

   // A function that calculates the Valid Timings' tolerable ranges
   calc_vt: function(){
      for(i in delay_recorder.instance[0].da){

         // Fetch all timings
         var timings = new Array();
         for(ii in delay_recorder.instance)
            if(typeof delay_recorder.instance[ii].da[i] != 'undefined')
               timings[ii] = delay_recorder.instance[ii].da[i];

            //if(i==0) alert("timings: "+timings); //Troubleshooting

         // Get avg /average/
         var avg = 0;
         for(ai=0; ai<timings.length ;ai++)
            avg += timings[ai];
         avg = Math.round(avg/ai);

            //if(i==0) alert("avg: "+avg); //Troubleshooting

         // Get tolerance
         var tolerance = 0;
         for(ti=0;ti<timings.length;ti++)
            if(tolerance < Math.abs(timings[ti] - avg))
               tolerance = Math.abs(timings[ti] - avg);

            //if(i==0) alert("tolerance: "+tolerance) //Troubleshooting

         // Register vt /Valid Timings/
         delay_recorder.vt[i] = {from: (avg-tolerance-10), to: (avg-(-tolerance)-(-10))};

            //if(i==0) alert("["+delay_recorder.vt[i].from+" , "+delay_recorder.vt[i].to+"]"); //Troubleshooting
      }
      delay_recorder.vt_done = true;
   },

   // A function that "packs" the Valid Timings as form data ready for submiting
   pack_vt: function(form, sSubmit){
      // Check if there is any Valid Timings to pack
      if(!delay_recorder.vt_done) return false;

      //For each vt
      for(ci in delay_recorder.vt){
         // Create the new Child Nodes that would contain the vt data
         nc_f = document.createElement('input'); // nc /New Child/
         nc_t = document.createElement('input');

         // Make them hidden
         nc_f.setAttribute("type", "hidden");
         nc_t.setAttribute("type", "hidden");

         // Set their identification names
         nc_f.setAttribute("name", "vt["+ci+"][0]");
         nc_t.setAttribute("name", "vt["+ci+"][1]");

         // Set the value as the data they should hold
         nc_f.setAttribute("value", delay_recorder.vt[ci].from);
         nc_t.setAttribute("value", delay_recorder.vt[ci].to);

         // Append the nodes to the form
         form.appendChild(nc_f);
         form.appendChild(nc_t);
      }

      // If should submit when done, submit
      if(sSubmit) form.submit();
   }
}
// Add "onload" event to the window object
if (window.addEventListener){
  window.addEventListener("load", delay_recorder.initialize, false);
}
else if (window.attachEvent){
  window.attachEvent("onload", delay_recorder.initialize);
}
else{
   window.onload = function(){ delay_recorder.initialize(); };
}

</script>
</head>
<body>
<?php
if(isset($_POST['vt'])){
   echo '<script type="text/javascript">var obj = {id: "dr0", da: new Array, td: false}; var I = delay_recorder.instance.push(obj) -1;</script>'."\n";
   echo '<label><b>Password</b>: &nbsp; &nbsp; <input type="password" id="dr0" onkeydown="delay_recorder.effect(\'0\');" onchange="delay_recorder.announce(\'0\', true);" /></label> &nbsp; &nbsp; <em>Try writing your password in various speeds and see that only your normal writing will be apporved</em><br />'."\n";
   echo '<span id="dr0:announce"></span><br />'."\n";
   echo '<input type="button" value="Submit" onclick="if(delay_recorder.verify(0)) alert(\'Hello!\'); else alert(\'I do not know you!\'); delay_recorder.refresh(0);" />'."\n";
}else{
   echo '<form action="" method="POST">'."\n";
   echo '   <span class="dr"></span><br /> &nbsp; &nbsp; <em>Write your password as you normally would</em><br />'."\n";
   echo '   <span class="dr"></span><br /> &nbsp; &nbsp; <em>Write your password a <strong>bit</strong> slower than normally</em><br />'."\n";
   echo '   <span class="dr"></span><br /> &nbsp; &nbsp; <em>Write your password as fast as you can</em><br />'."\n";
   echo '   <br />'."\n";
   echo '   <input type="button" value="Calc. Valid Times" onclick="delay_recorder.calc_vt(); delay_recorder.pack_vt(this.parentNode ,true);" />'."\n";
   echo '</form>'."\n";
}
?>
</body>
</html>