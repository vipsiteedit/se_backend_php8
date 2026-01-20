<?php

/**
 * Отправка письма с вложениями.
 *
 * @param string $subject       тема письма
 * @param string $to_email      email получателя
 * @param string $from_email    email отправителя (будет заменен на noreply@host)
 * @param string $msg           тело письма
 * @param string $contenttype   тип письма ('text/plain' или 'text/html')
 * @param string $filename      список файлов через ';' (имена в /files)
 * @param string $mimetype      MIME тип вложения
 * @param bool   $mime_filename не используется
 *
 * Пример:
 * $mailfile = new plugin_mail(
 *     "тема письма",
 *     "user@example.com",
 *     "sender@example.com",
 *     "Текст письма",
 *     "text/plain",
 *     "file1.jpg;file2.pdf",
 *     "application/octet-stream",
 *     false
 * );
 * $mailfile->sendfile();
 */
class plugin_mail
{
    private $mail;

    /**
     * Инициализирует письмо и добавляет текст/вложения.
     */
    public function __construct($subject, $to_email, $from_email, $msg,  $contenttype= '',$filename = '', $mimetype = "application/octet-stream", $mime_filename = false)
    {
        if (!$contenttype) {
            $contenttype = 'text/plain';
        }
        if (empty($from_email)) {
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $from_email = 'noreply@' . $host;
        }
        $this->mail = new plugin_jmail(stripslashes($subject), $to_email, $from_email);
        $this->mail->addtext($msg, $contenttype);
        if (!empty($filename)){
            $filelist = explode(';', $filename);
            foreach($filelist as $file){
                $file = trim($file);
                if (empty($file)) continue;
                $this->mail->attach($file, '', $mimetype);
            }
        }
    }

    /**
     * Отправляет письмо.
     *
     * @return bool
     */
    public function sendfile()
    {
        return $this->mail->send();
    }
}

?>