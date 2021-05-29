<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Arsip extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
        $this->load->helper("url");
        $this->load->helper("server");
        $this->load->model("kurs_model");
	}
    
	public function index()
	{
        if(isset($_GET["file"]) == true){
            $id = decrypt_str($_GET["file"]);
            $out = $this->kurs_model->exists_id($id);
            if($out["exists"] == true){
                $file = upload_excel_path($out["direktori"]).$out["nama_file"];
                if (file_exists($file)) {
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="'.$out["nama_file"].'"');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($file));
                    readfile($file);
                    exit;
                }
            }
        }
	}
}