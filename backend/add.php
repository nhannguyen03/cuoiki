<?php
include 'condb.php';

if (isset($_GET['add'])) {
    if ($_GET['add']=='students'){
        $student_id = $_POST['student_id'];
        $student_name = $_POST['student_name'];
        $student_class = $_POST['student_class'];
        $phone = $_POST['phone'];
    
        // Thêm thông tin thành viên vào cơ sở dữ liệu
        $sql = "INSERT INTO `students` (`student_id`, `student_name`, `student_class`, `phone`) 
        VALUES ('$student_id', '$student_name', '$student_class','$phone');";

        $location = "students.html";
    }

    elseif ($_GET['add']=='library'){
        $book_id = $_POST['book_id'];
        $book_name = $_POST['book_name'];
        $author = $_POST['author'];
        $publisher = $_POST['publisher'];
        $category = $_POST['category'];
        $book_type = $_POST['book_type'];
        $quantity = $_POST['quantity'];
        // Thêm thông tin thành viên vào cơ sở dữ liệu
        $sql = "INSERT INTO `library` (`book_id`, `book_name`, `author`, `publisher`,`category`, `book_type`, `quantity`) 
        VALUES ('$book_id', '$book_name', '$author','$publisher', '$category', '$book_type','$quantity');";

        $location = "library.php";
    }

    elseif ($_GET['add']=='librarian'){
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $username = $_POST['username'];
        $password = $_POST['password'];
     
        // Thêm thông tin thành viên vào cơ sở dữ liệu
        $sql = "INSERT INTO `librarian` (`name`, `phone`, `username`, `password`) 
        VALUES ('$name', '$phone', '$username','$password');";

        $location = "librarian.html";
    }

    elseif ($_GET['add']=='author_mgr'){
        $author_name = $_POST['author_name'];
        $style = $_POST['style'];
        $des = $_POST['des'];

        // Thêm thông tin thành viên vào cơ sở dữ liệu
        $sql = "INSERT INTO `author_mgr` (`author_name`, `style`, `des`) 
        VALUES ('$author_name', '$style', '$des');";

        $location = "library.php?status=author";
    }

    elseif ($_GET['add']=='publisher_mgr'){
        $publisher_name = $_POST['publisher_name'];
        $phone = $_POST['phone'];
        $ad = $_POST['ad'];
        // Thêm thông tin thành viên vào cơ sở dữ liệu
        $sql = "INSERT INTO `publisher_mgr` (`publisher_name`, `phone`, `ad`) 
        VALUES ('$publisher_name', '$phone', '$ad');";

        $location = "library.php?status=publisher";
    }

    elseif ($_GET['add']=='book_rental'){
        $call_card = $_POST['call_card'];
        $borrow_date = $_POST['borrow_date'];
        $due_date = $_POST['due_date'];
        $student_id = $_POST['student_id'];
        $book_ids = $_POST['book_id'];
        $quantitys = $_POST['quantity'];
        $librarian = $_COOKIE['user_name'];

        // Lặp qua mảng book_id và thực hiện câu lệnh SQL cho mỗi phần tử
        for ($i = 0; $i < count($book_ids); $i++) {
            $book_id = $book_ids[$i];
            $quantity = $quantitys[$i];

            
            // $sql1 = "SELECT * FROM `library` WHERE `book_id`='$book_id'";
            $sql1 ="SELECT library.quantity AS library_quantity, 
                            SUM(book_rental.quantity) AS book_rental_quantity 
                            FROM library INNER JOIN book_rental ON library.book_id = book_rental.book_id 
                            WHERE library.book_id = '$book_id '";
            $result = $conn->query($sql1);
            $row = $result->fetch_assoc();

            if ($row['library_quantity']-$row['book_rental_quantity']<$quantity){
                header("Location: ../add-invoice.php?error=unavaiable");
                exit();
            } else {
                $sql = "INSERT INTO `book_rental` (`call_card`, `quantity`, `borrow_date`, `due_date`, `student_id`, `book_id`, `librarian`, `status`) 
                VALUES ($call_card, '$quantity', '$borrow_date', '$due_date', '$student_id', '$book_id', '$librarian' ,'borrow');";
                
    
                if ($conn->query($sql) !== TRUE) {
                    echo "Lỗi khi thêm dữ liệu: " . $conn->error;
                }
            }
        }

        header("Location: ../index.php");
        exit();
    }

    
    if ($conn->query($sql) === TRUE) {

        $location = "Location: ../".$location;
        header($location);
        exit();
    } else {
        echo "Lỗi: " . $conn->error;
    }
    exit();
}
?>
