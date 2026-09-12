<?php declare(strict_types=1); require dirname(__DIR__).'/app/Auth.php'; Auth::start(); Auth::logout(); header('Location: /login.php'); exit;

