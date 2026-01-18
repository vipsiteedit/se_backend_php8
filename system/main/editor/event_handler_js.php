<?php

ob_start();
?>
var seEvents;
seEvents = {
	url : 'test_event.php',
	logger : {
		log: function(message) {
			console.log(message);
		}
	},
	send : function(senddata) {
		var _this = this;
		$.ajax(_this.url, {
			type: 'POST',
			data: senddata,
			success: function(data) {
				_this.logger.log(data);
			},
			error: function(j,t,e) {
				console.log(t,e);
			}
		})
	},
	init : function() {
		
	}
}

<?php
$f = ob_get_contents();
ob_end_clean();

header('Content-type: application/javascript');
header('Expires: Mon, 1 Jan 2001 00:00:00 GMT');
echo $f;
exit;

?>