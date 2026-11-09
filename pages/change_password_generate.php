<?php
require_once '../includes/auth_check.php';
require_once '../config/db.php';
require_once '../includes/header.php';
require_once '../includes/navbar.php';

$generated_password = '';
$hashed_password    = '';
$length             = 16;
$use_special        = true;
$use_numbers        = true;
$use_upper          = true;
$use_lower          = true;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $length      = max(8, min(32, (int)($_POST['length'] ?? 16)));
    $use_special = isset($_POST['special']);
    $use_numbers = isset($_POST['numbers']);
    $use_upper   = isset($_POST['upper']);
    $use_lower   = isset($_POST['lower']);

    // Character sets
    $lower   = 'abcdefghijklmnopqrstuvwxyz';
    $upper   = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $numbers = '0123456789';
    $special = '!@#$%^&*()_+-=[]{}|;:,.<>?';

    $chars = '';
    if ($use_lower)   $chars .= $lower;
    if ($use_upper)   $chars .= $upper;
    if ($use_numbers) $chars .= $numbers;
    if ($use_special) $chars .= $special;

    if (empty($chars)) {
        $error = "Please select at least one character type.";
    } else {
        // Generate password
        $generated_password = '';
        $max = strlen($chars) - 1;
        for ($i = 0; $i < $length; $i++) {
            $generated_password .= $chars[random_int(0, $max)];
        }

        // Hash it (same method used in your login system)
        $hashed_password = password_hash($generated_password, PASSWORD_DEFAULT);
    }
}
?>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <h2 class="mb-4">Generate Secure Password</h2>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="card mb-4">
            <div class="card-body">
                <form method="post">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Password Length</label>
                            <input type="number" name="length" class="form-control" 
                                   min="8" max="32" value="<?= $length ?>" required>
                        </div>

                        <div class="col-12 mt-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="lower" id="lower" <?= $use_lower ? 'checked' : '' ?>>
                                <label class="form-check-label" for="lower">Lowercase (a-z)</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="upper" id="upper" <?= $use_upper ? 'checked' : '' ?>>
                                <label class="form-check-label" for="upper">Uppercase (A-Z)</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="numbers" id="numbers" <?= $use_numbers ? 'checked' : '' ?>>
                                <label class="form-check-label" for="numbers">Numbers (0-9)</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="special" id="special" <?= $use_special ? 'checked' : '' ?>>
                                <label class="form-check-label" for="special">Special chars (!@#$%...)</label>
                            </div>
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                Generate Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php if ($generated_password): ?>
            <div class="card border-success mb-4">
                <div class="card-header bg-success text-white">
                    <strong>Generated Password</strong>
                </div>
                <div class="card-body">
                    <h4 class="font-monospace mb-3" style="letter-spacing: 1px;">
                        <?= htmlspecialchars($generated_password) ?>
                    </h4>

                    <div class="d-flex gap-2 mb-3">
                        <button class="btn btn-outline-secondary btn-sm" 
                                onclick="navigator.clipboard.writeText('<?= htmlspecialchars($generated_password) ?>'); alert('Password copied!')">
                            Copy Password
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" 
                                onclick="navigator.clipboard.writeText('<?= htmlspecialchars($hashed_password) ?>'); alert('Hash copied!')">
                            Copy Hash
                        </button>
                    </div>

                    <hr>

                    <label class="form-label fw-bold">Hashed version (ready for database):</label>
                    <div class="input-group input-group-lg">
                        <textarea class="form-control font-monospace" rows="3" readonly style="font-size: 0.95rem;"><?= htmlspecialchars($hashed_password) ?></textarea>
                    </div>

                    <div class="mt-3 small text-muted">
                        You can use this hash directly in SQL:<br>
                        <code>UPDATE admins SET password = '<?= htmlspecialchars(substr($hashed_password, 0, 20)) ?>...' WHERE username = 'admin';</code>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="alert alert-info mt-4">
            <strong>Recommendation:</strong><br>
            • Use at least 14–16 characters<br>
            • Include all character types<br>
            • Never reuse passwords across systems<br>
            • Store only the hash — never the plain password
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>