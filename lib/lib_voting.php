<?php
class voting
{
    private $vot_flag = false;
    private $vot_act = false;
    public $buttonstyle = '';
    public $idVoting = '';
    private $voting_e = 0;
    private $section = null;
    private $fnameip = '';
    private $fnamedat;
    private $razdel;
    private $page = '';
    private $width;
    public $number;
    public $summ;
    public $showhtml;
    private $textvoting;

    public function __construct($section, $idVoting, $voting_e = 0, $width = 0, $textvoting = '', $page = "")
    {
        if ($page == "") {
            $page = getRequest('page');
        }

        $this->textvoting = $textvoting;
        $this->page = $page;
        $this->width = intval($width);
        if ($this->width < 150) {
            $this->width = 150;
        }
        if ($this->width > 1000) {
            $this->width = 1000;
        }

        $this->section = $section;
        $this->idVoting = $idVoting;
        $this->voting_e = intval($voting_e);
        if (isRequest('razdel')) $this->razdel = getRequest('razdel', 1);
        else
            $this->razdel = intval($this->section->id);


        if (!empty($idVoting)) {
            $idVmd5 = substr(md5(str_replace("/", "", $this->idVoting)), 0, 5);
            $this->fnameip = "data/voting_" . $idVmd5 . "_ip.dat";
            $this->fnamedat = "data/voting_" . $idVmd5 . "_stat.dat";
        } else {
            $razdel = intval($this->section->id);
            $this->fnameip = "data/voting_" . $this->page . "_" . $this->razdel . "_ip.dat";
            $this->fnamedat = "data/voting_" . $this->page . "_" . $this->razdel . "_stat.dat";
        }
        $this->showhtml = '';
        $this->start();
    }

    public function start()
    {
        $razdel = intval($this->section->id);

        $this->vot_flag = true;
        $this->vot_act = false;

        if ((isRequest('GoTo_SHOW') && $razdel == $this->razdel) || ($this->checkIP($razdel, $this->idVoting))) {

            $this->showhtml = $this->GenHTML($this->width);
            $this->buttonstyle = "style='display:none;'";
            $this->vot_flag = false;
            return;
        }

        if (isRequest('GoTo_VOTING') && $razdel == $this->razdel) {
            $this->vot_act = true;
            $this->vot_flag = false;
            $this->buttonstyle = "style='display:none;'";
        }
    }

    public function votinglist()
    {
        $razdel = intval($this->section->id);
        $voting_e = intval($this->voting_e);
        if ($voting_e < 0) $voting_e = 0;
        if ($voting_e > 5) $voting_e = 5;

        $objcount = count($this->section->objects);
        $rait = array();
        if (!file_exists($this->fnamedat)) {
            $f = fopen($this->fnamedat, "w");
            flock($f, LOCK_EX);
            foreach ($this->section->objects as $line) {
                $rait[strval($line->id)] = 0;
            }
            $rait_ser = serialize($rait);
            fputs($f, $rait_ser);
            fflush($f);
            flock($f, LOCK_UN);
            fclose($f);
        }

        $number = array();
        if (file_exists($this->fnamedat)) {
            $f = file($this->fnamedat);
            $number = unserialize($f[0]);
            if (!empty($this->section->objects)) {
                foreach ($this->section->objects as $line) {
                    if (!isset($number[strval($line->id)])) {
                        $number[strval($line->id)] = 0;
                    }
                }
            }
        }

        if ($this->vot_act && isRequest('voting_radio')) {
            $_voting_radio = getRequest('voting_radio');

            if (!isset($number[$_voting_radio])) {
                $number[$_voting_radio] = 0;
            }
            $number[$_voting_radio] += 1;

            $f = fopen($this->fnamedat, "w");
            flock($f, LOCK_EX);
            $number_ser = serialize($number);
            fputs($f, $number_ser);
            fflush($f);
            flock($f, LOCK_UN);
            fclose($f);

            $ip = $_SERVER['HTTP_X_REAL_IP'];
            $f = fopen($this->fnameip, "a");
            flock($f, LOCK_EX);
            fputs($f, $ip . "\n");
            fflush($f);
            flock($f, LOCK_UN);
            fclose($f);
            header("Location: " . seMultiDir() . "/" . $this->page . '/');
            exit();
        }

        if ($this->vot_flag) return;
        $this->buttonstyle = "style='display:none;'";
    }

    public function execute()
    {

        if (file_exists($this->fnamedat)) {
            $number_ser = file($this->fnamedat);
            $number = unserialize($number_ser[0]);
        }

        $summ = 0;
        foreach ($number as $nn) $summ += $nn;


        if ($this->checkIP($this->section->id, $this->idVoting) || ($this->buttonstyle != '')) {

            return array('numlist' => $number, 'summ' => $summ);
        } else return false;
    }


    private function GenHTML()
    {
        $number_ser = file($this->fnamedat);
        $number = unserialize($number_ser[0]);
        $colgolos = array_sum($number);

        foreach ($this->section->objects as $value) {
            $id = strval($value->id);
            if (empty($value->field)) {
                $ListVote[$id]['color'] = "#000";
            } else {
                $ListVote[$id]['color'] = $value->field;
            }
            $ListVote[$id]['title'] = $value->title;
            if (!isset($number[$id])) {
                $number[$id] = 0;
            }
            $ListVote[$id]['number'] = trim($number[$id]);
        }
        $cont_vot = "<div style=\"width:" . $this->width . "px; border-left: 1px solid #000; border-bottom: 1px solid #000; padding-top: 10px;\">";

        foreach ($ListVote as $golos) {
            if ($golos['number'] > 0)
                $per = round($golos['number'] / ($colgolos / 100));
            else $per = 0;
            $cont_vot .= "<div style=\"width: " . $per . "%; height: 10px; margin-bottom: 5px; background: " . $golos['color'] . ";\">&nbsp;</div>";
        }
        $cont_vot .= "</div>";
        $cont_vot .= "<ul style=\"list-style: none;\">";
        foreach ($ListVote as $golos) {
            if ($golos['number'] > 0)
                $per = round($golos['number'] / ($colgolos / 100),  $this->voting_e);
            else $per = 0;
            $cont_vot .= "<li class=\"golos_txt\" style=\"color: " . $golos['color'] . "\">" . $golos['title'] . " " . $per . "%</li>";
        }
        $cont_vot .= "<li class=\"golos_txt\">{$this->textvoting}: " . $colgolos . "</li>";
        $cont_vot .= "</ul>";
        return $cont_vot;
    }

    private function checkIP()
    {
        $ip = $_SERVER['HTTP_X_REAL_IP'];
        if (!file_exists("data")) {
            mkdir("data", 0755);
        }


        if (file_exists($this->fnameip)) {
            if (date("d", filemtime($this->fnameip)) != date("d")) {
                unlink($this->fnameip);
            } else {
                $iplist = file($this->fnameip);
                foreach ($iplist as $str) {
                    if (trim($str) == $ip)
                        return true;
                }
            }
        }
        return false;
    }

    public function getResult()
    {
        if (!empty($this->showhtml)) {
            unset($this->section->objects);
        }
        return $this->showhtml;
    }
}
