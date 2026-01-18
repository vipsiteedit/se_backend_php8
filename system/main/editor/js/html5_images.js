window.URL = window.URL || window.webkitURL; //в google chrome нет window.URL, но есть //window.webkitURL. Он необходим для создания URL локального изображения
var currImg = 0; //номер текущего загружаемого в браузер изображения
var k = 0;//номер текущего обрабатываемого изображения
var list = document.createElement('ul');//создаем список ul 
var imgData = [];//массив canvas, где хранятся canvas изображений для последующей загрузки

function handleFilesIE(name) { //обработчик события выбора файлов
    if (name == 'defined') return false;
	var filename = name.replace(/^.*\\/, '');
	var file = new Blob([{name: filename, type: "image/jpeg", lastModifiedDate: ''}]);
	//file.size = 100;
	//file.type = "image/jpeg";

	var li = document.createElement("li");
	list.appendChild(li);
	var img = document.createElement("img");
	var canvas = document.createElement("canvas");
		canvas.setAttribute("id","canvas_" + k);
	
	img.onload = function(e) {//обработка события загрузки изображения. //e.target – текущий объект изображения
		alert('load');
		window.URL.revokeObjectURL(e.target.src);//очищаем ObjectURL
		var canvas = document.getElementById("canvas_" + currImg);//мы создаем //canvas для последующей работы с изображением в браузере. Тем самым мы даем //пользователю предварительно поправить (например, повернуть) картинку перед ее загрузкой.
		canvas.width = e.target.width; 
		canvas.height = e.target.height; 
		canvas.getContext("2d").drawImage(e.target,0,0,e.target.width,e.target.height);//здесь рисуем на //canvas наше изображение
		imgData[currImg] = canvas;//добавляем в массив
		currImg++;//отдельный счетчик сделан из-за того, что картинки загружаются //параллельно работе обработчика выбора файлов, и как следствие намного медленнее
	}
 //alert(file.name);
		//img.src = window.opener.setImage(file.name);
	alert(file.name);	
	img.src = window.URL.createObjectURL(file);//присваиваем локальный адрес //картинки в src объекта img 
	var info = document.createElement("span"); 
	info.innerHTML = filename + ": " + Math.floor(files.size / 1024) + " Kb"; //выводим информацию о загруженной картинке
	li.appendChild(info);
	li.appendChild(canvas);
	document.getElementById("selectedFiles").appendChild(list);
	
	
	
	console.dir(files);
}

function handleFiles(files, multi) { //обработчик события выбора файлов
	//if (multi == false) currImg = 0;
		//alert(files.length);
console.dir(files);
	for(var i = 0; i < files.length; i++) {
		var file = files[i];
		var li = document.createElement("li");
		list.appendChild(li);
		var img = document.createElement("img");
		var canvas = document.createElement("canvas");
		canvas.setAttribute("id","canvas_" + k);
		img.onload = function(e) {//обработка события загрузки изображения. //e.target – текущий объект изображения
			window.URL.revokeObjectURL(e.target.src);//очищаем ObjectURL
			var canvas = document.getElementById("canvas_" + currImg);//мы создаем //canvas для последующей работы с изображением в браузере. Тем самым мы даем //пользователю предварительно поправить (например, повернуть) картинку перед ее загрузкой.
			canvas.width = e.target.width; 
			canvas.height = e.target.height; 
			canvas.getContext("2d").drawImage(e.target,0,0,e.target.width,e.target.height);//здесь рисуем на //canvas наше изображение
			imgData[currImg] = canvas;//добавляем в массив
			currImg++;//отдельный счетчик сделан из-за того, что картинки загружаются //параллельно работе обработчика выбора файлов, и как следствие намного медленнее
		}
 //alert(file.name);
		//img.src = window.opener.setImage(file.name);
	alert(file.name);	
	img.src = window.URL.createObjectURL(file);//присваиваем локальный адрес //картинки в src объекта img 
		var info = document.createElement("span"); 
		info.innerHTML = files[i].name + ": " + Math.floor(files[i].size / 1024) + " Kb"; //выводим информацию о загруженной картинке
		li.appendChild(info);
		li.appendChild(canvas);
		k++;//увеличиваем счетчик обрабатываемых изображений
	}
	document.getElementById("selectedFiles").appendChild(list);
}
 /* ---------------------------------------------------------------------------------------------------------------------------------------- */

function resizeImg(canvas, max_width, max_height) {
	canvas.style.display = "none";
	var width = canvas.width;
	var height = canvas.height;
	if (width > height) {
		if (width > max_width) {
			height *= max_width / width;
			width = max_width;
		}
	} else { 
		if (height > max_height) {
			width *= max_height / height;
			height = max_height;
		}
	}
 
	var copy = document.createElement("canvas");
	copy.width = width;
	copy.height = height;
	copy.getContext("2d").drawImage(canvas,0,0,width,height);
	canvas.width = width;
	canvas.height = height;
	canvas.getContext("2d").drawImage(copy,0,0);
	canvas.style.display = "block";
}

function rotateImg(canvas, width, height, angle) {
	var copy = document.createElement('canvas');
	copy.width = width;
	copy.height = height;
	copy.getContext("2d").drawImage(canvas, 0,0,width,height);
	angle = -parseFloat(angle) * Math.PI / 180;
 
	var dimAngle = angle;
	if (dimAngle > Math.PI*0.5)
		dimAngle = Math.PI - dimAngle;
	if (dimAngle < -Math.PI*0.5)
		dimAngle = -Math.PI - dimAngle;
 
	var diag = Math.sqrt(width*width + height*height);
	var diagAngle1 = Math.abs(dimAngle) - Math.abs(Math.atan2(height, width));
	var diagAngle2 = Math.abs(dimAngle) + Math.abs(Math.atan2(height, width));
	var newWidth = Math.abs(Math.cos(diagAngle1) * diag);
	var newHeight = Math.abs(Math.sin(diagAngle2) * diag);
	canvas.width = newWidth;
	canvas.height = newHeight;
	var ctx = canvas.getContext("2d");
	ctx.translate(newWidth/2, newHeight/2);
	ctx.rotate(angle);
	ctx.drawImage(copy,-width/2,-height/2);
 }
 
 function inpFile(fileInput){   
	var file = $(fileInput).prop("files")[0];
	var fileName = file.fileName;
	var fileSize = file.fileSize;
	alert("Uploading: "+fileName+" @ "+fileSize+"bytes");  

}