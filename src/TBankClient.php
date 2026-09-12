<?php
declare(strict_types=1);
final class TBankClient {
 public function __construct(private string $terminalKey, private string $password, private bool $enabled=false) {}
 public function isEnabled(): bool { return $this->enabled && $this->terminalKey!=='' && $this->password!==''; }
 private function token(array $p): string { $p['Password']=$this->password; ksort($p); $s=''; foreach($p as $v) if(!is_array($v)) $s.=(string)$v; return hash('sha256',$s); }
 public function init(string $orderId,int $amount,string $description,string $notificationUrl): array { if(!$this->isEnabled()) return ['Success'=>false,'Status'=>'STUB','Message'=>'T-Bank integration is disabled']; $b=['TerminalKey'=>$this->terminalKey,'Amount'=>$amount,'OrderId'=>$orderId,'Description'=>$description,'NotificationURL'=>$notificationUrl]; $b['Token']=$this->token($b); return $this->request('/v2/Init',$b); }
 public function getState(string $paymentId): array { if(!$this->isEnabled()) return ['Success'=>false,'Status'=>'STUB','Message'=>'T-Bank integration is disabled']; $b=['TerminalKey'=>$this->terminalKey,'PaymentId'=>$paymentId]; $b['Token']=$this->token($b); return $this->request('/v2/GetState',$b); }
 public function verifyNotification(array $p): bool { if(!$this->isEnabled()||!isset($p['Token'])) return false; $t=(string)$p['Token']; unset($p['Token'],$p['Data'],$p['Receipt']); return hash_equals(strtolower($t),strtolower($this->token($p))); }
 private function request(string $path,array $body): array { $ch=curl_init('https://securepay.tinkoff.ru'.$path); curl_setopt_array($ch,[CURLOPT_POST=>true,CURLOPT_RETURNTRANSFER=>true,CURLOPT_HTTPHEADER=>['Content-Type: application/json'],CURLOPT_POSTFIELDS=>json_encode($body,JSON_UNESCAPED_UNICODE),CURLOPT_TIMEOUT=>15]); $raw=curl_exec($ch); $err=curl_error($ch); $code=(int)curl_getinfo($ch,CURLINFO_HTTP_CODE); curl_close($ch); if($raw===false) throw new RuntimeException('T-Bank request failed: '.$err); $data=json_decode($raw,true); if(!is_array($data)) throw new RuntimeException('T-Bank invalid response (HTTP '.$code.')'); return $data; }
}

