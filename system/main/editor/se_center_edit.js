window.onload = function(){
 var wsize = windowWorkSize(),                                               // размеры "рабочей области"
     testElem = document.getElementById("cont_txt_edit"),                        // ложим наш блок в переменную
     testElemWid =  testElem.offsetWidth,                                    // ширина блока
     testElemHei =  testElem.offsetHeight;                                   // высота блока

    window.document.onclick = function(){                                           // цетрируем по событию onclick
	testElem.style.left = wsize[0]/2 - testElemWid/2 + "px";        // центрируем блок по горизонтали
	testElem.style.top = wsize[1]/2 - testElemHei/2 + (document.body.scrollTop || document.documentElement.scrollTop) + "px";    // центрируем блок по вертикали + скролл
    };

   // фунция определения "рабочего пространства"
   function windowWorkSize(){
       var wwSize = new Array();
       if (window.innerHeight !== undefined) wwSize= [window.innerWidth,window.innerHeight]    // для основных браузеров
       else    
       {   // для "особо одарённых" (ИЕ6-8)
           wwSizeIE = (document.body.clientWidth) ? document.body : document.documentElement; 
           wwSize= [wwSizeIE.clientWidth, wwSizeIE.clientHeight];
       };
       return wwSize;
   };
};
                                                                                                        