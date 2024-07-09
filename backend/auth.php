<?php
// Kết nối tới cơ sở dữ liệu
include 'condb.php';

// Lấy thông tin từ biểu mẫu đăng nhập
$username = $_POST['username'];
$password = $_POST['password'];

if ($username == "admin" and $password == "admin") {
    setcookie("admin", "true", time() + 3600, "/");
    setcookie("basic", "true", time() + 3600, "/");
    setcookie("user_name", "Quản trị viên", time() + 3600, "/"); // Cookie tồn tại trong 1 giờ
    setcookie("role", "Quản trị viên", time() + 3600, "/"); // Cookie tồn tại trong 1 giờ
    header("Location: ../index.php"); 
    exit();
} else {
    // Kiểm tra thông tin đăng nhập trong cơ sở dữ liệu
    $sql = "SELECT * FROM librarian WHERE `username` = '$username' AND `password` = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Lấy thông tin hàng đầu tiên từ kết quả truy vấn
        $row = $result->fetch_assoc();
        $name = $row["name"]; // Lấy giá trị cột "name" từ hàng dữ liệu

        // Đăng nhập thành công, thiết lập cookie với tên dựa trên "name"
        setcookie("basic", "true", time() + 3600, "/");
        setcookie("user_name", $name, time() + 3600, "/"); // Cookie tồn tại trong 1 giờ
        setcookie("role", "Thủ thư", time() + 3600, "/"); // Cookie tồn tại trong 1 giờ
        header("Location: ../index.php"); // Chuyển hướng đến trang index.php
    } else {
        header("Location: ../login.html?error=true");
    }

    $conn->close();

}
?>
