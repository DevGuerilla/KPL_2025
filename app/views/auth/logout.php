<div class="container">
    <div class="row">
        <div class="col">
            <h2>Logout</h2>
            <p>Are you sure you want to logout?</p>

            <form action="<?= BASEURL; ?>/auth/logout" method="POST">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

                <button type="submit">Yes, Logout</button>
                <a href="<?= BASEURL; ?>/home">Cancel</a>
            </form>
        </div>
    </div>
</div>
