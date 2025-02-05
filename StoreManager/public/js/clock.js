function set2fig(num) {
   var ret;
   if( num < 10 ) { ret = "0" + num; }
   else { ret = num; }
   return ret;
}
function showClock2() {
   var nowTime = new Date();

   var options = {
         timeZone: timezone,
         hour: '2-digit',
         minute: '2-digit',
         second: '2-digit',
         hour12: false
   };
   var formatter = new Intl.DateTimeFormat([], options);
   var formattedTime = formatter.format(nowTime);

   document.getElementById("clock").innerHTML = formattedTime;
}
setInterval('showClock2()',1000);





