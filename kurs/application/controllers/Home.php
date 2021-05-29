<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
        $this->load->helper("url");
        $this->load->helper("server");
        $this->load->model("kurs_model");
	}
	public function index()
	{
        $this->load->view("home/home_view", [
            "title" => "Home <small>Selamat datang.</small>",
            "navigasi" => $this->load->view("navigasi/navigasi_view", ["active_index" => 0], true),
            "content" => $this->load->view("kurs/kurs_view", [
                "list" => $this->kurs_model->list_kurs(),
                "message" => "",
                "color" => ""
            ], true)
        ]);
	}
}