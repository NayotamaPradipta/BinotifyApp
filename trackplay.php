<?php
    session_start();
    header('Content-Type: application/json');
    $isLogged = isset($_SESSION['username']) ? true : false; 

    if (!$isLogged){ 
        if (!isset($_SESSION['audio_play_count'])) {
            $_SESSION['audio_play_count'] = 0;
        }

        if ($_SESSION['audio_play_count'] < 3) { 
            $_SESSION['audio_play_count']++;
            echo json_encode(['success' => true, 'play_count' => $_SESSION['audio_play_count']]);
        } else { 
            echo json_encode(['success' => false, 'message' => 'You have reached the maximum number of plays. Please log in to continue']);
        }

    } else { 
        echo json_encode(['success' => true, 'message' => 'Logged in users can play without restriction.']);
    }
?>