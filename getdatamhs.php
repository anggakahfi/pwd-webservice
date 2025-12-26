<?php
header('Content-Type: application/json; charset=utf-8');

require 'koneksi.php';

if (!isset($conn) || $conn === false) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Koneksi database gagal."
    ]);
    exit;
}

if (!isset($_GET['nim']) || empty(trim($_GET['nim']))) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "Parameter NIM wajib diisi."
    ]);
    exit;
}

$nim = trim($_GET['nim']);

$sql = "SELECT * FROM mahasiswa WHERE nim = ?";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt === false) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Gagal menyiapkan query."
    ]);
    mysqli_close($conn);
    exit;
}

mysqli_stmt_bind_param($stmt, "s", $nim);

if (!mysqli_stmt_execute($stmt)) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Gagal mengeksekusi query."
    ]);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    exit;
}

$result = mysqli_stmt_get_result($stmt);

if ($result === false) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Gagal mengambil hasil query."
    ]);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    exit;
}

if (mysqli_num_rows($result) > 0) {
    $data = mysqli_fetch_assoc($result);
    
    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "data" => $data
    ], JSON_UNESCAPED_UNICODE);
} else {
    http_response_code(404);
    echo json_encode([
        "status" => "error",
        "message" => "Data tidak ditemukan."
    ]);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>