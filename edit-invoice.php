<?php include 'backend/condb.php';
$rental_id_old = $_GET['rental_id'];
$sql = "SELECT * FROM book_rental WHERE rental_id = '$rental_id_old'";
$result = $conn->query($sql);
?>

<script>

if (getCookie("basic") === "true") {
    // alert("Đăng nhập với vai trò nhân viên")
} else {
    window.location.href = "login.html";
}

// Hàm lấy giá trị của cookie
function getCookie(name) {
    var value = "; " + document.cookie;
    var parts = value.split("; " + name + "=");
    if (parts.length === 2) return parts.pop().split(";").shift();
}
</script>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>UTT - Sửa đơn mượn</title>

    <link rel="shortcut icon" href="assets/img/favicon.png">

    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,500;0,700;0,900;1,400;1,500;1,700&amp;display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">

    <link rel="stylesheet" href="assets/plugins/feather/feather.css">

    <link rel="stylesheet" href="assets/plugins/icons/flags/flags.css">

    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">

    <link rel="stylesheet" href="assets/plugins/icons/feather/feather.css">

    <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">

    <link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css">

    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <div class="main-wrapper">

        <!-- Nội dung Header -->   
        <div id="header"></div>
        <script>
            // Fetch nội dung header
            fetch('header.html')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('header').innerHTML = data;

                    document.getElementById("name").textContent = decodeURIComponent(getCookie("user_name"));
                    document.getElementById("role").textContent = decodeURIComponent(getCookie("role"));   
                });
        </script>

        <!-- Nội dung Sidebar -->   
        <div id="sidebar"></div>
        <script>
            // Fetch nội dung Sidebar
            fetch('sidebar.html')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('sidebar').innerHTML = data;
                    
                    // Thêm lớp 'active' cho phần tử có class 'sv'
                    const svElement = document.querySelector('.db');
                    if (svElement) {
                        svElement.classList.add('active');
                    }
                });
        </script>


        <div class="page-wrapper">
            <div class="content container-fluid">

                <div class="page-header invoices-page-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <ul class="breadcrumb invoices-breadcrumb">
                                <li class="breadcrumb-item invoices-breadcrumb-item">
                                    <a href="index.php">
                                        <i class="fe fe-chevron-left"></i> Quay lại danh sách
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card invoices-add-card">
                            <div class="card-body">
                                <?php
                                    if ($result->num_rows == 1) {
                                        // Truy vấn chỉ trả về một hàng duy nhất
                                        $row = $result->fetch_assoc();
                                ?>
                                <form action="backend/edit.php?edit=book_rental&rental_id_old=<?php echo $rental_id_old?>" class="invoices-form" method="POST">
                                    <div class="invoices-main-form">
                                        <div class="row">
                                            <div class="col-xl-4 col-md-6 col-sm-12 col-12">
                                                <div class="form-group">
                                                    <label>Mã sách thuê</label>
                                                    <input class="form-control" type="text" value="<?php echo $row['book_id']?>" name="book_id" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label>Mã sinh viên</label>
                                                    <input class="form-control" type="text" value="<?php echo $row['student_id']?>" name="student_id" readonly>
                                                </div>
                                            </div>
                                            <div class="col-xl-5 col-md-6 col-sm-12 col-12">
                                                <h4 class="invoice-details-title">Chi tiết đơn mượn sách</h4>
                                                <div class="invoice-details-box">
                                                    <div class="invoice-inner-head">
                                                        <span>Mã mượn sách: <?php echo $row['rental_id']?></span>
                                                    </div>
                                                    <div class="invoice-inner-footer">
                                                        <div class="row align-items-center">
                                                            <div class="col-lg-6 col-md-6">
                                                                <div class="invoice-inner-date">
                                                                    <span>
                                                                        Date <input class="form-control datetimepicker"
                                                                            type="text" value="<?php echo $row['borrow_date']?>" name="borrow_date" readonly>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 col-md-6">
                                                                <div class="invoice-inner-date invoice-inner-datepic">
                                                                    <span>
                                                                        Due Date <input
                                                                            class="form-control datetimepicker"
                                                                            type="text" value="<?php echo $row['due_date']?>" name="due_date">
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="upload-sign">
                                        <div class="form-group float-end mb-0">
                                            <button class="btn btn-primary" type="submit">Cập nhật</button>
                                        </div>
                                    </div>
                                </form>
                                <?php
                                    } else {
                                        echo "Không có dữ liệu trả về.";
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <script src="assets/js/jquery-3.6.0.min.js"></script>

    <script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="assets/js/feather.min.js"></script>

    <script src="assets/js/feather.min.js"></script>

    <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <script src="assets/plugins/select2/js/select2.min.js"></script>

    <script src="assets/plugins/moment/moment.min.js"></script>
    <script src="assets/js/bootstrap-datetimepicker.min.js"></script>

    <script src="assets/js/script.js"></script>
</body>


</html>