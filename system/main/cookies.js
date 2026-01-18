<!-- 
var __default_expires = 2592000;	// seconds, 30 days

/** function set cookies  */
function set_cookie( name, data, path, expires, domain, secure ) {
 if ( !expires ) { expires = new Date();expires.setTime( expires.getTime() + __default_expires*30*1000 ); 
} document.cookie = name + "=" + escape(data) + ((expires == null) ? "" : "; expires=" + expires.toGMTString()) + ((path == null) ? "" : "; path=" + path) + ((domain == null) ? "" : "; domain=" + domain) + ((secure == null) ? "" : "; secure"); }


/** function - get cokies */
function get_cookie( name ) 
{
 	cookie = ' ' + document.cookie; var cname = ' ' + name + '='; var from  = cookie.indexOf(cname); 
	if ( from != -1 ) { from += cname.length; to    = cookie.indexOf( ';', from );
	if ( to == -1 ) to = cookie.length;
	return unescape( cookie.substring(from, to) ); 
} return null; }

/** function - delete cokies */
function delete_cookie ( name, path, domain ) 
{
	if ( get_cookie(name) ) 
	{
		document.cookie = name + "=" + 
		((path == null) ? "" : "; path=" + path) +
		((domain == null) ? "" : "; domain=" + domain) +
		"; expires=Thu, 01-Jan-70 00:00:01 GMT";
	}
}

var del_cookie = delete_cookie;

// -->
