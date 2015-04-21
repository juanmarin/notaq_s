<?php session_start(); ?>
<style>
.thisfoto{border:1px solid #666;background:#999;padding:3px;margin:10px;}
</style>
<img src="include/jpegcam/htdocs/<?php echo $_SESSION["ifecliente"];?>.jpg" class="thisfoto" />
<img src="include/jpegcam/htdocs/<?php echo $_SESSION["ifecliente"];?>2.jpg" class="thisfoto" />
