<?php include 'backend/condb.php';

if (isset($_GET['status'])){
    $status = $_GET['status'];
    if ($status == "done") {
        // Truy vấn những dòng có `due_date` lớn hơn hoặc bằng ngày hiện tại (đang mượn)
        $sql = "SELECT * FROM book_rental WHERE `status`='done'";
    }
    elseif ($status == "overdue") {
        // Truy vấn những dòng có `due_date` nhỏ hơn ngày hiện tại (quá hạn)
        $sql = "SELECT * FROM book_rental WHERE STR_TO_DATE(due_date, '%d-%m-%Y') < CURDATE() AND `status`='borrow'";
    } elseif ($status == "borrowed") {
        // Truy vấn những dòng có `due_date` lớn hơn hoặc bằng ngày hiện tại (đang mượn)
        $sql = "SELECT * FROM book_rental WHERE STR_TO_DATE(due_date, '%d-%m-%Y') >= CURDATE() AND `status`='borrow'";
    }
} else
    $sql = "SELECT * FROM book_rental";
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
    <title>UTT - Quản lý đơn mượn sách</title>

    <link rel="shortcut icon" href="assets/img/favicon.png">

    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,500;0,700;0,900;1,400;1,500;1,700&amp;display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">

    <link rel="stylesheet" href="assets/plugins/feather/feather.css">

    <link rel="stylesheet" href="assets/plugins/icons/flags/flags.css">

    <link rel="stylesheet" href="assets/css/feather.css">

    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">

    <link rel="stylesheet" href="assets/plugins/select2/css/select2.min.css">

    <link rel="stylesheet" href="assets/plugins/datatables/datatables.min.css">

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

                <div class="page-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="page-title">Danh sách mượn 2</h3>
                        </div>
                    </div>
                </div>


                <div class="card invoices-tabs-card border-0">
                    <div class="card-body card-body pt-0 pb-0">
                        <div class="invoices-main-tabs">
                            <div class="row align-items-center">
                                <div class="col-lg-8 col-md-8">
                                    <div class="invoices-tabs">
                                        <ul>
                                        <li><a href="index.php" class="<?php if (!isset($_GET['status'])) echo "active" ?>">Tất cả</a></li>
                                        <li><a href="index.php?status=borrowed" class="<?php if (isset($_GET['status']) && $_GET['status'] == "borrowed") echo "active" ?>">Đang mượn</a></li>
                                        <li><a href="index.php?status=overdue" class="<?php if (isset($_GET['status']) && $_GET['status'] == "overdue") echo "active" ?>">Quá hạn mượn</a></li>
                                        <li><a href="index.php?status=done" class="<?php if (isset($_GET['status']) && $_GET['status'] == "done") echo "active" ?>">Đã trả sách</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4">
                                    <div class="invoices-settings-btn">
                                        <a href="add-invoice.php" class="btn">
                                            <i class="feather feather-plus-circle"></i> Đơn mượn mới
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                date_default_timezone_set('Asia/Ho_Chi_Minh');
                $currentDate = date('d-m-Y');
                
                // Truy vấn dữ liệu từ cơ sở dữ liệu
                $sqldate = "SELECT due_date, `status`, rental_id FROM book_rental"; 
                $resultdate = $conn->query($sqldate);

                // Khởi tạo biến đếm cho ngày nhỏ hơn và lớn hơn ngày hiện tại
                $countPastDue = 0;
                $countFutureDue = 0;
                $countDone = 0;
                $number = 1;
                $stopnumber=False;

                if ($resultdate->num_rows > 0) {
                    while ($row = $resultdate->fetch_assoc()) {
                        $dueDate = $row['due_date'];
                        if ($row['status'] == 'done') {
                            $countDone++;
                        }
                        elseif (strtotime($dueDate) < strtotime($currentDate)) {
                            $countPastDue++;
                        } elseif (strtotime($dueDate) >= strtotime($currentDate)) {
                            $countFutureDue++;
                        }

                        if ($stopnumber=False && $row['rental_id']==$number){
                            $number++;
                        } else {
                            $stopnumber=True;
                        }
                            
                    }

                ?>

                <div class="row">
                    <div class="col-xl-3 col-sm-6 col-12">
                        <div class="card inovices-card">
                            <div class="card-body">
                                <div class="inovices-widget-header">
                                    <span class="inovices-widget-icon">
                                        <img src="assets/img/icons/invoices-icon1.svg" alt>
                                    </span>
                                    <div class="inovices-dash-count">
                                        <div class="inovices-amount"><?php echo $countPastDue+$countFutureDue+$countDone?></div>
                                    </div>
                                </div>
                                <p class="inovices-all">Tất cả</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 col-12">
                        <div class="card inovices-card">
                            <div class="card-body">
                                <div class="inovices-widget-header">
                                    <span class="inovices-widget-icon">
                                        <img src="assets/img/icons/invoices-icon2.svg" alt>
                                    </span>
                                    <div class="inovices-dash-count">
                                        <div class="inovices-amount"><?php echo $countFutureDue?></div>
                                    </div>
                                </div>
                                <p class="inovices-all">Đang mượn sách</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 col-12">
                        <div class="card inovices-card">
                            <div class="card-body">
                                <div class="inovices-widget-header">
                                    <span class="inovices-widget-icon">
                                        <img src="assets/img/icons/invoices-icon3.svg" alt>
                                    </span>
                                    <div class="inovices-dash-count">
                                        <div class="inovices-amount"><?php echo $countPastDue?></div>
                                    </div>
                                </div>
                                <p class="inovices-all">Quá hạn mượn sách</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 col-12">
                        <div class="card inovices-card">
                            <div class="card-body">
                                <div class="inovices-widget-header">
                                    <span class="inovices-widget-icon">
                                        <img src="assets/img/icons/invoices-icon4.svg" alt>
                                    </span>
                                    <div class="inovices-dash-count">
                                        <div class="inovices-amount"><?php echo $countDone?></div>
                                    </div>
                                </div>
                                <p class="inovices-all">Đã trả sách</p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    } else {
                        echo "Không có dữ liệu phù hợp.";
                    }
                ?>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card card-table">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-stripped table-hover datatable">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Số phiếu</th>
                                                <th>Mã độc giả</th>
                                                <th>Mã mượn</th>
                                                <th>Số lượng</th>
                                                <th>Ngày mượn</th>
                                                <th>Ngày trả dự kiến</th>
                                                <th>Ngày trả</th>
                                                <th>Thủ thư</th>
                                                <th>Trạng thái</th>
                                                <th class="text-end">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                        ?>
                                            <tr>
                                                <td class="text-primary"><?php echo $row["call_card"]?></td>
                                                <td><?php echo $row["student_id"]?></td>
                                                <td><?php echo $row["book_id"]?></td>
                                                <td><?php echo $row["quantity"]?></td>
                                                <td><?php echo $row["borrow_date"]?></td>
                                                <td><?php echo $row["due_date"]?></td>
                                                <td><?php echo $row["actual_date"]?></td>
                                                <td><?php echo $row["librarian"]?></td>
                                                <td>
                                                    <?php
                                                        if ($row["status"] != "borrow"){
                                                            $r1 = "badge bg-info";
                                                            $r2 = "Đã trả sách";
                                                        }
                                                        elseif(strtotime($row["due_date"])>=strtotime($currentDate)){
                                                            $r1 = "badge bg-success";
                                                            $r2 = "Đang mượn sách";
                                                        } else {
                                                            $r1 = "badge bg-danger";
                                                            $r2 = "Quá hạn mượn sách";       
                                                        }
                                                    ?>
                                                    <span class="<?php echo $r1?>"><?php echo $r2?></span>
                                                </td>
                                                <td class="text-end">
                                                    <div class="dropdown dropdown-action">
                                                        <a href="#" class="action-icon dropdown-toggle"
                                                            data-bs-toggle="dropdown" aria-expanded="false"><i
                                                                class="fas fa-ellipsis-v"></i></a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item" href="edit-invoice.php?rental_id=<?php echo $row["rental_id"]?>"><i
                                                                    class="far fa-edit me-2"></i>Gia hạn</a>
                                                            <a class="dropdown-item" href="backend/edit.php?edit=book_rental&status=done&rental_id=<?php echo $row["rental_id"]?>"><i
                                                                    class="far fa-check-circle me-2"></i>Xác nhận trả sách</a>
                                                            <a class="dropdown-item" href="backend/delete.php?delete=book_rental&rental_id=<?php echo $row["rental_id"]?>"><i
                                                                    class="far fa-trash-alt me-2"></i>Xóa</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
        
                                        <?php
                                                }
                                            } else {
                                                echo "Không có dữ liệu";
                                            }
                                        ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
        <!-- Nội dung footer -->   
        <div id="footer"></div>
        <script>
            // Fetch nội dung footer
            fetch('footer.html')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('footer').innerHTML = data;
                });
        </script>
            
            </div>
        </div>
    </div>

    <script src="assets/js/jquery-3.6.0.min.js"></script>

    <script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="assets/js/feather.min.js"></script>

    <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <script src="assets/plugins/select2/js/select2.min.js"></script>

    <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatables/datatables.min.js"></script>

    <script src="assets/plugins/moment/moment.min.js"></script>
    <script src="assets/js/bootstrap-datetimepicker.min.js"></script>

    <script src="assets/js/script.js"></script>
</body>


</html>