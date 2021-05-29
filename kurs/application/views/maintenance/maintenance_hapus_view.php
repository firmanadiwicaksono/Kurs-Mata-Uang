<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<form action="<?php echo get_controller("maintenance"); ?>" method="post">
<div class="box box-info">
    <div class="box-header">
        <h3 class="box-title">Hapus Data ?</h3>
    </div>
    <div class="box-body">
        <div class="form-group">
            <label>
                Mata Uang : <?php cetak($mata_uang); ?><br>
                Nilai : <?php cetak($nilai); ?><br>
                Nama File : <?php cetak($nama_file); ?><br>
            </label>
        </div>
    </div>
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <div class="box-footer">
        <button type="submit" name="hapus_kurs" value="<?php echo encrypt_str($id); ?>" class="btn btn-primary">Ya</button>
        &nbsp;
        <button type="submit" name="tidak" value="1" class="btn btn-danger">Tidak</button>
    </div>
</div>
</form>
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