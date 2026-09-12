<?php
declare(strict_types=1);
final class Auth {
    public static function env(): array { return parse_ini_file(dirname(__DIR__).'/.env') ?: []; }
    public static function check(): bool { return in_array($_SESSION['user']['role'] ?? '', ['owner','admin'], true); }
    public static function require(): void { if (!self::check()) { header('Location: /login.php'); exit; } }
    public static function login(string $password): bool { $expected=(string)(self::env()['ADMIN_PASSWORD'] ?? ''); if ($expected!=='' && hash_equals($expected,$password)) { $_SESSION['user']=['id'=>1,'name'=>'Ксения Степанова','role'=>'owner']; return true; } return false; }
}

