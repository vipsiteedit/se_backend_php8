/*

 ////////==================================================\\\\\\\\
////////               (c) EDGESTILE Company                \\\\\\\\
\\\\\\\\               Ana i?aaa caueuaiu                   ////////
 \\\\\\\\==================================================////////

*/
var test;
var flag;
//----------------------
var theFirstArea; // iaeanou ai auaaeaiey
var theSelArea; // caanu nio?aiyaony iaeanou auaaeaiey
var theLastArea; // iaeanou iinea auaaeaiey
//_---------------------
var name = navigator.appName;
var regexp = /^mic(\w|\W)*/i;
if (regexp.test(name)) flag = true;

var colors = new Array(216);
colors = [  "#000000","#000033","#000066","#000099","#0000CC","#0000FF","#003300","#003333","#003366","#003399","#0033CC","#0033FF","#006600","#006633","#006666","#006699","#0066CC","#0066FF","#009900","#009933","#009966","#009999","#0099CC","#0099FF","#00CC00","#00CC33","#00CC66","#00CC99","#00CCCC","#00CCFF","#00FF00","#00FF33","#00FF66","#00FF99","#00FFCC","#00FFFF",
"#330000","#330033","#330066","#330099","#3300CC","#3300FF","#333300","#333333","#333366","#333399","#3333CC","#3333FF","#336600","#336633","#336666","#336699","#3366CC","#3366FF","#339900","#339933","#339966","#339999","#3399CC","#3399FF","#33CC00","#33CC33","#33CC66","#33CC99","#33CCCC","#33CCFF","#33FF00","#33FF33","#33FF66","#33FF99","#33FFCC","#33FFFF",
"#660000","#660033","#660066","#660099","#6600CC","#6600FF","#663300","#663333","#663366","#663399","#6633CC","#6633FF","#666600","#666633","#666666","#666699","#6666CC","#6666FF","#669900","#669933","#669966","#669999","#6699CC","#6699FF","#66CC00","#66CC33","#66CC66","#66CC99","#66CCCC","#66CCFF","#66FF00","#66FF33","#66FF66","#66FF99","#66FFCC","#66FFFF",
"#990000","#990033","#990066","#990099","#9900CC","#9900FF","#993300","#993333","#993366","#993399","#9933CC","#9933FF","#996600","#996633","#996666","#996699","#9966CC","#9966FF","#999900","#999933","#999966","#999999","#9999CC","#9999FF","#99CC00","#99CC33","#99CC66","#99CC99","#99CCCC","#99CCFF","#99FF00","#99FF33","#99FF66","#99FF99","#99FFCC","#99FFFF",
"#CC0000","#CC0033","#CC0066","#CC0099","#CC00CC","#CC00FF","#CC3300","#CC3333","#CC3366","#CC3399","#CC33CC","#CC33FF","#CC6600","#CC6633","#CC6666","#CC6699","#CC66CC","#CC66FF","#CC9900","#CC9933","#CC9966","#CC9999","#CC99CC","#CC99FF","#CCCC00","#CCCC33","#CCCC66","#CCCC99","#CCCCCC","#CCCCFF","#CCFF00","#CCFF33","#CCFF66","#CCFF99","#CCFFCC","#CCFFFF",
"#FF0000","#FF0033","#FF0066","#FF0099","#FF00CC","#FF00FF","#FF3300","#FF3333","#FF3366","#FF3399","#FF33CC","#FF33FF","#FF6600","#FF6633","#FF6666","#FF6699","#FF66CC","#FF66FF","#FF9900","#FF9933","#FF9966","#FF9999","#FF99CC","#FF99FF","#FFCC00","#FFCC33","#FFCC66","#FFCC99","#FFCCCC","#FFCCFF","#FFFF00","#FFFF33","#FFFF66","#FFFF99","#FFFFCC","#FFFFFF"];

function quote() {
  if (document.getSelection) {
  // eonie aey NN, Opera, Firefox e o.a.
    var str = document.getSelection();
  } else if (document.selection && document.selection.createRange) {
  // eonie aey IE
    var range = document.selection.createRange();
    var str = range.text;
  } else { // not possible
    var str = "Sorry, this is not possible with your browser.";
  }
  test = '[quote]' + str + '[/quote]';
}

function doSaveSel() {
	var ar = form.erm_AreaForText;

	var range = ar.value.substring(0, ar.selectionStart);
	theFirstArea = range.toString();

	var range = ar.value.substring(ar.selectionStart, ar.selectionEnd);
	theSelArea = range.toString();

	var range = ar.value.substring(ar.selectionEnd, ar.value.length);
	theLastArea = range.toString();
}

function button(tag) {
	document.form.text.focus();
//	if (flag) // a?aoca? - IE
//	{
		if (document.getSelection) {
  		// eonie aey NN, Opera, Firefox e o.a.
    		document.form.text.value = theFirstArea + "[" + tag + "]" + theSelArea + "[/" + tag + "]" + theLastArea;
  		} else if (document.selection && document.selection.createRange) {
  		// eonie aey IE
    		var range = document.selection.createRange();
    		range.text = "["+tag+"]" + range.text + "[/"+tag+"]";
  		} else { // not possible
    		var str = "Sorry, this is not possible with your browser.";
  		}
//	}
//	else { // aey ia IE
//		document.form.text.value+= "["+tag+"]"  + "[/"+tag+"]";
//	}
}

function button_(tag)
{
document.form.text.focus();
if (flag)
{
	var str = document.selection;
	var range = str.createRange();
//	range.collapse;
	range.text= "["+tag+"]" + range.text + "[/"+tag+"]";
}
else
	document.form.text.value+= "["+tag+"]"  + "[/"+tag+"]";
}

function www(typik)
{
document.form.text.focus();
 if (flag){
var str = document.selection;
var range = str.createRange();
range.colapse;
}
if (typik=="mail")
{

 var mail = prompt("Aaaaeoa e-mail aa?an","");
 if (flag){
  if (str.type == "Text")
  range.text= "[mailto="+mail+"]"+range.text+"[/mailto]";
  else range.text = "[mailto="+mail+"]"+mail+"[/mailto]";
 }
 else document.form.text.value+= "[mailto="+mail+"]"+mail+"[/mailto]";
}
else if (typik=="img")
  {
   var url = prompt("Aaaaete URL ?enoiea","http://");
   if (flag){
   if (str.type == "Text")
   range.text= "[img src="+url+"]";
   else range.text = "[img src="+url+"]";
   }
   else document.form.text.value+="[a href="+url+"]"+url+"[/a]";
  }
 else
  {
   var url = prompt("Aaaaeoe URL","http://");
   if (flag){
   if (str.type == "Text")
   range.text= "[a href="+url+"]"+range.text+"[/a]";
   else range.text = "[a href="+url+"]"+url+"[/a]";
   }
   else document.form.text.value+="[a href="+url+"]"+url+"[/a]";
  }
}

function clr_add(tag)
{
document.form.text.focus();
if (flag)
{
var str = document.selection;
var range = str.createRange();
range.colapse;
range.text= "[COLOR="+tag+"]" + range.text + "[/COLOR]";
}
else
{
document.form.text.value+= "[COLOR="+tag+"]" +"[/COLOR]";
}
color_div.style.visibility='hidden';
return false;
}

function showtable()
{
color_div.style.visibility='visible';
}

function artclr()
{
document.write('<div id="color_div" style="visibility:hidden;">');
document.write('<table cellspacing=0 cellpadding=0 class=clr_tab onmouseout="javascript:clr_f(\'\')">');
for(i=0;i<12;i++) {
document.write('<tr>');
for(j=0;j<18;j++) {
color=colors[i*18+j];
document.write('<td> <input type="button" onclick="clr_add(\''+color+'\'); return false;" onmousemove="javascript:clr_f(\''+color+'\')" style="background-color:'+color+ '; width:10px; height:10px; nursor: pointer; border: 0px;" value=\'\'></button></td>');
}
document.write('</tr>');
}
document.write('</table>');
document.write('</div>');
}

function addSmile(smile)
{
document.form.text.focus();
if (document.selection)
{
 var str = document.selection;
 var range = str.createRange();
 range.colapse;
 range.text= smile;
}
else
{
document.form.text.value+=smile;
}
}

function clr_f(color_t)
{
form.clr.value=color_t;
clr_div.style.backgroundColor=color_t;
}


function cp ()
{
  form.erm_AreaForText.value+=test;
  test='';
}

function quote_()
{
  if (!window.getSelection) // eonie ooieoee aey IE
   {
    var str = document.selection;
    var range = str.createRange();
    range.colapse;
    if (str.type == "Text") {
    test= '[quote]'+range.text+'[/quote]';}
   }
  else{ //eonie ooieoee aey iia?u e oa
  str = window.getSelection();
  test='[quote]'+str+'[/quote]';
  }
}