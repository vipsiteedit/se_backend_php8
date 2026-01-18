function show_menu(code){
	document.getElementById("submenu_"+code).style.visibility="visible";
}

function hide_menu(code){
     document.getElementById('submenu_'+code).style.visibility='hidden';
}

function show_menu_item(code,item){
	document.getElementById('submenu_'+code+'_'+item).style.visibility="visible";
}

function hide_menu_item(code,item){
     document.getElementById('submenu_'+code+'_'+item).style.visibility='hidden';
}
