<?php
session_start();

function setUserSession($userData)
{
    $_SESSION['user_id'] = $userData['id'];
    $_SESSION['name'] = $userData['name'];
    $_SESSION['email'] = $userData['email'];
    $_SESSION['subscription_type'] = $userData['subscription_type'];
    $_SESSION['logged_in'] = true;
    $_SESSION['last_activity'] = time();
}

function checkUserSession()
{
    // Cek apakah session ada
    if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
        return false;
    }

    // Cek timeout session (30 menit)
    if (time() - $_SESSION['last_activity'] > 1800) {
        destroyUserSession();
        return false;
    }

    // Update waktu aktivitas terakhir
    $_SESSION['last_activity'] = time();
    return true;
}

function destroyUserSession()
{
    // Hapus semua data session
    session_unset();
    session_destroy();
}

function requireLogin()
{
    if (!checkUserSession()) {
        header('Location: login.php');
        exit;
    }
}
