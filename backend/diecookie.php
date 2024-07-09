<?php
// Đặt thời gian hết hạn của cookie là thời điểm trong quá khứ để xóa nó
setcookie("basic", "", time() - 3600, "/");
setcookie("admin", "", time() - 3600, "/");
header("Location: ../index.html");

?>