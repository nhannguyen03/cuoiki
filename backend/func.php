<?php
include 'condb.php';
if (isset($_GET['func']) && $_GET['func'] == "auto_id"){
    // Tính mã sách tự động tăng dần
    if ($_GET['auto_id'] == 'book_id'){
        $sql = "SELECT book_id FROM library ORDER BY book_id ASC";
        $result = $conn->query($sql);
        $number = 1;
        $id = "utt_lib_".strval($number);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if ($row['book_id']==$id){
                    $number++;
                    $id = "utt_lib_".strval($number);
                } else {
                        break;
                    }
                }
            echo $id;
            } else {
                echo "Null";
            }

    // Tính mã phiếu mượn tự động tăng dần 
    } elseif ($_GET['auto_id'] == 'call_card') {
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
            echo $number;
            } else {
                echo "Null";
            }
    }
} 

// Xử lý đưa ra số lượng sách tồn kho sau khi cho thuê
if (isset($_GET['func']) && $_GET['func'] == "stock"){
    $book_id = $_GET['book_id'];
    $sql = "SELECT library.quantity AS library_quantity, 
    SUM(book_rental.quantity) AS book_rental_quantity 
    FROM library INNER JOIN book_rental ON library.book_id = book_rental.book_id 
    WHERE library.book_id = '$book_id '";
    
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
}
?>