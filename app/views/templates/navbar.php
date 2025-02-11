<!--<nav class="navbar navbar-expand-lg bg-body-tertiary">-->
<!--    <div class="container">-->
<!--        <a class="navbar-brand" href="--><?php //= BASEURL; ?><!--">PHP MVC</a>-->
<!--        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">-->
<!--            <span class="navbar-toggler-icon"></span>-->
<!--        </button>-->
<!--        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">-->
<!--            <div class="navbar-nav">-->
                <!-- controller -->
<!--                <a class="nav-link active" aria-current="page" href="--><?php //= BASEURL; ?><!--">Home</a>-->
<!--                <a class="nav-link" href="--><?php //= BASEURL; ?><!--/posts">Posts</a>-->
<!--            </div>-->
<!--        </div>-->
<!---->
<!--    </div>-->
<!--</nav>-->

<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container">
        <a class="navbar-brand" href="<?= BASEURL; ?>">PHP MVC</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-link active" aria-current="page" href="<?= BASEURL; ?>">Home</a>
                <a class="nav-link" href="<?= BASEURL; ?>/posts">Posts</a>
            </div>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    Hello, <?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest'; ?>!
                </span>

                <?php if (isset($_SESSION['user_id'])) : ?>
                    <a class="nav-link" href="<?= BASEURL; ?>/auth/logout">Logout</a>
                <?php else : ?>
                    <a class="nav-link" href="<?= BASEURL; ?>/auth/login">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>


<?php if (isset($_SESSION['flash'])): ?>
    <?php Flasher::flash(); ?>
<?php endif; ?>

<?php
// Cek apakah user sudah login
$username = $_SESSION['username'] ?? 'Guest';
?>

<h3 class="text-flex m-3">Hello, <?= htmlspecialchars($username); ?>!</h3>
