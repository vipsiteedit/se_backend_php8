/******************************************
* Snow Effect Script- By Altan d.o.o. (http://www.altan.hr/snow/index.html)
* Visit Dynamic Drive DHTML code library (http://www.dynamicdrive.com/) for full source code
* Traduction franзaise par Prof TNJ
******************************************/

// Indiquer l'URL de l'image du flocon :
//var urlflocon="http://astuforum.free.fr/images/neige.gif"
// Ecrire le nombre de flocons :
var nombreflocons = 10;
// Indiquer si la neige doit disparaоtre aprиs x secondes (0=jamais) :
var cacherflocons = 0;
// Indiquer si la neige doit кtre vue sur la fenкtre ou toute la page avant de disparaоtre ("windowheight"=la fenкtre, "pageheight"=toute la page)
var voirflocons = "pageheight";

/////////// FIN DE LA PARTIE CONFIGURATION //////////////////////////////////

var ie4up = (document.all) ? 1 : 0;
var ns6up = (document.getElementById&&!document.all) ? 1 : 0;

function testIEcompatible(){
return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

var dx, xp, yp; // Variables de coordonnйes et de position
var am, stx, sty; // Variables d'amplitude
var i, doc_width = 1024, doc_height = 768; // Taille de l'йcran

if (ns6up) {
doc_width = self.innerWidth;
doc_height = self.innerHeight;
} else if (ie4up) {
doc_width = testIEcompatible().clientWidth;
doc_height = testIEcompatible().clientHeight;
}

dx = new Array();
xp = new Array();
yp = new Array();
am = new Array();
stx = new Array();
sty = new Array();

for (i = 0; i < nombreflocons; ++ i) {
dx[i] = 0; // Variables de coordonnйes
xp[i] = Math.random()*(doc_width-50); // Variables de position
yp[i] = Math.random()*doc_height;
am[i] = Math.random()*20; // Variables d'amplitude
stx[i] = 0.02 + Math.random()/10; // Variables de pas
sty[i] = 0.7 + Math.random(); // Variables de pas
if (ie4up||ns6up) {
if (i == 0) {
document.write("<div id=\"dot"+ i +"\" style=\"POSITION: absolute; Z-INDEX: "+ i +"; VISIBILITY: visible; TOP: 15px; LEFT: 15px;\"><img src='"+urlflocon+"' border=\"0\"><\/div>");
} else {
document.write("<div id=\"dot"+ i +"\" style=\"POSITION: absolute; Z-INDEX: "+ i +"; VISIBILITY: visible; TOP: 15px; LEFT: 15px;\"><img src='"+urlflocon+"' border=\"0\"><\/div>");
}
}
}

function neigeIE_NS6() { // IE et NS6 : fonctions principales d'animation
doc_width = ns6up?window.innerWidth-10 : testIEcompatible().clientWidth-10;
doc_height=(window.innerHeight && voirflocons=="windowheight")? window.innerHeight : (ie4up && voirflocons=="windowheight")? testIEcompatible().clientHeight : (ie4up && !window.opera && voirflocons=="pageheight")? testIEcompatible().scrollHeight : testIEcompatible().offsetHeight;
for (i = 0; i < nombreflocons; ++ i) { // dйplacement pour chaque point ("dot")
yp[i] += sty[i];
if (yp[i] > doc_height-50) {
xp[i] = Math.random()*(doc_width-am[i]-30);
yp[i] = 0;
stx[i] = 0.02 + Math.random()/10;
sty[i] = 0.7 + Math.random();
}
dx[i] += stx[i];
document.getElementById("dot"+i).style.top=yp[i]+"px";
document.getElementById("dot"+i).style.left=xp[i] + am[i]*Math.sin(dx[i])+"px";
}
snowtimer=setTimeout("neigeIE_NS6()", 10);
}

function cacherneige(){
if (window.snowtimer) clearTimeout(snowtimer)
for (i=0; i<nombreflocons; i++) document.getElementById("dot"+i).style.visibility="hidden"
}

if (ie4up||ns6up){
neigeIE_NS6();
if (cacherflocons>0)
setTimeout("cacherneige()", cacherflocons*1000)
}
