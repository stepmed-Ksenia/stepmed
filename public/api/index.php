<?php
declare(strict_types=1);
session_start();
require_once dirname(__DIR__,2).'/app/Auth.php';
if (!Auth::check()) { http_response_code(401); header('Content-Type: application/json; charset=utf-8'); echo json_encode(['ok'=>false,'error'=>'unauthorized']); exit; }
header('Content-Type: application/json; charset=utf-8');
$resource=preg_replace('/[^a-z0-9_-]/i','',(string)($_GET['resource']??'overview'));
$send=static function(array $v): never { echo json_encode($v,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES); exit; };
$lessons=[['time'=>'10:00','student'=>'Алексей П.','subject'=>'Химия','payment'=>'paid'],['time'=>'13:30','student'=>'Мария К.','subject'=>'Биология','payment'=>'unpaid'],['time'=>'18:00','student'=>'Группа 9А','subject'=>'Химия','payment'=>'paid']];
if($resource==='overview')$send(['metrics'=>['lessons_today'=>3,'unpaid_invoices'=>2,'open_exceptions'=>1,'teachers'=>4],'lessons'=>$lessons]);
if($resource==='invoices')$send(['items'=>[['id'=>'INV-001','student'=>'Алексей П.','amount'=>2500,'status'=>'paid'],['id'=>'INV-002','student'=>'Мария К.','amount'=>2200,'status'=>'unpaid']]]);
if($resource==='exceptions')$send(['items'=>[['title'=>'Неоплаченный счёт','details'=>'Мария К. · занятие сегодня в 13:30']]]);
if($resource==='students')$send(['items'=>[['id'=>1,'name'=>'Алексей П.'],['id'=>2,'name'=>'Мария К.']]]);
if($resource==='lessons')$send(['items'=>$lessons]);
if(in_array($resource,['attendance','accruals'],true))$send(['items'=>[]]);
http_response_code(404);$send(['ok'=>false,'error'=>'not_found']);
