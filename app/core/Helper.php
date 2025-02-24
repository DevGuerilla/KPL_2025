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


  // strtime date with humanize in indonesia format "23 Sep 2024 12:00 WIB"
  public static function date(String $datetime)
  {
    $date = date_create($datetime);
    $date = date_format($date, 'd M Y H:i');
    return $date . ' WIB';
  }

  public static function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
  }

  public static function validateCSRFToken($token) {
    if (!isset($_SESSION['csrf_token']) || empty($token)) {
      return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
  }
}
