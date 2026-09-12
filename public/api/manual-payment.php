<?php
declare(strict_types=1);
session_start();
header('Content-Type: application/json; charset=utf-8');
$role=$_SESSION['user']['role']??'';
if($role!=='owner'&&$role!=='admin'){http_response_code(403);echo json_encode(['ok'=>false]);exit;}
$input=json_decode((string)file_get_contents('php://input'),true);
if(!is_array($input)||!isset($input['invoice_id'],$input['amount'],$input['operation_id'])){http_response_code(400);echo json_encode(['ok'=>false,'error'=>'invoice_id_amount_operation_id_required']);exit;}
require dirname(__DIR__,2).'/app/Database.php';
try{$db=Database::connect();$db->beginTransaction();$q=$db->prepare('SELECT id,amount,status FROM invoices WHERE id=? FOR UPDATE');$q->execute([(int)$input['invoice_id']]);$invoice=$q->fetch();if(!$invoice)throw new RuntimeException('invoice_not_found');if((float)$invoice['amount']!==(float)$input['amount'])throw new RuntimeException('amount_mismatch');$q=$db->prepare('INSERT INTO payments (invoice_id,external_operation_id,amount,status,confirmed_by,confirmed_at) VALUES (?,?,?,?,?,NOW())');$q->execute([$invoice['id'],(string)$input['operation_id'],$input['amount'],'confirmed',$_SESSION['user']['id']??1]);$db->prepare("UPDATE invoices SET status='paid',paid_at=NOW() WHERE id=?")->execute([$invoice['id']]);$db->prepare('INSERT INTO audit_log (user_id,action,details) VALUES (?,?,?)')->execute([$_SESSION['user']['id']??1,'invoice.payment_confirmed',json_encode(['invoice_id'=>$invoice['id'],'operation_id'=>$input['operation_id']])]);$db->commit();echo json_encode(['ok'=>true,'status'=>'paid']);}catch(Throwable $e){if(isset($db)&&$db->inTransaction())$db->rollBack();http_response_code(422);echo json_encode(['ok'=>false,'error'=>$e->getMessage()]);}