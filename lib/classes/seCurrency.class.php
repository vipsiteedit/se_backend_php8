<?php
  
class seCurrency {

	private static $instance = null;
	private $curses;
	private $titles;
	private $basecurr = '';

	public function __construct($lang = 'rus')
   	{
		$this->basecurr = trim(se_db_fields_item("main", "lang='$lang'",'basecurr'));
		
		$this->curses = array();
		$this->titles = array();
		$qr = se_db_query("SELECT mt.name, mt.title, mt.name_front, mt.name_flang, mt.minsum
        FROM `money_title` mt
        WHERE mt.lang = '$lang'");
        if (!empty($qr))
           while ($line = se_db_fetch_assoc($qr)){
             if (empty($line['minsum'])) $line['minsum'] = 0.01;
             $this->titles[] = $line;
           }
		//m.date_replace
		$qr = se_db_query("SELECT m.name, m.kurs, m.date_replace
		FROM `money` m
		LEFT JOIN money_title mt ON (m.money_title_id = mt.id)
		WHERE mt.lang = '$lang' ORDER BY `date_replace` asc");
		if (!empty($qr))
		while ($line = se_db_fetch_assoc($qr))
		{
  			if ($line['kurs'] > 0)
	  		{
				$this->curses[$line['date_replace']][$line['name']]['name'] = trim($line['name']);
	  			$this->curses[$line['date_replace']][$line['name']]['kurs'] = $line['kurs'];
	  		}
		}
	}

	public static function getInstance($lang = 'rus') 
  	{
    	if (self::$instance === null) {
      		self::$instance = new self($lang);
    	}
    	return self::$instance;
  	}

    private function isCurr($curr, $thiscurr)
    {
        $curr = strval($curr);
        $thiscurr = strval($thiscurr);
        if (in_array($curr, array('RUB', 'RUR')) && in_array($thiscurr, array('RUB', 'RUR'))){
            return true;
        } elseif (in_array($curr, array('BYR', 'BEL')) && in_array($thiscurr, array('BYR', 'BEL'))){
            return true;
        } elseif (in_array($curr, array('KZT', 'KAT')) && in_array($thiscurr, array('KZT', 'KAT'))){
            return true;
        } elseif($curr == $thiscurr){
            return true;
        }
    }


    public function getCurrData($curr)
    {
        $curr = strval($curr);
        foreach($this->titles as $value ){
           if ($this->isCurr($curr, $value['name'])) {
              return $value;
           }
        }
    }	

    private function check($value, $curr)
    {
        $curr = strval($curr);
        if ($curr == 'RUB' || $curr == 'RUR') {
			if (!empty($value['RUR']['name']) || !empty($value['RUB']['name'])){
				return (!empty($value['RUR']['name'])) ? 'RUR' : 'RUB';
			}
        } elseif ($curr == 'BYR' || $curr == 'BEL') {
			if (!empty($value['BYR']['name']) || !empty($value['BEL']['name'])){
				return (!empty($value['BYR']['name'])) ? 'BYR' : 'BEL';
			}
        } elseif ($curr == 'KZT' || $curr == 'KAT') {
			if ( !empty($value['KZT']['name']) || !empty($value['KAT']['name'])) {
				return (!empty($value['KZT']['name'])) ? 'KZT' : 'KAT';
			}
        } else {
			return strval($curr);
        }
    }

    private function getKurs($value, $curr)
    {
        $curr = strval($curr);
        $getcurr = $this->check($value, $curr);
        if(!empty($value[strval($getcurr)]['kurs'])) {
            return $value[strval($getcurr)]['kurs'];
        } else {
            return 1;
	    }
    }
	
  	public function convert($datavalue, $currstart = 'RUR', $currend = 'RUR', $datestart = '')
  	{
		$currstart = strval($currstart);
		$currend = strval($currend);

		if (strtolower($currstart) == strtolower($currend)){
			return $datavalue;
		}
    
		if (empty($datestart)) {
			$datestart = date('Y-m-d');
		}
		$curs1 = $curs2 = 1;
		foreach($this->curses as $date=>$value ) {
			if (trim($this->basecurr)!=trim($currstart)
			&& $date <= $datestart
			&& $this->check($value, $currstart)) {
				if ($curs = $this->getKurs($value, $currstart))
					$curs1 = $curs;
			}

			if (trim($this->basecurr)!=trim($currend)
			&& $date <= $datestart
			&& $this->check($value, $currend)) {
				if ($curs = $this->getKurs($value, $currend))
					$curs2 = $curs;
			}
		}
		return ($curs1 > 0) ? ($datavalue * $curs1 / $curs2) : $datavalue;
  	}
}
?>