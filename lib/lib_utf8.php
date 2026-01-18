<?php

function utf8_strtolower($string)
{
  if (function_exists('mb_strtolower')) {
    return mb_strtolower($string, 'UTF-8');
  }
  $convert_to = array(
    "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u",
    "v", "w", "x", "y", "z", "б", "в", "ч", "з", "д", "е", "і", "ц",
    "ъ", "й", "к", "л", "м", "н", "о", "п", "р", "т", "у", "ф", "х", "ж", "и", "г", "ю", "ы", "э", "я", "щ",
    "ш", "ь", "а", "с"
  );
  $convert_from = array(
    "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U",
    "V", "W", "X", "Y", "Z", "Б", "В", "Ч", "З", "Д", "Е", "Ј", "Ц",
    "Ъ", "Й", "К", "Л", "М", "Н", "О", "П", "Р", "Т", "У", "Ф", "Х", "Ж", "И", "Г", "Ю", "Ы", "Э", "Я", "Щ",
    "Ш", "Ь", "А", "С"
  );
  return str_replace($convert_from, $convert_to, $string);
}

function utf8_strtoupper($string)
{
  if (function_exists('mb_strtoupper')) {
    return mb_strtoupper($string, 'UTF-8');
  }
  $convert_from = array(
    "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u",
    "v", "w", "x", "y", "z", "б", "в", "ч", "з", "д", "е", "і", "ц",
    "ъ", "й", "к", "л", "м", "н", "о", "п", "р", "т", "у", "ф", "х", "ж", "и", "г", "ю", "ы", "э", "я", "щ",
    "ш", "ь", "а", "с"
  );
  $convert_to = array(
    "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U",
    "V", "W", "X", "Y", "Z", "Б", "В", "Ч", "З", "Д", "Е", "Ј", "Ц",
    "Ъ", "Й", "К", "Л", "М", "Н", "О", "П", "Р", "Т", "У", "Ф", "Х", "Ж", "И", "Г", "Ю", "Ы", "Э", "Я", "Щ",
    "Ш", "Ь", "А", "С"
  );
  return str_replace($convert_from, $convert_to, $string);
}

function utf8_strlen($s)
{
  if (function_exists('mb_strlen')) {
    return mb_strlen($s, 'UTF-8');
  }
  return strlen($s);
}

function utf8_substr($s, $offset, $len = 'all')
{
  if ($offset < 0) $offset = utf8_strlen($s) + $offset;
  if ($len != 'all') {
    if ($len < 0) $len = utf8_strlen($s) - $offset + $len;
    $xlen = utf8_strlen($s) - $offset;
    $len = ($len > $xlen) ? $xlen : $len;
    preg_match('/^.{' . $offset . '}(.{0,' . $len . '})/us', $s, $tmp);
  } else {
    preg_match('/^.{' . $offset . '}(.*)/us', $s, $tmp);
  }
  return (isset($tmp[1])) ? $tmp[1] : false;
}

function utf8_strpos($haystack, $needle, $offset = 0)
{
  # get substring (if isset offset param)
  $offset = ($offset < 0) ? 0 : $offset;
  if ($offset > 0) {
    preg_match('/^.{' . $offset . '}(.*)/us', $haystack, $dummy);
    $haystack = (isset($dummy[1])) ? $dummy[1] : '';
  }

  # get relative pos
  $p = strpos($haystack, $needle);
  if ($haystack == '' or $p === false) return false;
  $r = $offset;
  $i = 0;

  # calc real pos
  while ($i < $p) {
    if (!isset($haystack[$i])) {
      break;
    }
    if (ord($haystack[$i]) < 128) {
      # ascii symbol
      $i = $i + 1;
    } else {
      # non-ascii symbol with variable length 
      # (handling first byte)
      $bvalue = decbin(ord($haystack[$i]));
      $i = $i + strlen(preg_replace('/^(1+)(.+)$/', '\1', $bvalue));
    }
    $r++;
  }
  return $r;
}

function utf8_substr_count($h, $n)
{
  # preparing $n for using in reg. ex.
  $n = preg_quote($n, '/');

  # select all matches
  preg_match_all('/' . $n . '/u', $h, $dummy);
  return count($dummy[0]);
}

function is_utf8($string)
{
  for ($i = 0; $i < strlen($string); $i++) {
    if (ord($string[$i]) < 0x80) continue;
    elseif ((ord($string[$i]) & 0xE0) == 0xC0) $n = 1;
    elseif ((ord($string[$i]) & 0xF0) == 0xE0) $n = 2;
    elseif ((ord($string[$i]) & 0xF8) == 0xF0) $n = 3;
    elseif ((ord($string[$i]) & 0xFC) == 0xF8) $n = 4;
    elseif ((ord($string[$i]) & 0xFE) == 0xFC) $n = 5;
    else return false;
    for ($j = 0; $j < $n; $j++) {
      if ((++$i == strlen($string)) || ((ord($string[$i]) & 0xC0) != 0x80))
        return false;
    }
  }
  return true;
}

function autoencode($string, $encoding = 'utf-8')
{
  if (is_utf8($string)) $detect = 'utf-8';
  else {
    $cp1251 = 0;
    $koi8u = 0;
    $strlen = strlen($string);
    for ($i = 0; $i < $strlen; $i++) {
      $code = ord($string[$i]);
      if (($code > 223 and $code < 256) or ($code == 179) or ($code == 180) or ($code == 186) or ($code == 191)) $cp1251++; // а-я, і, ґ, є, Ї 
      if (($code > 191 and $code < 224) or ($code == 164) or ($code == 166) or ($code == 167) or ($code == 173)) $koi8u++; // а-я, є, і, ї, ґ 
    }
    if ($cp1251 > $koi8u) $detect = 'windows-1251';
    else $detect = 'koi8-u';
  }
  if ($encoding == $detect) return $string;
  else return iconv($detect, $encoding, $string);
}
