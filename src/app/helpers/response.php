<?php
function sendJSONResponse($data)
{
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}
