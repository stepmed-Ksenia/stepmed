<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
if($_SERVER['REQUEST_METHOD']!=='POST'){http_response_code(405);echo json_encode(['ok'=>false]);exit;}
$input=json_decode((string)file_get_contents('php://input'),true);
if(!is_array($input)||empty($input['invoice_id'])){http_response_code(400);echo json_encode(['ok'=>false,'error'=>'invoice_id_required']);exit;}
echo json_encode(['ok'=>true,'status'=>'manual_review','invoice_id'=>$input['invoice_id']],JSON_UNESCAPED_UNICODE);