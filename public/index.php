<?php

$request_uri = $_SERVER['REQUEST_URI'];

if ($request_uri === '/users') {
    include __DIR__ . '/../src/app/views/users.html';
} else if ($request_uri === '/register') {
    include __DIR__ . '/../src/app/views/register.html';
}
else {
    echo '404 Not Found';
}
