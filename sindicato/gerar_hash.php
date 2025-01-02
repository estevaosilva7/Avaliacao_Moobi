<?php
$senha_admin = '123456';
$hash_admin = password_hash($senha_admin, PASSWORD_BCRYPT);
echo "Hash da senha do admin: " . $hash_admin . "\n";

$senha_comum = '654321';
$hash_comum = password_hash($senha_comum, PASSWORD_BCRYPT);
echo "Hash da senha do comum: " . $hash_comum . "\n";

