<?php
require_once '../../includes/auth_check.php';
require_once '../../config/db.php';

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    // Because of ON DELETE CASCADE in the schema, inventory items will be automatically deleted
    $stmt = $conn->prepare("DELETE FROM venues WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

header("Location: index.php");
exit();
?>