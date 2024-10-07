<?php
if (isset($_GET['buyer_id'])) {
    $buyer_id = $_GET['buyer_id'];

    // Database connection
    try {
        $dsn = 'mysql:host=localhost;dbname=exportsoftware';
        $username = 'root';
        $password = '';
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);

        $stmt = $pdo->prepare("
            SELECT b.bank_name, b.ifsc_code, b.gstin, b.vat_tin, b.iec_code, b.bank_account_no
            FROM buyers AS bi
            JOIN banks AS b ON bi.bank_id = b.id
            WHERE bi.id = :buyer_id
        ");
        $stmt->execute([':buyer_id' => $buyer_id]);

        $bank_details = $stmt->fetch();

        if ($bank_details) {
            echo json_encode($bank_details);
        } else {
            echo json_encode(null);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error: ' . $e->getMessage()]);
    }
}
?>
