<!DOCTYPE html>
<html>

<head>
    <title>Enkripsi & Dekripsi Teks</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        .container {
            margin-top: 50px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Enkripsi & Dekripsi Teks</h2>

        <form method="POST" action="">
            <div class="form-group">
                <label for="plaintext">Plaintext:</label>
                <textarea class="form-control" name="plaintext" id="plaintext" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="key">Key:</label>
                <input type="text" class="form-control" name="key" id="key" required>
            </div>
            <button type="submit" class="btn btn-primary" name="encrypt">Enkripsi</button>
            <button type="submit" class="btn btn-primary" name="decrypt">Dekripsi</button>
        </form>

        <?php
        // Fungsi untuk mengenkripsi teks
        function encrypt($plaintext, $key)
        {
            $ivSize = openssl_cipher_iv_length('AES-256-CBC');
            $iv = openssl_random_pseudo_bytes($ivSize);

            $ciphertext = openssl_encrypt($plaintext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

            $ciphertext = base64_encode($iv . $ciphertext);

            return $ciphertext;
        }

        // Fungsi untuk mendekripsi teks
        function decrypt($ciphertext, $key)
        {
            $ciphertext = base64_decode($ciphertext);

            $ivSize = openssl_cipher_iv_length('AES-256-CBC');
            $iv = substr($ciphertext, 0, $ivSize);

            $ciphertext = substr($ciphertext, $ivSize);

            $plaintext = openssl_decrypt($ciphertext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

            return $plaintext;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $plaintext = $_POST['plaintext'];
            $key = $_POST['key'];

            if (isset($_POST['encrypt'])) {
                $ciphertext = encrypt($plaintext, $key);
                echo '<div class="alert alert-success mt-3" role="alert">
                    Ciphertext: ' . $ciphertext . '
                </div>';
            } elseif (isset($_POST['decrypt'])) {
                $decryptedText = decrypt($plaintext, $key);
                echo '<div class="alert alert-success mt-3" role="alert">
                    Decrypted text: ' . $decryptedText . '
                </div>';
            }
        }
        ?>

    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>