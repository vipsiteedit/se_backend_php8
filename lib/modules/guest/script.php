<?php
		function to_utf8_1 ($file) {
			$f = se_file($file);
			$ff = se_fopen('m'.$file,'w');
			if ( $f[0][0] == chr(8) ) {
				foreach ( $f as $v ) {
					$data = explode (chr(8), $v);
					$date = explode ('.', iconv( 'cp1251', 'utf-8', trim($data[1])));
					$data = 
					serialize(
						array (
/*
							'date' => iconv( 'cp1251', 'utf-8', trim($data[1])),
							'name' => iconv( 'cp1251', 'utf-8', trim($data[2])),
							'message' => iconv( 'cp1251', 'utf-8', trim($data[4])),
							'email' => iconv( 'cp1251', 'utf-8', trim($data[6])),
							'ip' => iconv( 'cp1251', 'utf-8', trim($data[7]))
//*/
                            'usrname' => iconv( 'cp1251', 'utf-8', trim($data[2])),
							'usrmail' => iconv( 'cp1251', 'utf-8', trim($data[6])),
							'usrnote' => base64_encode(iconv( 'cp1251', 'utf-8', trim($data[4]))),
							'date'    => mktime(0,0,0,intval($date[1]),intval($date[0]),intval($date[2])),
                            'ip'      => iconv( 'cp1251', 'utf-8', trim($data[7]))
						)
					);
					fprintf ($ff,"%s\n",$data);
				}
			} else {
				foreach ( $f as $v ) {
					$data = unserialize($v);
					if ( function_exists(mb_detect_encoding) ) {
						if ( (mb_detect_encoding($data['date'],'cp1251,utf-8') == 'CP1251') ) {
							$date['date'] = iconv('cp1251','utf-8',$data['date']);
						}
						if ( (mb_detect_encoding($data['usrname'],'cp1251,utf-8') == 'CP1251') ) {
							$date['usrname'] = iconv('cp1251','utf-8',$data['usrname']);
						}
						if ( (mb_detect_encoding($data['usrnote'],'cp1251,utf-8') == 'CP1251') ) {
							$date['usrnote'] = iconv('cp1251','utf-8',$data['usrnote']);
						}
						if ( (mb_detect_encoding($data['usrmail'],'cp1251,utf-8') == 'CP1251') ) {
							$date['usrmail'] = iconv('cp1251','utf-8',$data['usrmail']);
						}
						if ( (mb_detect_encoding($data['ip'],'cp1251,utf-8') == 'CP1251') ) {
							$date['ip'] = iconv('cp1251','utf-8',$data['ip']);
						}
					}
					fprintf($ff,"%s\n",serialize($data));
				}
			}				
			fclose($ff);
		}
?>