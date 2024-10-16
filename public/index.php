<?php

$request_uri = $_SERVER['REQUEST_URI'];

if ($request_uri === '/users') {
    include __DIR__ . '/../src/app/views/users.html';
} else {
    echo '404 Not Found';
}
