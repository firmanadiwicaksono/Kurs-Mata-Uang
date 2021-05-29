<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Load library phpspreadsheet
require("./phpspreadsheet/vendor/autoload.php");
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
// End load library phpspreadsheet

class Maintenance extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
        $this->load->helper("url");
        $this->load->helper("server");
        $this->load->model("kurs_model");
        $this->load->library("upload");
	}
	public function index()
	{
        if(isset($_GET["tambah"]) == true){
            $this->load->view("home/home_view", [
                "title" => "Tambah <small>Silahkan menambahkan file arsip kurs.</small>",
                "navigasi" => $this->load->view("navigasi/navigasi_view", ["active_index" => -1], true),
                "content" => $this->load->view("maintenance/maintenance_tambah_view", [
                    "message" => "",
                    "color" => ""
                ], true)
            ]);
        }else
        if(isset($_GET["hapus"]) == true){
            $id = decrypt_str($_GET["hapus"]);
            $out = $this->kurs_model->exists_id($id);
            if($out["exists"] == true){
                $this->load->view("home/home_view", [
                    "title" => "Hapus <small>Silahkan menghapus file arsip kurs.</small>",
                    "navigasi" => $this->load->view("navigasi/navigasi_view", ["active_index" => -1], true),
                    "content" => $this->load->view("maintenance/maintenance_hapus_view", [
                        "id" => $id,
                        "mata_uang" => $out["mata_uang"],
                        "nilai" => $out["nilai"],
                        "nama_file" => $out["nama_file"],
                        "message" => "",
                        "color" => ""
                    ], true)
                ]);
            }else{
                $this->load->view("home/home_view", [
                    "title" => "Tambah <small>Silahkan menambahkan file arsip kurs.</small>",
                    "navigasi" => $this->load->view("navigasi/navigasi_view", ["active_index" => -1], true),
                    "content" => $this->load->view("maintenance/maintenance_tambah_view", [
                        "message" => "Arsip kurs tidak ditemukan.",
                        "color" => "red"
                    ], true)
                ]);
            }
        }else
        if((isset($_POST["tambah_kurs"]) == true)){
            if(isset($_FILES["file_excel"]) == true){
                $filename = $_FILES['file_excel']['name'];
                if(pathinfo($filename, PATHINFO_EXTENSION) == "xlsx"){
                    $foldername = hash_str($filename.get_time().rand_string("RAND", 100));
                    $this->upload->initialize(config_upload_excel($filename, $foldername));
                    if($this->upload->do_upload("file_excel")){
                        $inputFileName = upload_excel_path($foldername).$filename;
                        $inputFileType = "Xlsx";
                        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
                        $reader->setReadDataOnly(true);

                        $worksheetData = $reader->listWorksheetInfo($inputFileName);

                        $sheetName = $worksheetData[0]['worksheetName'];
                        $reader->setLoadSheetsOnly($sheetName);
                        $spreadsheet = $reader->load($inputFileName);
                        $worksheet = $spreadsheet->getActiveSheet();
                        $data = $worksheet->toArray();
                        $mata_uang =  $data[0][0];
                        $nilai =  $data[0][1];
                        if($this->kurs_model->exists_mata_uang($mata_uang) == false){
                            $this->kurs_model->insert($filename, $foldername, $mata_uang, $nilai);
                            $this->load->view("home/home_view", [
                                "title" => "Maintenance Kurs <small>Silahkan melakukan pemeliharaan arsip kurs.</small>",
                                "navigasi" => $this->load->view("navigasi/navigasi_view", ["active_index" => 1], true),
                                "content" => $this->load->view("maintenance/maintenance_view", [
                                    "list" => $this->kurs_model->list_arsip_kurs(),
                                    "message" => "Arsip kurs berhasil ditambahkan.",
                                    "color" => "green"
                                ], true)
                            ]);
                        }else{
                            delete_file_excel($filename, $foldername);
                            $this->load->view("home/home_view", [
                                "title" => "Tambah <small>Silahkan menambahkan file arsip kurs.</small>",
                                "navigasi" => $this->load->view("navigasi/navigasi_view", ["active_index" => -1], true),
                                "content" => $this->load->view("maintenance/maintenance_tambah_view", [
                                    "message" => "Arsip kurs sudah terdaftar.",
                                    "color" => "red"
                                ], true)
                            ]);
                        }
                    }
                }else
                if(pathinfo($filename, PATHINFO_EXTENSION) == ""){
                    $this->load->view("home/home_view", [
                        "title" => "Tambah <small>Silahkan menambahkan file arsip kurs.</small>",
                        "navigasi" => $this->load->view("navigasi/navigasi_view", ["active_index" => -1], true),
                        "content" => $this->load->view("maintenance/maintenance_tambah_view", [
                            "message" => "Harap memilih file sebelum melakukan menyimpanan.",
                            "color" => "red"
                        ], true)
                    ]);
                }else{
                    $this->load->view("home/home_view", [
                        "title" => "Tambah <small>Silahkan menambahkan file arsip kurs.</small>",
                        "navigasi" => $this->load->view("navigasi/navigasi_view", ["active_index" => -1], true),
                        "content" => $this->load->view("maintenance/maintenance_tambah_view", [
                            "message" => "File yang terupload bukan file excel.",
                            "color" => "red"
                        ], true)
                    ]);
                }
            }
        }else
        if((isset($_POST["hapus_kurs"]) == true)){
            $id = decrypt_str($_POST["hapus_kurs"]);
            $out = $this->kurs_model->exists_id($id);
            if($out["exists"] == true){
                delete_file_excel($out["nama_file"], $out["direktori"]);
                $this->kurs_model->delete($id);
                $this->load->view("home/home_view", [
                    "title" => "Maintenance Kurs <small>Silahkan melakukan pemeliharaan arsip kurs.</small>",
                    "navigasi" => $this->load->view("navigasi/navigasi_view", ["active_index" => 1], true),
                    "content" => $this->load->view("maintenance/maintenance_view", [
                        "list" => $this->kurs_model->list_arsip_kurs(),
                        "message" => "Arsip kurs berhasil dihapus.",
                        "color" => "green"
                    ], true)
                ]);
            }else{
                $this->load->view("home/home_view", [
                    "title" => "Tambah <small>Silahkan menambahkan file arsip kurs.</small>",
                    "navigasi" => $this->load->view("navigasi/navigasi_view", ["active_index" => -1], true),
                    "content" => $this->load->view("maintenance/maintenance_tambah_view", [
                        "message" => "Arsip kurs tidak ditemukan.",
                        "color" => "red"
                    ], true)
                ]);
            }
        }else{
            $this->load->view("home/home_view", [
                "title" => "Maintenance Kurs <small>Silahkan melakukan pemeliharaan arsip kurs.</small>",
                "navigasi" => $this->load->view("navigasi/navigasi_view", ["active_index" => 1], true),
                "content" => $this->load->view("maintenance/maintenance_view", [
                    "list" => $this->kurs_model->list_arsip_kurs(),
                    "message" => "",
                    "color" => ""
                ], true)
            ]);
        }
	}
}