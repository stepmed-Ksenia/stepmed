<?php
declare(strict_types=1);
final class Auth {
    public static function start(): void { if (session_status()===PHP_SESSION_NONE) session_set_cookie_params(['httponly'=>true,'secure'=>!empty($_SERVER['HTTPS'])&&$_SERVER['HTTPS']!=='off','samesite'=>'Lax']); session_start(); }
    public static function env(): array { return parse_ini_file(dirname(__DIR__).'/.env') ?: []; }
    public static function check(): bool { return in_array($_SESSION['user']['role'] ?? '', ['owner','admin'], true); }
    public static function require(): void { if (!self::check()) { header('Location: /login.php'); exit; } }
    public static function login(string $password): bool { $expected=(string)(self::env()['ADMIN_PASSWORD'] ?? ''); if ($expected!=='' && hash_equals($expected,$password)) { session_regenerate_id(true); $_SESSION['user']=['id'=>1,'name'=>'Ксения Степанова','role'=>'owner']; return true; } return false; }
    public static function logout(): void { $_SESSION=[]; if(ini_get('session.use_cookies')){ $p=session_get_cookie_params(); setcookie(session_name(),'',['expires'=>time()-42000,'path'=>$p['path'],'domain'=>$p['domain'],'secure'=>$p['secure'],'httponly'=>$p['httponly'],'samesite'=>'Lax']); } session_destroy(); }
}

