<?php
//
//
//
//class Flasher
//{
//
//    public static function setFlash($pesan, $aksi, $tipe)
//    {
//        $_SESSION['flash'] = [
//            'pesan' => $pesan,
//            'aksi' => $aksi,
//            'tipe' => $tipe
//        ];
//    }
//
//
//    public static function flash()
//    {
//        if (isset($_SESSION['flash'])) {
//            echo '<div class="alert alert-' . $_SESSION['flash']['tipe'] . ' alert-dismissible fade show" role="alert">
//        <strong>' . $_SESSION['flash']['pesan'] . '</strong> Data Mahasiswa ' . $_SESSION['flash']['pesan'] . ' ' . $_SESSION['flash']['aksi'] . '
//        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
//      </div>';
//            unset($_SESSION['flash']);
//        }
//    }
//}


class Flasher
{
    public static function setFlash($pesan, $aksi, $tipe, $username = '')
    {
        $_SESSION['flash'] = [
            'pesan' => $pesan,
            'aksi' => $aksi,
            'tipe' => $tipe,
            'username' => $username
        ];
    }

    public static function flash()
    {
        if (isset($_SESSION['flash'])) {
            $username = !empty($_SESSION['flash']['username']) ? ', ' . $_SESSION['flash']['username'] : '';

            echo '<div id="flash-message" class="alert alert-' . $_SESSION['flash']['tipe'] . ' alert-dismissible fade show" role="alert">
                    ' . $_SESSION['flash']['pesan'] . $username . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';


            unset($_SESSION['flash']);
            session_write_close();

            // sembunyikan flash message setelah 5 detik
            echo '<script>
                    setTimeout(function() {
                        let flashMessage = document.getElementById("flash-message");
                        if (flashMessage) {
                            flashMessage.classList.remove("show");
                            setTimeout(() => flashMessage.remove(), 500); 
                        }
                    }, 5000);
                  </script>';
        }
    }
}




