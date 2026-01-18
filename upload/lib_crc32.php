<?php


function crc32_file($filename)
{
   $file_string = file_get_contents($filename);
   $crc = crc32($file_string);
   return sprintf("%u", $crc);
}


function crc32_file_($filename)
{
   $f = @fopen($filename, 'rb');
   if (!$f) return false;

   static $CRC32Table, $Reflect8Table;
   if (!isset($CRC32Table)) {
      $Polynomial = 0x04c11db7;
      $topBit = 1 << 31;

      for ($i = 0; $i < 256; $i++) {
         $remainder = $i << 24;
         for ($j = 0; $j < 8; $j++) {
            if ($remainder & $topBit)
               $remainder = ($remainder << 1) ^ $Polynomial;
            else $remainder = $remainder << 1;
         }

         $CRC32Table[$i] = $remainder;

         if (isset($Reflect8Table[$i])) continue;
         $str = str_pad(decbin($i), 8, '0', STR_PAD_LEFT);
         $num = bindec(strrev($str));
         $Reflect8Table[$i] = $num;
         $Reflect8Table[$num] = $i;
      }
   }

   $remainder = 0xffffffff;
   while ($data = fread($f, 1024)) {
      $len = strlen($data);
      for ($i = 0; $i < $len; $i++) {
         $byte = $Reflect8Table[ord($data[$i])];
         $index = (($remainder >> 24) & 0xff) ^ $byte;
         $crc = $CRC32Table[$index];
         $remainder = ($remainder << 8) ^ $crc;
      }
   }

   $str = decbin($remainder);
   $str = str_pad($str, 32, '0', STR_PAD_LEFT);
   $remainder = bindec(strrev($str));
   return 0x100000000 + ($remainder ^ 0xffffffff);
}

function crc32_text($text)
{
   if (empty($text)) return false;

   static $CRC32Table, $Reflect8Table;
   if (!isset($CRC32Table)) {
      $Polynomial = 0x04c11db7;
      $topBit = 1 << 31;

      for ($i = 0; $i < 256; $i++) {
         $remainder = $i << 24;
         for ($j = 0; $j < 8; $j++) {
            if ($remainder & $topBit)
               $remainder = ($remainder << 1) ^ $Polynomial;
            else $remainder = $remainder << 1;
         }

         $CRC32Table[$i] = $remainder;

         if (isset($Reflect8Table[$i])) continue;
         $str = str_pad(decbin($i), 8, '0', STR_PAD_LEFT);
         $num = bindec(strrev($str));
         $Reflect8Table[$i] = $num;
         $Reflect8Table[$num] = $i;
      }
   }

   $remainder = 0xffffffff;
   $len = strlen($text);
   for ($i = 0; $i < $len; $i++) {
      $byte = $Reflect8Table[ord($text[$i])];
      $index = (($remainder >> 24) & 0xff) ^ $byte;
      $crc = $CRC32Table[$index];
      $remainder = ($remainder << 8) ^ $crc;
   }

   $str = decbin($remainder);
   $str = str_pad($str, 32, '0', STR_PAD_LEFT);
   $remainder = bindec(strrev($str));
   return 0x100000000 + ($remainder ^ 0xffffffff);
}
