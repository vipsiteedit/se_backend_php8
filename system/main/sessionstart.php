<?php

class SessionSaveHandler {
    protected $savePath;
    protected $sessionName;

    public function __construct() {
        session_set_save_handler(
            array($this, "open"),
            array($this, "close"),
            array($this, "read"),
            array($this, "write"),
            array($this, "destroy"),
            array($this, "gc")
        );
    }

    function open($savePath, $sessionName) {
        $this->savePath = $savePath;
        if (!is_dir($this->savePath)) {
            mkdir($this->savePath, 0777);
        }
        return true;
    }

    function close() {
        return true;
    }

    function read($id) {
        if (!file_exists("$this->savePath/sess_$id")) return '';
        $i = 0; $flag = false;
        while ($i<5 && empty($flag)) {
            if ($i>0) $this->log("Error read $this->savePath/sess_$id on ".date('H:i:s')." iteration $i");
            $flag = (string)file_get_contents("$this->savePath/sess_$id");
        }
        return (empty($flag)) ? '' : $flag;
    }

    function write($id, $data) {
        return file_put_contents("$this->savePath/sess_$id", $data) === false ? false : true;
    }

    function destroy($id) {
        $file = "$this->savePath/sess_$id";
        if (file_exists($file)) {
            unlink($file);
        }
        return true;
    }

    function gc($maxlifetime) {
        foreach (glob("$this->savePath/sess_*") as $file) {
            if (filemtime($file) + $maxlifetime < time() && file_exists($file)) {
            unlink($file);
        }
        }

        return true;
    }
    private function log($data) {
        $logfile = 'modules/sessionhandler/log.txt';
        $flag = (file_exists($logfile)) ? FILE_APPEND : null;
        return file_put_contents($logfile,(string)$data."\r\n",$flag);
    }
}
new SessionSaveHandler();

?>