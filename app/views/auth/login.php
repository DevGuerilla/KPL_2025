<?php

// Ambil waktu lockout yang tersisa
$lockoutKey = "attempt_login_" . $_SERVER['REMOTE_ADDR'];
$remainingTime = 0;

if (isset($_SESSION[$lockoutKey]) && $_SESSION[$lockoutKey]['count'] >= 5) {
    $elapsedTime = time() - $_SESSION[$lockoutKey]['first_attempt'];
    if ($elapsedTime < 300) {
        $remainingTime = 300 - $elapsedTime;
    }
}
?>

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-lg p-4" style="width: 400px;">
        <h2 class="text-center mb-3">Login</h2>

        <!-- Flash Messages -->
        <?php Flasher::flash(); ?>

        <!-- Alert jika terkena lockout -->
        <?php if ($remainingTime > 0): ?>
            <div class="alert alert-danger text-center">
                Too many failed attempts! Please try again in <strong><span id="timer"><?= $remainingTime ?></span></strong> seconds.
            </div>
        <?php endif; ?>

        <form action="<?= BASEURL; ?>/auth/doLogin" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $data['csrf_token']; ?>">

            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" id="email" class="form-control"
                       required <?= ($remainingTime > 0) ? 'disabled' : '' ?>>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" name="password" id="password" class="form-control"
                       required <?= ($remainingTime > 0) ? 'disabled' : '' ?>>
            </div>

            <button type="submit" class="btn btn-primary w-100"
                <?= ($remainingTime > 0) ? 'disabled' : '' ?>>Login</button>
        </form>

        <p class="text-center mt-3">Don't have an account? <a href="<?= BASEURL; ?>/auth/register">Register here</a></p>
    </div>
</div>

<script>
    function startCountdown() {
        let timerElement = document.getElementById("timer");
        if (!timerElement) return;

        let timeLeft = parseInt(timerElement.innerText);

        let countdown = setInterval(() => {
            timeLeft--;
            timerElement.innerText = timeLeft;

            if (timeLeft <= 0) {
                clearInterval(countdown);
                location.reload(); // Refresh halaman setelah waktu habis
            }
        }, 1000);
    }

    startCountdown();
</script>
