<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link rel="stylesheet" type="text/css" href="<?php echo get_assets()?>dropify/dist/css/dropify.min.css">
<script type="text/javascript" src="<?php echo get_assets()?>dropify/dist/js/dropify.min.js" class="script_loader"></script>
<form action="<?php echo get_controller("maintenance"); ?>" method="post" enctype="multipart/form-data">
<div class="box box-info">
    <div class="box-header">
        <h3 class="box-title">Tambah Kurs Mata Uang</h3>
    </div>
    <div class="box-body">
        <div class="form-group">
            <input type="file" name="file_excel" class="dropify" data-height="300">
        </div>
    </div>
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <div class="box-footer">
        <button type="submit" name="tambah_kurs" value="1" class="btn btn-success">Simpan</button>
        &nbsp;
        <button type="submit" name="kembali" value="1" class="btn btn-info">Kembali</button>
    </div>
</div>
</form>

<script type="text/javascript">
$(".dropify").dropify({
    messages: {
        default: "Drag & Drop atau Klik disini untuk memilih file excel.<br>Kemudian klik upload.",
        replace: "Drag & drop atau Klik disini untuk mengganti file excel",
        remove:  "Hapus file excel",
        error: "error"
    }
});
</script>

<?php
if(($message != "") && ($color != "")){
    echo "<link rel=\"stylesheet\" href=\"".base_url()."assets/iziToast/dist/css/iziToast.min.css\">".
    "<script src=\"".base_url()."assets/iziToast/dist/js/iziToast.min.js\"></script>".
    "<script type=\"text/javascript\">
    $(function(){
        iziToast.show({title: \"".$message."\", color: \"".$color."\", timeout: 2000});
    });
    </script>";
}
?>