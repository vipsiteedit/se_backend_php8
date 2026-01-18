/***********************************************
* Dynamic Countdown script- © Dynamic Drive (http://www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit http://www.dynamicdrive.com/ for this script and 100s more.
***********************************************/

function cdtime(container, targetdate){
if (!document.getElementById || !document.getElementById(container)) return
this.container=document.getElementById(container)
this.currentTime=new Date()
this.targetdate=new Date(targetdate)

this.timesup=false
this.updateTime(); 
}

cdtime.prototype.updateTime=function(){
var thisobj=this
this.currentTime.setSeconds(this.currentTime.getSeconds()+1)
setTimeout(function(){thisobj.updateTime()}, 1000) //update time every second
}

cdtime.prototype.displaycountdown=function(baseunit, functionref){
this.baseunit=baseunit
this.formatresults=functionref
this.showresults()
}

cdtime.prototype.showresults=function(){
var thisobj=this


var timediff=(this.targetdate-this.currentTime)/1000 //difference btw target date and current date, in seconds
if (timediff<0){ //if time is up
this.timesup=true
this.container.innerHTML=this.formatresults()
return
}
var oneMinute=60 //minute unit in seconds
var oneHour=60*60 //hour unit in seconds
var oneDay=60*60*24 //day unit in seconds
var dayfield=Math.floor(timediff/oneDay)
var hourfield=Math.floor((timediff-dayfield*oneDay)/oneHour)
var minutefield=Math.floor((timediff-dayfield*oneDay-hourfield*oneHour)/oneMinute)
var secondfield=Math.floor((timediff-dayfield*oneDay-hourfield*oneHour-minutefield*oneMinute))
if (this.baseunit=="hours"){ //if base unit is hours, set "hourfield" to be topmost level
hourfield=dayfield*24+hourfield
dayfield="n/a"
}
else if (this.baseunit=="minutes"){ //if base unit is minutes, set "minutefield" to be topmost level
minutefield=dayfield*24*60+hourfield*60+minutefield
dayfield=hourfield="n/a"
}
else if (this.baseunit=="seconds"){ //if base unit is seconds, set "secondfield" to be topmost level
var secondfield=timediff
dayfield=hourfield=minutefield="n/a"
}
this.container.innerHTML=this.formatresults(dayfield, hourfield, minutefield, secondfield)
setTimeout(function(){thisobj.showresults()}, 1000) //update results every second
}

/////CUSTOM FORMAT OUTPUT FUNCTIONS BELOW//////////////////////////////

//Create your own custom format function to pass into cdtime.displaycountdown()
//Use arguments[0] to access "Days" left
//Use arguments[1] to access "Hours" left
//Use arguments[2] to access "Minutes" left
//Use arguments[3] to access "Seconds" left

//The values of these arguments may change depending on the "baseunit" parameter of cdtime.displaycountdown()
//For example, if "baseunit" is set to "hours", arguments[0] becomes meaningless and contains "n/a"
//For example, if "baseunit" is set to "minutes", arguments[0] and arguments[1] become meaningless etc
                                               

function displayCountDown(){                      
if (this.timesup==false){ //if target date/time not yet met
var dd = changeword(arguments[0], 'd');              
var hh = changeword(arguments[1], 'h');
var mm = changeword(arguments[2], 'm');
var ss = changeword(arguments[3], 's');
if (arguments[0] < 10) arguments[0] = '0' + arguments[0];
if (arguments[1] < 10) arguments[1] = '0' + arguments[1];
if (arguments[2] < 10) arguments[2] = '0' + arguments[2];
if (arguments[3] < 10) arguments[3] = '0' + arguments[3];
var displaystring="<div class='count_down'><div class='arg daynum'>"+arguments[0]+"</div><div class='name daytxt'> "+dd+" </div><div class='arg hournum'> "+arguments[1]+"</div><div class='name hourtxt'> "+hh+" </div><div class='arg minnum'>"+arguments[2]+"</div><div class='name mintxt'> "+mm+" </div><div class='arg secmun'>"+arguments[3]+"</div><div class='name sectxt'> "+ss+"</div></div>"
//var displaystring="<div class='count_down'><div class='arg daynum'>"+arguments[0]+"</div><div class='name daytxt'> day </div><div class='arg hournum'> "+arguments[1]+"</div><div class='name hourtxt'> hours </div><div class='arg minnum'>"+arguments[2]+"</div><div class='name mintxt'> min </div><div class='arg secmun'>"+arguments[3]+"</div><div class='name sectxt'> sec</div></div>"
}
else{ //else if target date/time met
var displaystring="" //Don't display any text
}
return displaystring
}
 
function changeword(value, type){        
    var day = '';
    var hours = '';
    var minuts = '';
    var seconds = '';
    if(value>10&&value<20){
        day = 'Дней'; hours = 'Часов'; minuts = 'Минут'; seconds = 'Секунд'; 
    } else {
        value = value%10;
        switch(value){
            case 1: day = 'День'; hours = 'Час'; minuts = 'Минута'; seconds = 'Секунда'; break;
            case 2: day = 'Дня'; hours = 'Часа'; minuts = 'Минуты'; seconds = 'Секунды'; break;
            case 3: day = 'Дня'; hours = 'Часа'; minuts = 'Минуты'; seconds = 'Секунды'; break;
            case 4: day = 'Дня'; hours = 'Часа'; minuts = 'Минуты'; seconds = 'Секунды'; break;
            case 5: day = 'Дней'; hours = 'Часов'; minuts = 'Минут'; seconds = 'Секунд'; break;
            case 6: day = 'Дней'; hours = 'Часов'; minuts = 'Минут'; seconds = 'Секунд'; break;
            case 7: day = 'Дней'; hours = 'Часов'; minuts = 'Минут'; seconds = 'Секунд'; break;
            case 8: day = 'Дней'; hours = 'Часов'; minuts = 'Минут'; seconds = 'Секунд'; break;
            case 9: day = 'Дней'; hours = 'Часов'; minuts = 'Минут'; seconds = 'Секунд'; break;
            default: day = 'Дней'; hours = 'Часов'; minuts = 'Минут'; seconds = 'Секунд'; 
        }
    }
    switch(type){
        case 'd': return day;
        case 'h': return hours;
        case 'm': return minuts;
        case 's': return seconds;
        default: return false;
    }
}
