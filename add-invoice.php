<?php 
include 'backend/condb.php';
$sql = "SELECT call_card FROM book_rental ORDER BY call_card ASC ";
$result = $conn->query($sql);
$number = 1;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($row['call_card']==$number){
            $number++;
        } else {
                break;
            }
        }
    } else {
        echo "Không có dữ liệu";
    }

$sqlstu = "SELECT * FROM students";
$resultstu = $conn->query($sqlstu);
?>

<script>
// Kiểm tra giá trị GET có tồn tại và không rỗng
if (<?php echo isset($_GET['error'])?>) {
    alert("Không đủ sách");
}

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
<html >


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>UTT - Thêm đơn mượn</title>

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
                                        <i class="fe fe-chevron-left"></i> Quay lại
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
                                <form action="backend/add.php?add=book_rental" class="invoices-form" method="POST">
                                    <div class="invoices-main-form">
                                        <div class="row">
                                            <div class="col-xl-4 col-md-6 col-sm-12 col-12">
                                                <div class="form-group">
                                                    <label>Mã độc giả</label>
                                                    <select class="form-control select" name="student_id">
                                                        <?php
                                                        if ($resultstu->num_rows > 0) {
                                                            while ($row = $resultstu->fetch_assoc()) {
                                                        ?>
                                                            <option><?php echo $row["student_id"]?></option>
                                                        <?php
                                                                }
                                                            } else {
                                                                echo "Không có dữ liệu";
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Phiếu mượn sách</label>
                                                    <input class="form-control" type="text"
                                                        placeholder="Nhập mã mượn sách" name="call_card" value="<?php echo $number?>" readonly>
                                                </div>
                                            </div>
                                            <div class="col-xl-5 col-md-6 col-sm-12 col-12">
                                                <h4 class="invoice-details-title">Ngày mượn/trả sách</h4>
                                                <div class="invoice-details-box">
                                                    <div class="invoice-inner-footer">
                                                        <div class="row align-items-center">
                                                            <div class="col-lg-6 col-md-6">
                                                                <div class="invoice-inner-date">
                                                                    <span>
                                                                        Ngày mượn <input class="form-control datetimepicker"
                                                                            type="text" placeholder="Chọn ngày" name="borrow_date">
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6 col-md-6">
                                                                <div class="invoice-inner-date invoice-inner-datepic">
                                                                    <span>
                                                                        Ngày trả sách dự kiến <input
                                                                            class="form-control datetimepicker"
                                                                            type="text" placeholder="Chọn ngày" name="due_date">
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="invoice-add-table">
                                        <h4>Chi tiết mượn sách</h4>
                                        <div class="table-responsive">
                                            <table class="table table-center add-table-items">
                                                <thead>
                                                    <tr>
                                                        <th>Mã sách mượn</th>
                                                        <th>Số lượng</th>
                                                        <th style="text-align: right;">Thêm/xóa</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="books_row">
                                                    <?php
                                                    if(isset($_GET['add'])){
                                                        $add=$_GET['add'];
                                                        for ($i = 1; $i <= $add; $i++) {
                                                    ?>
                                                    <tr class="add-row" id="bookInputs">
                                                        <td>
                                                            <select class="form-control select book-select" name="book_id[]">
                                                                <!-- Các option sẽ được thêm bằng JavaScript -->
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="number" class="form-control" name="quantity[]" min="1">
                                                        </td>
                                                        <td class="add-remove text-end"> 
                                                            <a href="javascript:void(0);" onclick="removeBookInput(this)"><i class="fas fa-minus-circle"></i></a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                        }  
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="upload-sign">
                                        <div class="form-group float-end mb-0" style="<?php if(!isset($_GET['add'])) echo 'display:none'?>">
                                            <button class="btn btn-primary" type="submit">Thêm đơn mượn</button>
                                        </div>
                                    </div>
                                </form>
                                <form action="add-invoice.php">
                                        <div class="invoice-item">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="quantity_books">Số lượng mã sách muốn thuê:</label>
                                                        <input type="number" class="form-control" name="add">
                                                    </div>
                                                </div>
                                                <div>
                                                    <button type="submit" class="btn btn-primary">Thuê Sách</button>
                                                </div>
                                            </div> 
                                        </div>
                                </form>
<script>
    const bookSelects = document.querySelectorAll('.book-select');
    // Sử dụng fetch để gửi yêu cầu đến backend/get_invoice.php
    fetch('backend/get_data.php?get_data=tables&table=library')
        .then(response => response.json())
        .then(data => {
            // Lặp qua từng select element và thêm các option từ dữ liệu JSON
            bookSelects.forEach(bookSelect => {
                data.forEach(book => {
                    const option = document.createElement('option');
                    option.value = book.book_id;
                    option.textContent = book.book_id;
                    bookSelect.appendChild(option);
                });
            });
        })
        .catch(error => {
            console.error('Lỗi khi truy xuất dữ liệu:', error);
        });

    function removeBookInput(element) {
        const row = element.parentNode.parentNode;
        row.parentNode.removeChild(row);
    }
    
</script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- <div class="modal custom-modal fade invoices-preview" id="invoices_preview" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="row justify-content-center">
                            <div class="col-lg-12">
                                <div class="card invoice-info-card">
                                    <div class="card-body pb-0">
                                        <div class="invoice-item invoice-item-one">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="invoice-logo">
                                                        <img src="assets/img/logo.png" alt="logo">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="invoice-info">
                                                        <div class="invoice-head">
                                                            <h2 class="text-primary">Invoice</h2>
                                                            <p>Invoice Number : In983248782</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="invoice-item invoice-item-bg">
                                            <div class="invoice-circle-img">
                                                <img src="assets/img/invoice-circle1.png" alt class="invoice-circle1">
                                                <img src="assets/img/invoice-circle2.png" alt class="invoice-circle2">
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-4 col-md-12">
                                                    <div class="invoice-info">
                                                        <strong class="customer-text-one">Billed to</strong>
                                                        <h6 class="invoice-name">Customer Name</h6>
                                                        <p class="invoice-details invoice-details-two">
                                                            9087484288 <br>
                                                            Address line 1, <br>
                                                            Address line 2 <br>
                                                            Zip code ,City - Country
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-12">
                                                    <div class="invoice-info">
                                                        <strong class="customer-text-one">Invoice From</strong>
                                                        <h6 class="invoice-name">Company Name</h6>
                                                        <p class="invoice-details invoice-details-two">
                                                            9087484288 <br>
                                                            Address line 1, <br>
                                                            Address line 2 <br>
                                                            Zip code ,City - Country
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-12">
                                                    <div class="invoice-info invoice-info-one border-0">
                                                        <p>Issue Date : 27 Jul 2022</p>
                                                        <p>Due Date : 27 Aug 2022</p>
                                                        <p>Due Amount : $ 1,54,22 </p>
                                                        <p>Recurring Invoice : 15 Months</p>
                                                        <p class="mb-0">PO Number : 54515454</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="invoice-item invoice-table-wrap">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table class="invoice-table table table-center mb-0">
                                                            <thead>
                                                                <tr>
                                                                    <th>Description</th>
                                                                    <th>Category</th>
                                                                    <th>Rate/Item</th>
                                                                    <th>Quantity</th>
                                                                    <th>Discount (%)</th>
                                                                    <th class="text-end">Amount</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>Dell Laptop</td>
                                                                    <td>Laptop</td>
                                                                    <td>$1,110</td>
                                                                    <th>2</th>
                                                                    <th>2%</th>
                                                                    <td class="text-end">$400</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>HP Laptop</td>
                                                                    <td>Laptop</td>
                                                                    <td>$1,500</td>
                                                                    <th>3</th>
                                                                    <th>6%</th>
                                                                    <td class="text-end">$3,000</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Apple Ipad</td>
                                                                    <td>Ipad</td>
                                                                    <td>$11,500</td>
                                                                    <th>1</th>
                                                                    <th>10%</th>
                                                                    <td class="text-end">$11,000</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row align-items-center justify-content-center">
                                            <div class="col-lg-6 col-md-6">
                                                <div class="invoice-payment-box">
                                                    <h4>Payment Details</h4>
                                                    <div class="payment-details">
                                                        <p>Debit Card XXXXXXXXXXXX-2541 HDFC Bank</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="invoice-total-card">
                                                    <div class="invoice-total-box">
                                                        <div class="invoice-total-inner">
                                                            <p>Taxable <span>$6,660.00</span></p>
                                                            <p>Additional Charges <span>$6,660.00</span></p>
                                                            <p>Discount <span>$3,300.00</span></p>
                                                            <p class="mb-0">Sub total <span>$3,300.00</span></p>
                                                        </div>
                                                        <div class="invoice-total-footer">
                                                            <h4>Total Amount <span>$143,300.00</span></h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="invoice-sign-box">
                                            <div class="row">
                                                <div class="col-lg-8 col-md-8">
                                                    <div class="invoice-terms">
                                                        <h6>Notes:</h6>
                                                        <p class="mb-0">Enter customer notes or any other details</p>
                                                    </div>
                                                    <div class="invoice-terms mb-0">
                                                        <h6>Terms and Conditions:</h6>
                                                        <p class="mb-0">Enter customer notes or any other details</p>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-4">
                                                    <div class="invoice-sign text-end">
                                                        <img class="img-fluid d-inline-block"
                                                            src="assets/img/signature.png" alt="sign">
                                                        <span class="d-block">Harristemp</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->

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