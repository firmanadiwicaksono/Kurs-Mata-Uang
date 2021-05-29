<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$active_0 = '';
$active_1 = '';
if($active_index == 0){
    $active_0 = 'class="active"';
}else
if($active_index == 1){
    $active_1 = 'class="active"';
}
?>
<li <?php echo $active_0;?>><a href="<?php echo get_controller("home"); ?>"><i class="fa fa-desktop"></i> <span>Home</span></a></li>
<li <?php echo $active_1;?>><a href="<?php echo get_controller("maintenance"); ?>"><i class="fa fa-file-text"></i> <span>Maintenance Kurs</span></a></li>