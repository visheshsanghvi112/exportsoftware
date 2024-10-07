<?php
if (isset($_POST['id'])) {
    $id = $_POST['id'];
    
    try {
        $dsn = 'mysql:host=localhost;dbname=exportsoftware';
        $username = 'root';
        $password = '';
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        
        $stmt = $pdo->prepare("SELECT gstin, vat_tin, iec_code, bank_name, bank_account_no FROM buyers WHERE id = ?");
        $stmt->execute([$id]);
        $buyer = $stmt->fetch();
        
        echo json_encode($buyer);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>
