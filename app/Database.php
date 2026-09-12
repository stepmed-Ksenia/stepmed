<?php
declare(strict_types=1);
final class Database {
 public static function connect(): PDO {
  static $pdo; if ($pdo instanceof PDO) return $pdo;
  $env=parse_ini_file(dirname(__DIR__).'/.env'); if(!is_array($env)) throw new RuntimeException('Application configuration is missing');
  return $pdo=new PDO('mysql:host='.$env['DB_HOST'].';dbname='.$env['DB_NAME'].';charset=utf8mb4',$env['DB_USER'],$env['DB_PASSWORD'],[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
 }
}