<?php


if (!function_exists('download')){
function download($url, $filename){
 // $serverRSS=$url;

  preg_match('/\/\/(.+?)\//i',$url,$serverRSS);

  $serverRSS = $serverRSS[1];
  $fileRSS = explode('://',$url);
  $fileRSS = $fileRSS[1];


  //$fileRSS=explode('://',$rsspath);
  //$fileRSS=$fileRSS[1];
  $ittext="";

  @$fp = fsockopen($serverRSS, 80,$errno, $errstr, 10);
  if ($fp) {
   $pathalies = explode("/",$fileRSS);
   $fileRSS = substr($fileRSS,strlen($pathalies[0]));
   $pathfiles = $pathalies[0];

   fputs ($fp, "GET ".$fileRSS." HTTP/1.0\r\nHost: ".$pathfiles."\r\n\r\n");

   while ($data = fgets($fp,256)) $ittext.=$data;
   

   if (!preg_match("/encoding=[\'\"](utf-8)[\'\"]/m",$ittext)) {
     preg_match("/encoding=[\'\"](.+?)[\'\"]/m",$ittext,$encode);
     
     $encode=$encode[1];
     if (!empty($encode)) {
       $ittext = preg_replace("/encoding=[\'\"](.+?)[\'\"]/m","encoding=\"utf-8\"",$ittext);
       $ittext = iconv($encode,'utf-8',$ittext);
     } 
   }
   $itt =explode("<?xml",$ittext);
   $ittext="<?xml".@$itt[1];
     fclose($fp);
     
  if (!empty($ittext)) {
    $frss=se_fopen($filename,"wb");
    fputs($frss,$ittext);
    fclose($frss);
  }

    // $file = se_file_get_contents($ittext);
    // if ($file) file_put_contents($filename,$file);
  }
 }
}
?>