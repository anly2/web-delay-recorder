function parseTime(str){
   //Initial Remove of whitespaces
   str = str.replace(/ /g, '');

   //Replace with equivalent unit denotations
   str = str.replace(/second|sec/ig, 's').replace(/minute|min/ig, 'm').replace(/hour/ig, 'h').replace(/day/ig, 'd ');

   //Remove incomprehensible input
   str = str.replace(/[^0-9smhd]/g, '');

   //Add whitespace delimiters between different units
   str = str.replace(/s/g, 's ').replace(/m/g, 'm ').replace(/h/g, 'h ').replace(/d/g, 'd ');

   //Finalizing Trim
   str = str.replace(/^\s+|\s+$/g,  '');


   //Conversions to miliseconds
   str = str.replace(/s/g, '*1000');
   str = str.replace(/m/g, '*60*1000');
   str = str.replace(/h/g, '*60*60*1000');
   str = str.replace(/d/g, '*24*60*60*1000');

   //Final joining
   str = str.replace(/\s/g, '+');

   //Evaluate and return
   var ms = eval(str);

   if(isNaN(ms)) return false;
   return ms;
}
function cookie(name, value, expire){
   if(!name) return false;

   // If no value or expire date is specified
   // Act as "getCookie" function
   if(!value && !expire){
      var i;
      var nm; //Name
      var cookies = document.cookie.split(";");

      for(i=0; i<cookies.length; i++){
         nm  = cookies[i].substr(0, cookies[i].indexOf("=")).replace(/^\s+|\s+$/g, "");

         if(nm == name)
            return unescape(cookies[i].substr(cookies[i].indexOf("=")+1));
      }

      return false;
   }

   // If either value or expire date is specified
   // Act as "setCookie" function
   var str = name+"="+escape(value);
   if(expire)
      str += "; expires="+( new Date( (new Date()).getTime() + parseTime(expire)) ).toUTCString();
   document.cookie = str;
   return true;
}