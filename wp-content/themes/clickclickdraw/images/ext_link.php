<?php
header('Content-Type: image/svg+xml');
if(isset($_GET['color'])) $color = $_GET['color'];
else $color = '#FFF';
?>
<svg height="1024" width="768" xmlns="http://www.w3.org/2000/svg" fill="<?php echo $color; ?>">
  <path d="M640 768H128V257.90599999999995L256 256V128H0v768h768V576H640V768zM384 128l128 128L320 448l128 128 192-192 128 128V128H384z" />
</svg>
