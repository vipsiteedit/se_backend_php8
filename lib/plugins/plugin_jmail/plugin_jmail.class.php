<?php

/** -------------------------------------------------------------- //
 * Отправка почты с аттачем
 * @param string $subject	-	тема_письма
 * @param string $to_email	-	мыло@получателя
 * @param string $from_email	-	мыло@отправителя
 * @param string $msg		-	тело_письма(если нужно)
 * @param string $contenttype	Тип письма ('text/plain' - текст, 'text/html' - html)
 * @param string $filename	-	имя_файла
 * @param string $filepath	-	путь_к_файлу
 * @param string $mimetype	-	тип_аттача(например image/jpeg или application/octet-stream)
 * @param string $mime_filename - определять тип по имени
 * Пример: 
 * 
 * $mailfile = new plugin_mail("тема_письма",
 * 			"мыло@получателя",
 * 			"мыло@отправителя");
 * $content_id = $mailfile->attach(array( 
 * 						array(
 *							'filename' => "имя_файла"
 *							'filepath' => "путь_к_файлу",
 *							'mime'	   => "тип_аттача(например image/jpeg)"
 *						)
 *					);
 * $mailfile->addtext(тело_письма, mime-type);
 * $mailfile->send();
 **/
class plugin_jmail
{
    private $subject;
    private $addr_to = '';
    private $body = '';
    private $headers;
    private $domainEmail = ''; 

    private $messages = array();
    private $attaches = array();

    private $boundary;
    private $eol;

    public function __construct($subject, $to_email, $from_email)
    {
        $this->boundary = '==================' . strtoupper(uniqid()) . '==';
        $this->eol = "\r\n";
        //smtp_headers
        $this->subject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
        $this->addr_to = $to_email;
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

        if (strpos($from_email, '@mail.')!==false || strpos($from_email, '@bk.')!==false || strpos($from_email, '@list.')!==false || strpos($from_email, '@inbox.')!==false) {
            $from_email = 'noreply@' . $host;
        }
        
        $this->domainEmail = 'admin@' . $host;

        //headers
        $this->headers = "From: " . $from_email . $this->eol;
        //$this->headers .= "To: " . $to_email . "\n";
        //$this->headers .= "Subject: " . $subject . "\n";
        $this->headers .= "Reply-To: " . $from_email . $this->eol;
        $this->headers .= "X-Mailer: PHP5" . $this->eol;
        $this->headers .= "X-Sender: " . $from_email . $this->eol;
        $this->headers .= "Mime-Version: 1.0" . $this->eol;
        $this->headers .= "Content-Type: multipart/mixed; ";
        $this->headers .= "boundary=\"" . $this->boundary . "\"" . $this->eol . $this->eol;

    }
    public function attach($filename = '', $filepath = '', $mime = '', $cid = false)
    {
        if (empty($mime))
            $mime = 'application/octet-stream';
        $content_id = $cid ? $cid : uniqid();
        $this->attaches[] = array('filename' => $filename, 'filepath' => $filepath, 'mime' => $mime, 'cid' => $content_id);
        return $content_id;
    }
    public function addtext($message, $mime)
    {
        if (empty($mime))
            $mime = 'text/plain';
        $this->messages[] = array('message' => $message, 'mime' => $mime);
    }

    private function encode_file($sourcefile)
    {
        $encoded = '';
        if (is_readable($sourcefile))
        {
            $fd = fopen($sourcefile, "rb");
            if ($fd !== false) {
                $size = filesize($sourcefile);
                $contents = ($size > 0) ? fread($fd, $size) : '';
                $encoded = $this->my_chunk_split(base64_encode($contents));
                fclose($fd);
            }
        }
        return $encoded;
    }

    private function my_chunk_split($str)
    {
        $stmp = $str;
        $len = strlen($stmp);
        $out = "";
        while ($len > 0)
        {
            if ($len >= 76)
            {
                $out = $out . substr($stmp, 0, 76) . "\r\n";
                $stmp = substr($stmp, 76);
                $len = $len - 76;
            } else
            {
                $out = $out . $stmp . "\r\n";
                $stmp = "";
                $len = 0;
            }
        }
        return $out;
    }

    private function create()
    {
        foreach ($this->messages as $message)
        {
            $this->body .= '--' . $this->boundary . $this->eol;
            $this->body .= "Content-Type: " . $message['mime'] . "; charset=\"UTF-8\"" . $this->eol;
            $this->body .= "Content-Transfer-Encoding: 8bit" . $this->eol;
            $this->body .= $this->eol . $message['message'] . $this->eol . $this->eol;
        }
        foreach ($this->attaches as $attach)
        {
    	    if (substr($attach['filename'], 0, 1) == '/'){
    		$ffr = true;
    	    } else {
    		$ffr = false;
    	    }
    	    
            $this->body .= '--' . $this->boundary . $this->eol;
            if (!$ffr){
        	$this->body .= "Content-Type: " . $attach['mime'] . "; name=\"" . $attach['filename'] . "\"" . $this->eol;
            } else {
                $parts = explode('/', $attach['filename']);
                $filename = end($parts);
        	$this->body .= "Content-Type: " . $attach['mime'] . "; name=\"" . $filename . "\"" . $this->eol;
            }
            if (!$ffr){
        	$this->body .= "Content-disposition: attachment; name=\"" . $attach['filename'] . "\"" . $this->eol;
    	    } else {
                $parts = explode('/', $attach['filename']);
                $filename = end($parts);
    		$this->body .= "Content-disposition: attachment; name=\"" . $filename . "\"" . $this->eol;
    	    }
            if ($attach['cid'])
                $this->body .= "Content-ID: <" . $attach['cid'] . ">" . $this->eol;
            $this->body .= "Content-Transfer-Encoding: base64" . $this->eol;
            if (!empty($attach['filepath']) && substr($attach['filepath'], 0, 1) !== '/')
                $attach['filepath'] = '/' . $attach['filepath'];
            if (!empty($attach['filepath']) && substr($attach['filepath'], -1, 1) == '/')
                $attach['filepath'] = substr($attach['filepath'], 0, -1);
            if (!$ffr){
        	$this->body .= $this->eol . $this->encode_file($_SERVER['DOCUMENT_ROOT'] . '/files' . $attach['filepath'] . '/' . $attach['filename']) . $this->eol;
    	    } else {
    	    	$this->body .= $this->eol . $this->encode_file($attach['filename']) . $this->eol;
    	    }
        }
        $this->body .= '--' . $this->boundary . "--";
    }

    public function show_headers()
    {
        echo '<pre>';
        echo $this->headers;
        echo $this->body;
        echo '</pre>';
    }

    // Отправка письма
    public function send()
    {
        $this->create();
        //return
        return mail($this->addr_to, $this->subject, $this->body, $this->headers, '-f'.$this->domainEmail);
    }
}
?>
