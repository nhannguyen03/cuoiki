<?php
include 'condb.php';

// Lấy dữ liệu cho trang dashboard
    if (isset($_GET['get_data']) && $_GET['get_data'] == "dashboard"){
        // Dữ liệu tổng sô lượng cuốn sách đã từng cho thuê
        if (isset($_GET['get']) && $_GET['get'] == "books") {
            $sql = "SELECT SUM(quantity) as quantity
            FROM book_rental
            WHERE actual_date is NULL";
        
        // Dữ liệu tổng số lượng sinh viên đã từng thuê sách
        } elseif (isset($_GET['get']) && $_GET['get'] == "students") {
            $sql = "SELECT COUNT(DISTINCT student_id) as students
            FROM book_rental";
        
        // Dữ liệu những sinh viên đang mượn sách chưa đến hạn trả
        } elseif (isset($_GET['get']) && $_GET['get'] == "borrow") {
            $sql = "SELECT *
            FROM (
                SELECT book_rental.student_id, `student_name`, `book_name`, book_rental.quantity, `borrow_date`, `due_date`, actual_date
                FROM book_rental
                INNER JOIN students ON book_rental.student_id = students.student_id
                INNER JOIN library ON book_rental.book_id = library.book_id
            ) AS joined_data
            WHERE actual_date IS NULL AND STR_TO_DATE(due_date, '%d-%m-%Y') >= CURDATE()";

        // Dữ liệu những sinh viên quá hạn trả sách
        } elseif (isset($_GET['get']) && $_GET['get'] == "due") {
            $sql = "SELECT *
            FROM (
                SELECT book_rental.student_id, `student_name`, `book_name`, book_rental.quantity, `borrow_date`, `due_date`, actual_date
                FROM book_rental
                INNER JOIN students ON book_rental.student_id = students.student_id
                INNER JOIN library ON book_rental.book_id = library.book_id
            ) AS joined_data
            WHERE actual_date IS NULL AND STR_TO_DATE(due_date, '%d-%m-%Y') < CURDATE()";
        }
    }

// Lấy dữ liệu các bảng đơn
    if (isset($_GET['get_data']) && $_GET['get_data'] == "tables"){
        if (isset($_GET['table'])){
            $table = $_GET['table'];
            $sql = "SELECT * FROM $table";
        } else {
            echo "Vui lòng chọn bảng muốn lấy thông tin:";
            echo '<br> 1. <a href="?get_data=tables&table=book_rental">Bảng quản lý sách mượn</a>';
            echo '<br> 2. <a href="?get_data=tables&table=library">Bảng quản lý sách trong kho</a>';
            echo '<br> 3. <a href="?get_data=tables&table=author_mgr">Bảng quản lý tác giả</a>';
            echo '<br> 4. <a href="?get_data=tables&table=publisher_mgr">Bảng quản lý nhà xuất bản</a>';
            echo '<br> 5. <a href="?get_data=tables&table=librarian">Bảng quản lý thủ thư</a>';
            echo '<br> 6. <a href="?get_data=tables&table=students">Bảng quản lý sinh viên</a>';
        }

    }

// Lấy dữ liệu bảng cho trang danh sách sinh viên
if (isset($_GET['get_data']) && $_GET['get_data'] == "table_students"){
    $sql = "SELECT * FROM students WHERE 1";

    if (isset($_GET['student_id'])) {

        if ($_GET['student_id'] != "") {
            $data = $_GET['student_id'];
            $sql.= " AND student_id LIKE '%$data%'";
        } 
        
        if ($_GET['student_name'] != "") {
            $data = $_GET['student_name'];
            $sql.= " AND student_name LIKE '%$data%'";
        } 
        
        if ($_GET['phone'] != "" ) {
            $data = $_GET['phone'];
            $sql.= " AND phone LIKE '%$data%'";
        }
    }
}

// Echo dữ liệu ra dạng json 
    $result = $conn->query($sql);
    $data = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        } else {
            echo "Không có dữ liệu";
        }
    // Trả về dữ liệu dưới dạng JSON
    header('Content-Type: application/json');
    echo json_encode($data);

    // Đóng kết nối đến cơ sở dữ liệu
    $result->close();
    ?>