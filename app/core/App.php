<?php


class App
{
    // property
    protected $controller = 'Home';
    protected $method = 'index';
    protected $params = [];

    // method
    public function __construct()
    {
        $url = $this->parseURL();
        if (isset($url[0])) {
            // controler
            if (file_exists('../app/controllers/' . $url[0] . '.php')) {
                // jadi kontroler
                $this->controller = $url[0];
                // hilangin array ke 0
                unset($url[0]);
            }
        }
        require_once '../app/controllers/' . $this->controller . '.php';

        // mereka dapat controller dari sini buat di intansiasi
        $this->controller = new $this->controller;

        // method
        if (isset($url[1])) {
            // dari intansiasi controler atas
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // parameter
        if (!empty($url)) {
            // var_dump($url);
            $this->params = array_values($url);
        }

        // jalankan controller dan method serta params jika ada

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseURL()
    {
        if (isset($_GET['url'])) {
            // slash akhir jangan ada
            $url = rtrim($_GET['url'], '/');
            // bersihin
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
    }
}
