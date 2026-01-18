var ctrlKeyEnable = false, shiftKeyEnable = false;
document.onkeydown = function(event){
	event=event||window.event;
	if (event.ctrlKey){
		ctrlKeyEnable = true;
	}
	if (event.shiftKey){
		shiftKeyEnable = true;
	}
}
document.onkeyup = function(event){
	ctrlKeyEnable = false;
	shiftKeyEnable = false;
}


function runMenuEditor(tabindex){
		//if (thistab > 0) 
		$( "#dialog:ui-dialog" ).dialog( "destroy" );
		var dialog_width = $('html')[0].clientWidth - 300,
			dialog_height = $('html')[0].clientHeight - 60;
		if (dialog_width < 800) dialog_width = 800;
		
		$("#dialog-modal-menu").dialog({
			height: dialog_height,
			width: dialog_width,
			modal: true,
			create: function(event, ui) {
			},
			open: function(event, ui) {
			
			},
			resize: function(event, ui) {
				//dialog_width = this.clientWidth;
				//dialog_height = this.clientHeight;
				if (this.clientWidth < 800) this.clientWidth = 800;
				if (this.clientHeight < 500) this.clientHeight = 500;
				var head = 45;
				//$( "#dialog-modal-menu" ).css('min-width', '778px');
				//$( ".ui-dialog" ).css('min-width', dialog_width +'px');
				//$( "#dialog-modal-menu" ).css('min-height', (dialog_height-head) +'px');
				//$( ".ui-dialog" ).css('min-height', dialog_height + 'px');
			},
			buttons:[
				{
					text: ed_button['save'],
					click: function() {
					// Меню
					
					//console.log("save");
					
					var elems1 = $("#tabs-2 .menuWrapper"),   // Универсальное меню
						elems2 = $("#tabs-1 .mainMenuWrapper");  // Главное меню
					var datapage = '', datamain = '';
					
					for (var i = 0; i < elems1.length; i++){
						var id = elems1[i].id;
						var wrap = $('#tabs-2 #'+id); 
							//alert(id+' '+wrap);
						//if (wrap == undefined) continue;
						var dd = wrap.sortable('serialize');
						
						if(dd != ''){
							//console.dir(dd);
							//alert(id+' '+dd);
							if (datapage != '') {
								datapage = datapage + '&';
							};
							  datapage = datapage + dd.split('group[]').join(id+'[]');
						}
					}

					if (datapage != ''){
						datapage = datapage.split('I').join('-');
					}
						
					var d = elems2.sortable('serialize');
					
					if(d != ''){
						if (datamain != '') datamain = datamain + '&';
						datamain = datamain + d.split('group_m[]').join(elems2[0].id+'[]');
					}
					
					if (datamain != '') {
						datamain = datamain.split('I').join('-'); 
					}
					
					if (datamain != '' || datapage != ''){
						var mainmenutype = $('#mainmenutype').val();
						
					//	console.log(parent.location + "?on_ajax_execute&sortablepagemenu");
						
					//	console.log({pagemenu: ""+datapage+"", mainmenu: ""+datamain+"", menutype: ""+mainmenutype+""});
						
						$.ajax({
							url: "/?on_ajax_execute&sortablepagemenu",
							type: 'POST',
							data: {pagemenu: ""+datapage+"", mainmenu: ""+datamain+"", menutype: ""+mainmenutype+""},
							success: function(res){
								//alert(res);
								res = res.split('>>!!<<');
								$('#pageMenu', parent.document).html(res[0]);
								$('#pageMainmenu', parent.document).html(res[1]);
								parent.setMenuIcon('#pageMenu', 1, parent.document);
								parent.setMenuIcon('#pageMainmenu', 0, parent.document);
							}
						});
						//var res  = $.post('?on_ajax_execute&sortablepages', { data:data },function(res){});
					}
								
					$( this ).dialog( "close" );
				}},{
				text: ed_button['cancel'],
				click: function() {
					$( this ).dialog("close");
				}
			}],
			
			close: function () {
				frameWindow("hide");
			},
			
			beforeClose: function(event, ui) {
				$(".menuWrapper,.mainMenuWrapper").remove();
				$('#dialog-modal-menu').remove();
			}
		});
	
	// Дерево меню

	// Редактор меню . Подсветка выбранных пунктов меню
		$("#dialog-modal-menu .groupMenuItem").live("click",function(){
			if (ctrlKeyEnable == false && shiftKeyEnable == false) {
				var allCheckboxes = $("#dialog-modal-menu .groupMenuItem input:checkbox:enabled");
				for (i = 0; i < allCheckboxes.length; i++){
					if (allCheckboxes[i].checked && allCheckboxes[i]!=$(this).find("input:checkbox:enabled")[0]){
						allCheckboxes[i].checked = false;
						$(allCheckboxes[i]).parent().parent().removeClass("groupMenuItem-selected");
					}
				}
			}
			if ($(this).find("input:checked").length > 0) {
				$(this).parent().addClass("groupMenuItem-selected");
			} else {
				$(this).parent().removeClass("groupMenuItem-selected");
			}

			if (shiftKeyEnable == true) {
				var checkId1 = -1, checkId2 = -1;
				var allCheckboxes = $("#dialog-modal-menu .groupMenuItem input:checkbox");
				for (i = 0; i < allCheckboxes.length; i++){
					if (checkId1 > -1 && allCheckboxes[i].checked) {
						checkId2 = i;
					}
					if (allCheckboxes[i].checked && checkId1 == -1) {
						checkId1 = i;
					}
				}
				if (checkId2 > 0){
					for (i = checkId1 + 1; i < checkId2; i++){
						allCheckboxes[i].checked = true;
						$(allCheckboxes[i]).parent().parent().addClass("groupMenuItem-selected");
					}
				}
			}
		});

	// Редактор меню. Поиск пунктов меню при нажатии на кнопку поиска и при нажатии на Enter
	function findGM(event){
		if (((event.type == "keydown")&&(event.keyCode == 13))||(event.type == "click")) {
		sString = $(this).parent().find("input[type='text']")[0].value;
		if (sString == '') {
			$(this).parents(".menuListDMM").find(".edit_menu_pages > .groupMenu .groupMenuItem").removeClass('findPoint');
			return false;
		};
		$(this).parents(".menuListDMM").find(".edit_menu_pages > .groupMenu").removeClass('findPoint').each(function(i){
				if($(this).text().toLowerCase().search(sString.toLowerCase())!= -1) {
				//	$(this).parent().animate({scrollTop:this.offsetTop - parseInt($(this).css("marginTop"))},500);
				 // $(this).animate({borderColor:"#ff0000"},1500, function(){
				//				$(this).animate({borderColor:"#F3F3F3"},100);
				//			});
				var fitems = $(this).find('.groupMenuItem');
				for (i = 0; i < fitems.length; i++){
					if ($(fitems[i]).text().toLowerCase().search(sString.toLowerCase())!= -1)
						$(fitems[i]).addClass('findPoint');
				}
				//	alert($(this).find('.groupMenuItem').length);
				//	$(this).find('> .groupMenuItem').addClass('findPoint');
				//	return false;
		      };
		});
		
		$(this).parents(".menuListDMM").find(".edit_menu_pages > .groupMenu").each(function(i){

				if($(this).text().toLowerCase().search(sString.toLowerCase())!= -1) {
					$(this).parent().animate({scrollTop:this.offsetTop - parseInt($(this).css("marginTop"))},500);
					return false;
				};
		});
		
		}
	}
	$("#dialog-modal-menu .searchDMM > input[type='text']").keydown(findGM);
	$("#dialog-modal-menu .searchDMM > input[type='button']").click(findGM);
	
	
	// btnMoveMenuPointNoActive
	// Активирование кнопок если есть выбранные пункты
	// function ()
	
	
	// Обновление списка страниц для скрытия тех пунктов, которые уже есть в меню
//	function updateMenuList (i){
		//console.log("update page list")
		//function replace(search, replace, subject) {
		//	return subject.split(search).join(replace);
		//}
		
		
		
		//pageList.show();
		
		//menu.each(function(){
		//	$("[data-id="+replace('I','-',$(this).attr("data-id"))+"]",pageList.parent()).hide();
		//});
		
		
		
		//var pageList = $("#dialog-modal-menu .pageMenuList .groupMenu").parent();
		//pageList.empty();
		//pageList.append(pageListOriginal.clone());
		
		//console.log(pageList);
		
		//var menu = $("#dialog-modal-menu #tabs-menu .siteMenuList:not(:hidden) .edit_menu_pages .groupMenu");
		
		//console.log(pageList);
		//menu.each(function(){
		//	pageList.parent().find("[data-id="+replace('I','-',$("label",this).attr("data-id"))+"]").remove();
		//});
		
		//console.log(pageList);
//	}
	
	
	// Перемещение пунктов меню из списка в список
//	function moveGM (){
//		function replace(search, replace, subject) {
//			return subject.split(search).join(replace);
//		}
//		if (command=="add") {
//			var select = $("#dialog-modal-menu .pageMenuList .groupMenuItem-selected");
//			var target = $("#dialog-modal-menu #tabs-menu .siteMenuList:not(:hidden) .edit_menu_pages > .groupMenu").parent();
			//select.clone().appendTo(target);
			//select.hide();
//			target.append(select);
//		};
//		if (command=="delete") {
//			var select = $("#dialog-modal-menu #tabs-menu .siteMenuList:not(:hidden) .groupMenuItem-selected");
//			var target = $("#dialog-modal-menu .pageMenuList .groupMenu").parent();

//		};
		
		
		
//	};
    function createSortableMenu(index){
		var m = 'div.menuWrapper';
		if (index == 0) m = 'div.mainMenuWrapper'; 
			$(m).sortable({
				option:"placeholder",
				connectWith: m+", .groupMenu"
			}).disableSelection();
	}

	$("#dialog-modal-menu .btnMoveMenuPointToDMM").click(function (){
		function getSelect(){
			var list = [];
			$("#dialog-modal-menu .pageMenuList .groupMenuItem-selected > label").each(function(i){
				list[i] = getMenuData(this);
			});
			return list;
		};

			//menuList[tabIndex].setPoints();
			//menuList[tabIndex].create();
			//menuList[tabIndex].pageList.create();
		
		var select = getSelect();
		var target = menuList[tabIndex]
		var source = menuList[tabIndex].pageList;
		
		for ( var i=0; i < select.length ; i++){
			target.add(select[i]);
			source.remove(select[i]);
		};
		createSortableMenu(tabIndex);
		
	});
	$("#dialog-modal-menu .btnMoveMenuPointBackDMM").click(function(){
		
		// Обновляем данные о пунктах меню
		menuList[tabIndex].setPoints();
		
		function getSelect(){
			var list = [];
			$("#dialog-modal-menu #tabs-menu .siteMenuList:not(:hidden) .groupMenuItem-selected label[data-id]").each(function(i){
				list[i] = getMenuData(this);
			});
			return list;
		};
		
			//menuList[tabIndex].setPoints();
			//menuList[tabIndex].create();
			//menuList[tabIndex].pageList.create();

			var select = getSelect();
		var target = menuList[tabIndex].pageList;
		var source = menuList[tabIndex];
		
		for ( var i=0; i < select.length ; i++){
			target.add(select[i]);
			source.remove(select[i]);
		};
			$('div.menuWrapper').sortable({
				option:"placeholder",
				connectWith: "div.menuWrapper, .groupMenu",
				stop :function(event, ui)
					{
					},
				start: function(event, ui){

				}
			}).disableSelection();
//div.sortMainPages,
			$('div.mainMenuWrapper').sortable({
				option:"placeholder",
				connectWith: "div.mainMenuWrapper, .groupMenu",
				stop :function(event, ui){
					},
				start:function(event, ui){
					}
			}).disableSelection();
			
	//	console.dir(menuList);
		
	});
	
	
	
	// Модель меню
	function menu (data) {
		// объект, в котором находится меню
		var parent = $(data.parent);
		
		// добавление пункта меню
		// object - передается объект, подобный объекту из хранилища points
		this.add = function(object){
			this.points[object.id]=object;
			parent.append(this.getPoint(object));
		};
		
		// удаление пункта меню
		this.remove = function(object){
			delete this.points[object.id];
			//parent.empty();
			
			for (var n in this.points) {
				if (this.points[n].child != "undefined"){
					for (var t in this.points[n].child){
						if (this.points[n].child[t] == object.id) this.points[n].child.splice(t,1);
					}
				}
			}
			
			this.create();
		};
		
		this.create = function (){
			parent.empty();
			for (var name in this.points){
				//console.log(this.points[name].parent);
				if(!this.points[name].parent){
				//console.dir($(this.getPoint(this.points[name])));
					parent.append($(this.getPoint(this.points[name])))
				};
			}
		}
		
		// шаблон пункта меню
		var pointTemplate = data.pointTemplate;
		// преобразование шаблона пункта меню в строку html
		this.getPoint = function (object){
			// Делаем слепок с шаблона
			//console.dir(this.points);
			var temp = pointTemplate;
			var points = this.points;
			//var child = "";//pointTemplate;
			// Перебераем свойства объекта и заменяем совпадения с шаблоном
			for (var name in object) {
				// Если в шаблоне присутствует указание на дочерний пункт , то начинаем искать его в объекте и подставлять его код
				if (name=="child") {
					var arr = points[object.id].child;
					var child = "";
					for (var i in arr){
						//console.log(arr[i]);
						child = child + this.getPoint(points[arr[i]]);
					};
					temp = temp.split("[child]").join(child);
					//console.log(child);
				} else {
					temp = temp.split("["+name+"]").join(object[name]);
				}
			};
			
			// Костыли для замещения ненайденных свойств
			temp = temp.split("[child]").join("");
			
			// Возвращаем преобразованную html строку с подставленными из хранилища данными
			return temp;
		};
		
		// все пункты этого меню
		// хранилище данных
		this.points = {
			// home: { id:"", title:"", link:"", parent:"", child:"", select:"" },
		}
		
		this.setPoints = function(){
			// Заполнение хранилища
			//points = this.points;
			var points = {};
			parent.find("label[data-id]").each(function(){
				var ob = getMenuData(this);
				//console.log(ob);
				points[ob.id] = ob;
			});
			this.points = points;
		}
	};
	
	
	// Функция разбирает переданный пункт меню на составляющие и на выходе отдает объект с данными о пункте меню
	function getMenuData(point){
		var id = $(point).attr("data-id");
		var title = $(point).find("i").text();
		var link = $(point).find("em").text();
		function getChild(){
			var a = [];
			$(point).find("+ div label[data-id]").each(function(i){
				if ( getParent(this) == id){
					a[i] = $(this).attr("data-id");
				}
			});
			if (a.length > 0) {return a} else {return false};
		};
		var child = getChild();
		
		function getParent(ob){
			return $($(ob).parents("div.groupMenu")[1]).find("label[data-id]").attr("data-id");
		}
		var parent = getParent(point);
		
		var obj = {};
		if (id) { obj.id = id };
		if (title) { obj.title = title };
		if (link) { obj.link = link };
		if (child) { obj.child = child };
		if (parent) { obj.parent = parent };
		
		return obj;
	}

    
	
	// Создание меню и заполнение содержимого
	var menuList = [];
	function createMenu() {
		var parent = "";
		
		//Главное меню
		parent = "#dialog-modal-menu #tabs-1 .edit_menu_pages";
		var mainMenu = new menu({
			pointTemplate:"<div class='groupMenu' id='group_m_[id]'><label data-id='[id]' class='groupMenuItem'><input type='checkbox'><div><i> [title] </i><br><em> [link] </em></div></label>  </div>",
			parent:parent
		});
		// Заполнение хранилища
		mainMenu.setPoints();
		
		//  Список страниц
		parent = "#dialog-modal-menu .pageMenuList .edit_menu_pages";
		mainMenu.pageList = new menu({
			pointTemplate:"<div class='groupMenu' ><label data-id='[id]' class='groupMenuItem'><input type='checkbox'><div><i> [title] </i><br><em> [link] </em></div></label>  </div>",
			parent:parent
		});
		// Заполнение хранилища
		mainMenu.pageList.setPoints();
		
		// Универсльное меню
		parent = "#dialog-modal-menu #tabs-2 .edit_menu_pages";
		var universalMenu = new menu({
			pointTemplate:"<div class='groupMenu' id='group_[id]'><label data-id='[id]' class='groupMenuItem'><input type='checkbox'><div><i> [title] </i><br><em> [link] </em></div></label> <div id='sort_[id]' class='menuWrapper submenu ui-sortable'> [child] </div> </div>",
			parent:parent
		});
		// Заполнение хранилища
		universalMenu.setPoints();
		
		//  Список страниц
		parent = "#dialog-modal-menu .pageMenuList .edit_menu_pages";
		universalMenu.pageList = new menu({
			pointTemplate:"<div class='groupMenu'><label data-id='[id]' class='groupMenuItem'><input type='checkbox'><div><i> [title] </i><br><em> [link] </em></div></label>  </div>",
			parent:parent
		});
		// Заполнение хранилища
		universalMenu.pageList.setPoints();
		
		// Все меню собраны в единый массив для монепуляции через индексы
		menuList = [mainMenu, universalMenu];
		
		// Удаляем совпадения из списка страниц
		// и перестраиваем меню
		for (var i=0; i < menuList.length; i++){
			var obj = menuList[i].pageList.points;
			for (var name in obj){
				if ( menuList[i].points[name] != undefined) { delete obj[name]; }
			}
		}
		
		//console.dir(menuList);
	};
	createMenu();
	
	var tabIndex = 0;
	var activeMenu = {};
		var add = $(".control.btnMoveMenuPointToDMM");
		var remove = $(".control.btnMoveMenuPointBackDMM");
		var up = $(".control.up");
		var down = $(".control.down");
		var into = $(".control.into");
		var out = $(".control.out");

		var $tabs = $( "#tabs-menu" ).tabs({
		// обновляем список доступных пунктов меню при переключении вкладок
		show: function(event,ui) {
			menuList[tabIndex].setPoints();
			//console.dir(menuList[tabIndex]);
			
			menuList[ui.index].create();
			menuList[ui.index].pageList.create();
			tabIndex = ui.index;
			
			activeMenu = $("#tabs-"+(tabIndex+1)+" #sortbase");
			
			//console.log(tabIndex);
			for (var i = 0; i < 2; i++){
			  createSortableMenu(i);
			}

			activeMenu = $("#tabs-"+(ui.index+1)+" #sortbase");
			add.addClass('noActive');
			remove.addClass('noActive');
			up.addClass('noActive');
			down.addClass('noActive');
			into.addClass('noActive');
			out.addClass('noActive');
			
			if (activeMenu.find('.submenu').length > 0) {
				into.removeClass('hidden');
				out.removeClass('hidden');
			} else {
				into.addClass('hidden');
				out.addClass('hidden');
			};


		},
		create: function (ui){
			//updateMenuList();
		}
	});
	$tabs.tabs('select', tabindex);
	
	
	// Запускаем функции управления пунктами меню, такими как перемещение пунктов вверх/вниз/вглубь
	function runControls(){
		
		var up = $(".control.up");
		var down = $(".control.down");
		var into = $(".control.into");
		var out = $(".control.out");
		
		// Событие перемещения выбранной группы вверх
		
		// Добавить возможность смещать все меню даже если верхний из них уже на самом верху
		up.bind("click",function(){
			var selected = activeMenu.find(".groupMenuItem-selected");
			
			// Если мы уперлиысь в потолок уровня то нужно перейти на уровень выше
			function setTarget(s,i){
				i = (typeof i == "undefined") ? 0 : i;
				
				var t = $(s[i]).prev(':not(.groupMenuItem-selected)')[0];
				if (typeof t == "undefined"){
					t = $(s[i]).parents('.groupMenu')[0];
				}
				//if (typeof t == "undefined"){
					//if (i <= s.length ) {t = setTarget(s,i+1);};
				//	if (s.length > 0){
						
				//	}
				//}
				return t;
			}
			
			var target = setTarget(selected);
			
			//console.log(target);
			
			$(target).before(selected);
			
			
			// Схлопываем выбранные пункты в случае если выбран самый верхний пункт
			if ((typeof target == "undefined") && (selected.length > 0)){
				$(selected[0]).after(selected.not(selected[0]));
			};
			
		});
		
		// Событие перемещения выбранной группы вниз
		down.bind("click",function(){
			var selected = activeMenu.find(".groupMenuItem-selected");
			
			// Если мы уперлиысь в потолок уровня то нужно перейти на уровень выше
			function setTarget(s,i){
				i = (typeof i == "undefined") ? 0 : i;
				
				var t = $(s[i]).next(':not(.groupMenuItem-selected)')[0];
				
				if (typeof t == "undefined"){
					t = $(s[s.length-1]).next()[0];
				}
				
				if (typeof t == "undefined"){
					t = $(s[i]).parents('.groupMenu')[0];
				}
				return t;
			}
			
			var target = setTarget(selected);
			
			//console.log(target);
			
			$(target).after(selected);
			
		});
		
		// Событие перемещения выбранной группы вглубь пункта меню
		into.bind("click",function(){
			var selected = activeMenu.find(".groupMenuItem-selected");
			
			//console.log(selected);
			
			var target = $(selected[0]).prev().find(".submenu")[0];
			$(target).append(selected);
			//console.log(target);
			
			// Схлопываем выбранные пункты в случае если первым выбран пункт, который не может быть перемещен
			if ((typeof target == "undefined") && (selected.length > 0)){
				$(selected[0]).after(selected.not(selected[0]));
			};
			
		});
		
		// Событие выведения выбранной группы пунктов меню на внешний уровень
		out.bind("click",function(){
			var selected = activeMenu.find(".groupMenuItem-selected");
			
			//console.log(selected);
			
			var target = $(selected[0]).parents(".groupMenu")[0];
			$(target).after(selected);
			//console.log(target);
			
			// Схлопываем выбранные пункты в случае если выбран пункт в корне и пункты в нем
			if ((typeof target == "undefined") && (selected.length > 0)){
				$(selected[0]).after(selected.not(selected[0]));
			};
			
		});
		
	};
	runControls();
	
	// Функция определяет будут ли видны или активны пункты управления меню
	function activateControls (c) {
/*		
		var add = $(".control.btnMoveMenuPointToDMM");
		var remove = $(".control.btnMoveMenuPointBackDMM");
		var up = $(".control.up");
		var down = $(".control.down");
		var into = $(".control.into");
		var out = $(".control.out");

		$("#tabs-menu").bind("tabsshow", function(event, ui) {
		});
*/		
		
		$("#dialog-modal-menu .groupMenuItem, .control").live("click",function(){
			if (activeMenu.find('.groupMenuItem-selected').length > 0) {
				remove.removeClass('noActive');
				up.removeClass('noActive');
				down.removeClass('noActive');
				into.removeClass('noActive');
				out.removeClass('noActive');
			} else {
				remove.addClass('noActive');
				up.addClass('noActive');
				down.addClass('noActive');
				into.addClass('noActive');
				out.addClass('noActive');
			};
			
			if ($('.pageMenuList').find('.groupMenuItem-selected').length > 0) {
				add.removeClass('noActive');
			} else {
				add.addClass('noActive');
			}
		});
		
	};
	activateControls();
	
}