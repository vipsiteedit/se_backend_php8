function SWFLayer(){
	
	this.swf_url = "";
	this.swf_id = "Flash";
	this.layer_name = "";
	this.xx = 0;//window
	this.yy = 0;//window
	this.width = 1;
	this.height = 1;
	this.zIndex = 0;
	this.body = document['CSS1Compat' == document.compatMode ? 'documentElement' : 'body'];
	this._oLayer = "";

	this.flashvars_labels = [];
	this.flashvars_values = [];

	this._ww_full_flg = "false";
	this._hh_full_flg = "false";
}

//----------------------------
// SWF
//----------------------------
SWFLayer.prototype._createFlashVarsValue = function(){
	var value = "browser=" + this._checkBrowser() + "&";
	var flashvars_labels = this.flashvars_labels;
	var flashvars_values = this.flashvars_values;
	for(var i=0; i < flashvars_labels.length; i++){
		value += flashvars_labels[i] + "=" + flashvars_values[i] + "&";
	}
	value = value.substring(0,value.length - 1);
	return value;
};


SWFLayer.prototype.create = function(){
	var swf_url = this.swf_url;
	var swf_id = this.swf_id;
	var layer_name = this.layer_name;
	
	var html = "";
	html += "<object classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' codebase='http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0' width='100%' height='100%' id='"+swf_id+"' align='middle'>";
	html += "<param name='allowScriptAccess' value='always' />";

	if(this.flashvars_labels.length > 0){
		html += "<param name='flashvars' value='" + this._createFlashVarsValue() + "'>";
	}

	html += "<param name='movie' value='" + swf_url + "' /><param name='scale' value='noscale' /><param name='salign' value='lt' /><param name='quality' value='high' /><param name='wmode' value='transparent' /><embed src='" + swf_url + "' quality='high' scale='noscale' salign='lt' wmode='transparent' width='100%' height='100%' name='"+swf_id+"' id='"+swf_id+"' swLiveConnect=true align='middle' allowScriptAccess='always' type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/go/getflashplayer'";
	if(this.flashvars_labels.length > 0){
		html += " flashvars='" + this._createFlashVarsValue() + "'";
	}
	html += "/>";
	html += "</object>";

	var oLayer = document.createElement('div');
	oLayer.setAttribute('id',layer_name);
	//oLayer.style.background = "#FF0000";
	oLayer.innerHTML = html;
	oLayer.style.position = 'absolute';
	oLayer.style.zIndex = this.zIndex;
	document.body.appendChild(oLayer);
	this._oLayer = oLayer;

};

//
// 
//----------------------------
SWFLayer.prototype._checkBrowser = function(){
	var uName = navigator.userAgent;
	if (uName.indexOf("Safari") > -1) return "Safari";
	if (uName.indexOf("MSIE") > -1){
		return "MSIE";
	}
	return "Netscape";
};

//---------------------------------------------------------------
// 
//---------------------------------------------------------------
SWFLayer.prototype.setSize = function(ww,hh){
	var browser = this._checkBrowser();
	//
	if(ww == 'full'){
		this._ww_full_flg = "true";
		if(browser == "MSIE"){
			this.width = this.body.clientWidth;
		}else if(browser == "Netscape"){
			this.width = window.innerWidth - 17;
		}else{
			this.width = window.innerWidth;
		}
	}else{
		this._ww_full_flg = "false";
		this.width = ww;
	}

	//й«гЃ•
	if(hh == 'full'){
		this._hh_full_flg = "true";
		if(browser == "MSIE"){
			this.height = this.body.clientHeight;
		}else if(browser == "Netscape"){
			this.height = window.innerHeight-17;
		}else{
			this.height = window.innerHeight;
		}
	}else{
		this._hh_full_flg = "false";
		this.height = hh;
	}
	this._oLayer.style.width = this.width + 'px';
	this._oLayer.style.height = this.height + 'px';
};


//--------------------------------------------------------------------
//
//--------------------------------------------------------------------
SWFLayer.prototype.setPos = function(xx,yy){
	var browser = this._checkBrowser();

	this.xx = xx;
	this.yy = yy;

	//Xеє§жЁ™
	if(browser == "MSIE"){
		this._oLayer.style.left = this.xx + this.body.scrollLeft + 'px';
		this._oLayer.style.top = this.yy + this.body.scrollTop + 'px';
	}else {
		this._oLayer.style.left = this.xx + pageXOffset + 'px';
		this._oLayer.style.top = this.yy + pageYOffset + 'px';
	}

};

//
//
//--------------------------------------------------------------------
SWFLayer.prototype.setPosFixed = function(xx,yy){

	this.xx = xx;
	this.yy = yy;

	//
	this._oLayer.style.left = this.xx + 'px';
	this._oLayer.style.top = this.yy + 'px';

};

//--------------------------------------------------------------------
//
//--------------------------------------------------------------------

SWFLayer.prototype.setdPos = function(d_xx,d_yy){

	this.xx += d_xx;
	this.yy += d_yy;

	this.setPos(this.xx,this.yy);
};

//--------------------------------------------------------------------
// str='left' or 'right'
//--------------------------------------------------------------------
SWFLayer.prototype.setWindowAlign = function(str){
	var browser = this._checkBrowser() ;
	if(str == 'right'){
		if(browser == "MSIE"){
			this.xx = this.body.clientWidth - this.width;
		}else {
			this.xx = window.innerWidth - this.width;
			//Mozilla
			if(browser == "Netscape"){
				this.xx -= 17;
			}
		}
	}else{
		if(browser == "MSIE"){
			this.xx = 0;
		}else {
			this.xx = 0;
		}
	}


	this.setPos(this.xx,this.yy);
};

//--------------------------------------------------------------------
//
//--------------------------------------------------------------------
SWFLayer.prototype.setWindowAlignFixed = function(str){
//alert("aaa");
	var browser = this._checkBrowser() ;
	if(str == 'right'){
		if(browser == "MSIE"){
			this.xx = this.body.clientWidth - this.width + this.body.scrollLeft;
		}else {
			this.xx = window.innerWidth - this.width + pageXOffset;
			//Mozilla
			if(browser == "Netscape"){
				this.xx -= 17;
			}
		}
	}else{
		if(browser == "MSIE"){
			this.xx = 0;
		}else {
			this.xx = 0;
		}
	}


	this.setPosFixed(this.xx,this.yy);
};

//--------------------------------------------------------------------
//str='top' or 'bottom'
//--------------------------------------------------------------------
SWFLayer.prototype.setWindowValign = function(str){
	//
	var browser = this._checkBrowser();
	if(str == 'bottom'){
		if(browser == "MSIE"){
			this.yy = this.body.clientHeight - this.height;
		}else {
			this.yy = window.innerHeight - this.height;
			//Mozilla
			if(browser == "Netscape"){
				this.yy -= 17;
			}
		}
	}else{
		if(browser == "MSIE"){
			this.yy = 0;
		}else {
			this.yy = 0;
		}
	}
	this.setPos(this.xx,this.yy);
};

//--------------------------------------------------------------------
//
//--------------------------------------------------------------------
SWFLayer.prototype.setWindowValignFixed = function(str){
	//
	var browser = this._checkBrowser();
	if(str == 'bottom'){
		if(browser == "MSIE"){
			//alert(this.body.scrollHeight);
			this.yy = this.body.scrollHeight - this.height;
		}else {
			this.yy = this.body.offsetHeight - this.height;
			//this.yy = window.innerHeight - this.height;
			//Mozilla
			if(browser == "Netscape"){
				this.yy -= 17;
			}
		}
	}else{
		if(browser == "MSIE"){
			this.yy = 0;
		}else {
			this.yy = 0;
		}
	}
	this.setPosFixed(this.xx,this.yy);
};

//--------------------------------------------------------------------
//(str = 'visible' or 'hidden')
//--------------------------------------------------------------------
SWFLayer.prototype.setVisible = function(str){
	this._oLayer.style.visibility = str;
};

//--------------------------------------------------------------------
//
//--------------------------------------------------------------------
SWFLayer.prototype.correctWindowSize = function(str){
	if(this._ww_full_flg == "true" && this._hh_full_flg == "true"){
		this.setSize("full","full");
	}
	else if(this._ww_full_flg == "true"){
		//alert("_ww_full_flg = true");
		this.setSize("full",this.height);
	}
	else if(this._hh_full_flg == "true"){
		//alert("_hh_full_flg = true");
		this.setSize(this.width,"full");
	}
};


//---------------------------------------------------------------------
// 
//---------------------------------------------------------------------
function eventObserve(obj,e, func, bool){
	if (obj.addEventListener){
		obj.addEventListener(e, func, bool)
	}else if (obj.attachEvent){
		obj.attachEvent("on" + e, func)
	}
}


var noel_deco = "";
var noel_deco_flashvars_labels = ["myUrl"];
var noel_deco_flashvars_values = [location.host];//location.hostname
var noel_deco_zIndex = 1;

//--------------------------------------------------------------------
//load
//--------------------------------------------------------------------
function loadnoeldeco(data){
	noel_deco = new SWFLayer();
	noel_deco.zIndex = noel_deco_zIndex;
	noel_deco.swf_url = "http://infoscript.ru/script/guirlande/guirlande.swf";
	noel_deco.swf_id = "noel_deco_swf";
	noel_deco.layer_name = "noel_deco";
	
	noel_deco.flashvars_labels = noel_deco_flashvars_labels;
	noel_deco.flashvars_values = noel_deco_flashvars_values;
	
	noel_deco.create();
	noel_deco.setSize(100,300);//win 
	noel_deco.setWindowAlignFixed('left');
	noel_deco.setWindowValignFixed('top');
	if(checkBrowser() == "Safari"){
		window.resizeBy(1,0);
	}
}

eventObserve(window, 'load', loadnoeldeco, false);

//--------------------------------------------------------------------
//scroll
//--------------------------------------------------------------------
function scrollnoeldeco(){
	noel_deco.setWindowAlignFixed('left');

}
//
eventObserve(window, 'scroll', scrollnoeldeco, false);
 
//--------------------------------------------------------------------
//resize
//--------------------------------------------------------------------
function resizenoeldeco(){
	noel_deco.setWindowAlignFixed('left');

}
//window.resize
eventObserve(window, 'resize', resizenoeldeco, false);

//--------------------------------------------------------------------
//
//--------------------------------------------------------------------
//
document.getElementsByTagName("html")[0].style.overflow="scroll";

function hidenoel_deco(){
	noel_deco.setVisible("hidden");
}

function setClose(){
	noel_deco.setSize(0,0);
}

function checkBrowser(){
	var uName = navigator.userAgent;
	if (uName.indexOf("Safari") > -1) return "Safari";
	if (uName.indexOf("MSIE") > -1){
		return "MSIE";
	}
	return "Netscape";
};




