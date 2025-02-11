<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-lg p-4" style="width: 400px;">
        <h2 class="text-center mb-4">Register</h2>

        <!-- Flash Message -->
        <?php Flasher::flash(); ?>

        <!-- Alert jika terkena delay -->
        <?php if ($data['show_delay_warning']): ?>
            <div class="alert alert-warning text-center">
                Too many failed attempts! Please wait <strong>3 seconds</strong> before retrying.
            </div>
        <?php endif; ?>

        <form action="<?= BASEURL; ?>/auth/doRegister" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $data['csrf_token']; ?>">

            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" name="username" id="username" class="form-control" required value="<?= $_POST['username'] ?? '' ?>">
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Full Name:</label>
                <input type="text" name="name" id="name" class="form-control" required value="<?= $_POST['name'] ?? '' ?>">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" id="email" class="form-control" required value="<?= $_POST['email'] ?? '' ?>">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success w-100">Register</button>
        </form>

        <p class="text-center mt-3">Already have an account? <a href="<?= BASEURL; ?>/auth/login">Login here</a></p>
    </div>
</div>
