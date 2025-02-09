<?php

class Posts extends Controller
{

    public function index()
    {
        $data['judul'] = 'Posts';
        // head
        $this->view('templates/header', $data);

        $this->view('posts/index', $data);
        // footer
        $this->view('templates/footer');
    }
}
