<?php include 'backend/condb.php';
$sql = "SELECT * FROM library";

if (isset($_GET['status']) && $_GET['status'] == "publisher") {
    $sql = "SELECT * FROM publisher_mgr";
} elseif (isset($_GET['status']) && $_GET['status'] == "author") {
    $sql = "SELECT * FROM author_mgr";
}

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
    <title>UTT - Thư viện</title>

    <link rel="shortcut icon" href="assets/img/favicon.png">

    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,500;0,700;0,900;1,400;1,500;1,700&amp;display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css">

    <link rel="stylesheet" href="assets/plugins/feather/feather.css">

    <link rel="stylesheet" href="assets/plugins/icons/flags/flags.css">

    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">

    <link rel="stylesheet" href="assets/plugins/datatables/datatables.min.css">

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
                    const svElement = document.querySelector('.tv');
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
                            <h3 class="page-title">Thư viện</h3>
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
                                        <li><a href="library.php" class="<?php if (!isset($_GET['status'])) echo "active" ?>">Quản lý sách</a></li>
                                        <li><a href="library.php?status=author" class="<?php if (isset($_GET['status']) && $_GET['status'] == "author") echo "active" ?>">Quản lý tác giả</a></li>
                                        <li><a href="library.php?status=publisher" class="<?php if (isset($_GET['status']) && $_GET['status'] == "publisher") echo "active" ?>">Quản lý nhà xuất bản</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="card card-table">
                            <div class="card-body">

                                <div class="page-header">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h3 class="page-title">Sách hiện có</h3>
                                        </div>
                                        <div class="col-auto text-end float-end ms-auto download-grp">
                                            <a href="<?php
                                                if (!isset($_GET['status']))
                                                    echo "add-books.html";
                                                elseif (isset($_GET['status']) && $_GET['status'] == "author")
                                                    echo "add-books.html?status=author";
                                                elseif (isset($_GET['status']) && $_GET['status'] == "publisher")
                                                    echo "add-books.html?status=publisher";                                                
                                            ?>" class="btn btn-primary"><i
                                                    class="fas fa-plus"></i></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table border-0 star-student table-hover table-center mb-0 datatable table-striped">
                                        <?php if (isset($_GET['status']) && $_GET['status'] == "publisher") { ?>
                                            <thead class="student-thread">
                                                <tr>
                                                    <th>Tên nhà xuất bản</th>
                                                    <th>Số điện thoại</th>
                                                    <th>Địa chỉ</th>
                                                    <th class="text-end">Thao tác</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    if ($result->num_rows > 0) {
                                                        while ($row = $result->fetch_assoc()) {
                                                ?>
                                                    <tr>
                                                        <td><?php echo $row["publisher_name"]?></td>
                                                        <td><?php echo $row["phone"]?></td>
                                                        <td><?php echo $row["ad"]?></td>
                                                        <td class="text-end">
                                                            <div class="actions">
                                                                <a href="backend/delete.php?delete=publisher_mgr&publisher_name=<?php echo $row["publisher_name"]?>" class="btn btn-sm bg-danger-light">
                                                                    <i class="far fa-trash-alt me-2"></i>
                                                                </a>
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
                                        <?php }
                                            elseif (isset($_GET['status']) && $_GET['status'] == "author") {
                                        ?>
                                            <thead class="student-thread">
                                                <tr>
                                                    <th>Tên tác giả</th>
                                                    <th>Phong cách viết</th>
                                                    <th>Tóm tắt</th>
                                                    <th class="text-end">Thao tác</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    if ($result->num_rows > 0) {
                                                        while ($row = $result->fetch_assoc()) {
                                                ?>
                                                    <tr>
                                                        <td><?php echo $row["author_name"]?></td>
                                                        <td style="white-space: pre-wrap;"><?php echo $row["style"]?></td>
                                                        <td style="white-space: pre-wrap;"><?php echo $row["des"]?></td>
                                                        <td class="text-end">
                                                            <div class="actions">
                                                                <a href="backend/delete.php?delete=author_mgr&author_name=<?php echo $row["author_name"]?>" class="btn btn-sm bg-danger-light">
                                                                    <i class="far fa-trash-alt me-2"></i>
                                                                </a>
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
                                        <?php }
                                            else {
                                        ?>
                                            <thead class="student-thread">
                                                <tr>
                                                    <th>Mã sách</th>
                                                    <th>Tên sách</th>
                                                    <th>Tác giả</th>
                                                    <th>Nhà xuất bản</th>
                                                    <th>Danh mục</th>
                                                    <th>Loại sách</th>
                                                    <th>Tồn kho</th>
                                                    <th>Trạng thái</th>
                                                    <th class="text-end">Thao tác</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    if ($result->num_rows > 0) {
                                                        while ($row = $result->fetch_assoc()) {
                                                            $book_id = $row["book_id"];

                                                            $sql1 = "SELECT library.quantity AS library_quantity, 
                                                                        SUM(book_rental.quantity) AS book_rental_quantity 
                                                                        FROM library INNER JOIN book_rental ON library.book_id = book_rental.book_id 
                                                                        WHERE library.book_id = '$book_id '";
                                                            $result1 = $conn->query($sql1);
                                                            $countData = $result1->fetch_assoc();
                                                            if ($countData['book_rental_quantity'] == null)
                                                                $count = $row["quantity"];
                                                            else
                                                                $count = $countData['library_quantity']-$countData['book_rental_quantity'];

                                                ?>
                                                    <tr>
                                                        <td><?php echo $row["book_id"]?></td>
                                                        <td><?php echo $row["book_name"]?></td>
                                                        <td><?php echo $row["author"]?></td>
                                                        <td style="white-space: pre-wrap;"><?php echo $row["publisher"]?></td>
                                                        <td style="white-space: pre-wrap;"><?php echo $row["category"]?></td>
                                                        <td><?php echo $row["book_type"]?></td>
                                                        <td><?php echo $count."/".$row["quantity"]?></td>
                                                        <td>
                                                        <?php 
                                                            if ($count<=0) {
                                                
                                                        ?>
                                                            <span class="badge badge-danger">Hết sách</span>
                                                        <?php
                                                            }else {
                                                        ?>
                                                            <span class="badge badge-success">Còn sách</span>
                                                        </td>
                                                        <?php
                                                            }
                                                        ?>
                                                        <td class="text-end">
                                                            <div class="actions">
                                                                <a href="edit-books.html?book_id=<?php echo $row['book_id']?>" class="btn btn-sm bg-danger-light">
                                                                    <i class="feather-edit"></i>
                                                                </a>
                                                                <a href="backend/delete.php?delete=library&book_id=<?php echo $row["book_id"]?>" class="btn btn-sm bg-danger-light">
                                                                    <i class="far fa-trash-alt me-2"></i>
                                                                </a>
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
                                        <?php } ?>
                                    </table>
                                </div>
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


    <script src="assets/js/jquery-3.6.0.min.js"></script>

    <script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="assets/js/feather.min.js"></script>

    <script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <script src="assets/plugins/datatables/datatables.min.js"></script>

    <script src="assets/js/script.js"></script>
</body>

</html>