<?php
class Helper
{
  public static function dd($data)
  {
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die;
  }

  // make str to excerpt 2 setences and last word is ... dot dont forget to take all the html tag

  public static function excerpt(String $string, int $length = 150)
  {
    $string = strip_tags($string);
    if (strlen($string) > $length) {
      $string = substr($string, 0, $length);
      $string = substr($string, 0, strrpos($string, ' '));
      $string = $string . '...';
    }
    return $string;
  }

  // make strtime from datetime to time what i want, eg: 1j yang lalu, 1m yang lalu, when uunderr 1 minute "baru saja" in Indonesia, asia/jakarta
  public static function timeAgo(String $datetime)
  {
    $time = strtotime($datetime);
    $current = time();
    $diff = $current - $time;
    $second = $diff;
    $minute = round($diff / 60);
    $hour = round($diff / 3600);
    $day = round($diff / 86400);
    $week = round($diff / 604800);
    $month = round($diff / 2419200);
    $year = round($diff / 29030400);

    if ($second <= 60) {
      return 'baru saja';
    } else if ($minute <= 60) {
      return $minute . 'm yang lalu';
    } else if ($hour <= 24) {
      return $hour . 'j yang lalu';
    } else if ($day <= 7) {
      return $day . 'h yang lalu';
    } else if ($week <= 4) {
      return $week . 'm yang lalu';
    } else if ($month <= 12) {
      return $month . 'b yang lalu';
    } else {
      return $year . 't yang lalu';
    }
  }
}
