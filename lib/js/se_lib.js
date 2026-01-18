  function seLoadBox(section, id, name, value) {
    var width = $(id).width();
    $(id).prepend('<p style="position: absolute; width:'+width+'px; text-align: center;"><img src="/lib/js/loading.gif"></p>');
    $(id).load("?jquery"+section+"", {name: ""+name+"",value: ""+value+""});
  } 
