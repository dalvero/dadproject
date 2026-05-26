<?php

function validate_required($value, $fieldName) {
    if (!isset($value) || trim($value) === '') {
        return "$fieldName wajib diisi.";
    }
    return null;
}

function validate_email($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Format email tidak valid.";
    }
    return null;
}

function validate_min_length($value, $min, $fieldName) {
    if (strlen($value) < $min) {
        return "$fieldName minimal $min karakter.";
    }
    return null;
}

function validate_max_length($value, $max, $fieldName) {
    if (strlen($value) > $max) {
        return "$fieldName maksimal $max karakter.";
    }
    return null;
}

function validate_in($value, $allowed, $fieldName) {
    if (!in_array($value, $allowed)) {
        return "$fieldName tidak valid.";
    }
    return null;
}

function validate_integer($value, $fieldName) {
    if (!is_numeric($value) || (int)$value != $value) {
        return "$fieldName harus berupa angka.";
    }
    return null;
}

function validate_positive($value, $fieldName) {
    if ((int)$value <= 0) {
        return "$fieldName harus lebih besar dari 0.";
    }
    return null;
}

function validate_file_upload($file, $allowedTypes, $maxSize, $fieldName) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return null; // file opsional, skip jika tidak ada
    }

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedTypes)) {
        return "Tipe file $fieldName tidak diizinkan. Hanya: " . implode(', ', $allowedTypes);
    }

    if ($file['size'] > $maxSize) {
        $maxMB = round($maxSize / 1024 / 1024);
        return "Ukuran $fieldName maksimal {$maxMB}MB.";
    }

    return null;
}

function set_validation_error($message) {
    $_SESSION['validation_error'] = $message;
}

function get_validation_error() {
    $msg = $_SESSION['validation_error'] ?? null;
    unset($_SESSION['validation_error']);
    return $msg;
}
