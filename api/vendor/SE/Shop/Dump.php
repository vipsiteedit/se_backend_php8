<?php

namespace SE\Shop;

use SE\MySQLDump as MySQLDump;
use SE\Exception as Exception;
use SE\DB as DB;

class Dump extends Base
{
    public function info($id = null)
    {
        $filePath = DOCUMENT_ROOT . "/files/tmp";
        if (!file_exists($filePath) || !is_dir($filePath))
            mkdir($filePath);
        $fileName = HOSTNAME . '.sql.gz';
        $filePath .= "/{$fileName}";
        $urlFile = '//' . HOSTNAME . "/files/tmp/{$fileName}";

        try {
            $dump = new MySQLDump();
            $dump->save($filePath);

            if (file_exists($filePath) && filesize($filePath)) {
                $this->result['url'] = $urlFile;
                $this->result['name'] = $fileName;
            } else throw new Exception();
        } catch (Exception $e) {
            $this->error = "Не удаётся создать дамп базы данных для вашего проекта!";
            throw new Exception($this->error);
        }
    }

    public function post($tempFile = FALSE)
    {
        $this->error = "Не удаётся развернуть дамп базы данных для вашего проекта!";
        try {
            $filePath = DOCUMENT_ROOT . "/files/tmp";
            if (!file_exists($filePath) || !is_dir($filePath))
                mkdir($filePath);
            $fileName = $_FILES["file"]['name'];
            $fileName = $filePath . "/" . $fileName;
            if (!move_uploaded_file($_FILES["file"]['tmp_name'], $fileName))
                exit;

            $query = null;
            $lines = gzfile($fileName);
            foreach ($lines as $line) {
                $query .= $line;
            }

            if ($query) {
                DB::exec($query);
            }

            $this->error = null;
        } catch (Exception $e) {
            throw new Exception($this->error);
        }
    }
}
