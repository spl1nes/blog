<?php declare(strict_types=1);

$postBody = $_POST['payload'] ?? '';
$payload  = \json_decode($postBody, true);

if (isset($payload['organization'], $payload['organization']['login']) && $payload['organization']['login'] === 'Karaka') {
    \shell_exec('./build.sh > /dev/null 2>/dev/null &');

    echo 'Updating';
} else {
    echo 'Invalid payload';
}
