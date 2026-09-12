<?php
declare(strict_types=1);
session_start();
$_SESSION['user'] ??= ['name'=>'Ксения','role'=>'owner'];
?><!doctype html><html lang="ru"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>StepMed — дэшборд</title><link rel="stylesheet" href="/app.css"></head><body><main><p>ОПЕРАЦИОННЫЙ ЦЕНТР</p><h1>Добрый день, Ксения</h1><p>Локальный прототип StepMed</p><a href="/admin/">Открыть дэшборд</a></main></body></html>