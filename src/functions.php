<?php
// This file contains various utility functions used throughout the project

function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function renderTemplate($template, $data = []) {
    extract($data);
    include "../templates/{$template}.html";
}

function redirect($url) {
    header("Location: $url");
    exit();
}
?>