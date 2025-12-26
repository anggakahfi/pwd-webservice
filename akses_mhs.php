<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cari Data Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h3 class="mb-4 text-center">Cari Data Mahasiswa Berdasarkan NIM</h3>

    <form method="GET" class="mb-4">
        <div class="mb-3">
            <label class="form-label">Masukkan NIM</label>
            <input type="text" name="nim" class="form-control" placeholder="Contoh: 230018055" 
                   value="<?= isset($_GET['nim']) ? htmlspecialchars($_GET['nim']) : ''; ?>">
        </div>
        <button type="submit" class="btn btn-primary">Cari Data</button>
    </form>

    <hr>

    <?php
    if (isset($_GET['nim'])) {
        $nim = trim($_GET['nim']);
        
        if (empty($nim)) {
            echo '<div class="alert alert-warning mt-4">Masukkan NIM terlebih dahulu.</div>';
        } else {
            $url = "http://localhost/pwd-webservice/getdatamhs.php?nim=" . urlencode($nim);

            $client = curl_init($url);
            
            if ($client === false) {
                echo '<div class="alert alert-danger mt-4">Gagal menginisialisasi koneksi.</div>';
            } else {
                curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($client, CURLOPT_TIMEOUT, 10);
                curl_setopt($client, CURLOPT_CONNECTTIMEOUT, 5);
                
                $response = curl_exec($client);
                $curl_error = curl_error($client);
                $http_code = curl_getinfo($client, CURLINFO_HTTP_CODE);
                curl_close($client);

                if ($response === false) {
                    echo '<div class="alert alert-danger mt-4">Gagal terhubung ke server: ' . htmlspecialchars($curl_error) . '</div>';
                } elseif ($http_code !== 200) {
                    echo '<div class="alert alert-danger mt-4">Data tidak ditemukan (HTTP ' . $http_code . ')</div>';
                } else {
                    $result = json_decode($response, true);

                    if (json_last_error() !== JSON_ERROR_NONE) {
                        echo '<div class="alert alert-danger mt-4">Gagal memproses response dari server.</div>';
                    } elseif (!isset($result['status'])) {
                        echo '<div class="alert alert-danger mt-4">Format response tidak valid.</div>';
                    } elseif ($result['status'] === "success" && isset($result['data'])) {
                        $mhs = $result['data'];
                        ?>
                        <h5 class="mt-4 text-success">Data ditemukan</h5>

                        <table class="table table-bordered mt-3">
                            <tr>
                                <th>NIM</th>
                                <td><?= htmlspecialchars($mhs['nim'] ?? ''); ?></td>
                            </tr>
                            <tr>
                                <th>Nama</th>
                                <td><?= htmlspecialchars($mhs['nama'] ?? ''); ?></td>
                            </tr>
                            <tr>
                                <th>Jenis Kelamin</th>
                                <td><?= htmlspecialchars($mhs['jkel'] ?? ''); ?></td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td><?= htmlspecialchars($mhs['alamat'] ?? ''); ?></td>
                            </tr>
                            <tr>
                                <th>Tanggal Lahir</th>
                                <td><?= htmlspecialchars($mhs['tgllhr'] ?? ''); ?></td>
                            </tr>
                        </table>
                        <?php
                    } else {
                        $message = isset($result['message']) ? htmlspecialchars($result['message']) : 'Data tidak ditemukan';
                        echo '<div class="alert alert-danger mt-4">' . $message . '</div>';
                    }
                }
            }
        }
    }
    ?>
</div>

</body>
</html>