function loadContent(content, link)
{
  //$('#section'+content).hide();
	 $('#section'+content).post("?on_ajax_execute=&getwindow&section="+content+'&'+link, {});
}

function loadBlock(section, id, poststr)
{
	$("#"+id).post("?section="+section+"&on_ajax_execute=true&"+id, {'value' : poststr});
}


// Поиск родительского элемента 
// e- текущий объект
// name - класс с которым должен быть родитель

function firstParent(e, name){
	if(e && typeof(e[0])=="object"){ e = e[0]; }
	var finded = null;
	if(name.substr(0,1)=="."){ var findClass = name.substr(1); }else{ var findClass = null; }
	while(e){
		e = e.parentNode;
		if(e && typeof(e)=="object"){
			if(findClass){
				if( $(e).hasClass( findClass ) ){
					finded = e;
					e = null;
				}
			}else{
				if(e.tagName==name){
					finded = e;
					e = null;
				}
			}
		}else{
			e = null;
		}
	}
	return finded;
}
jQuery.fn.firstParent = function(name){
	var e = firstParent(this, name);
	return $(e);
};


///####################################################################

function sectWrapper(id){
		$('div.recordsWrapper'+id).sortable(
			{
				connectWith: 'div.recordsWrapper'+id,
				items: 'div.object',
				stop: function(event, ui){
					processStopRec(id);
				}
			}
		);
}

//скрытие



function processStop(object)
{
  var elems = $(".groupWrapper");//document.getElementsByClassName("groupWrapper");
  var data = '';
		for (var i = 0; i < elems.length; i++){
			var id = elems[i].id;
			var d = $('#'+id).sortable('serialize');
			if(d != ''){
				if (data != '') data = data + '&';
				data = data + d.split('group[]').join(id+'[]');
			}
		} 		
		setData(data);
}



function setData(data){  


  res  = $.post('?on_ajax_execute&sortable', { data:data },function(res){
  if (res!=''){
	var res_id = res.split('=');
	$(".groupItem#group_"+res_id[0]+" .group-header").find('.se-edit-content').html("Изменить раздел "+res_id[1]); 
	$(".groupItem#group_"+res_id[0])[0].id="group_"+res_id[1]; 
	//$(".groupItem#group_"+res_id[0])[0].id =attr("id","group_"+res_id[1]); 
  }
  });
}

function centerWindow(id_window){

 var wsize_h, wsize_w;

 wsize_h = $('html')[0].clientHeight;
 wsize_w = $('html')[0].clientWidth;
 // размеры "рабочей области"
 var testElem = document.getElementById(id_window),                        // cont_txt_edit, ложим наш блок в переменную
	 testElemWid, testElemHei;
	 
    if (testElem != null) {
		testElemWid =  testElem.offsetWidth,                                    // ширина блока
		testElemHei =  testElem.offsetHeight;                                   // высота блока
		testElem.style.left = wsize_w/2 - testElemWid/2 + "px";        // центрируем блок по горизонтали
		testElem.style.top = (wsize_h/2 - testElemHei/2) + "px";    // центрируем блок по вертикали + скролл
	} 
};

