<?php
include 'condb.php';

if (isset($_GET['edit'])) {
    if ($_GET['edit']=='students'){
        $student_id_old = $_GET['student_id_old'];

        $student_id = $_POST['student_id'];
        $student_name = $_POST['student_name'];
        $student_class = $_POST['student_class'];
        $phone = $_POST['phone'];
    
        // Thêm thông tin thành viên vào cơ sở dữ liệu
        $sql = "UPDATE `students` SET `student_id`='$student_id',`student_name`='$student_name',`student_class`='$student_class',`phone`='$phone'
        WHERE `student_id` = '$student_id_old'";

        $sql1 = "UPDATE `book_rental` SET `student_id` = '$student_id' WHERE `book_rental`.`student_id` IS NULL";

        $location = "students.html";
    }

    elseif ($_GET['edit']=='librarian'){
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $username = $_POST['username'];
        $password = $_POST['password'];
    
        // Thêm thông tin thành viên vào cơ sở dữ liệu
        $sql = "UPDATE `librarian` SET `phone`='$phone',`username`='$username',`password`='$password'
        WHERE `name` = '$name'";

        $location = "librarian.html";
    }

    elseif ($_GET['edit']=='library'){
        $book_id_old = $_GET['book_id_old'];

        $book_id = $_POST['book_id'];
        $book_name = $_POST['book_name'];
        $author = $_POST['author'];
        $publisher = $_POST['publisher'];
        $category = $_POST['category'];
        $book_type = $_POST['book_type'];
        $quantity = $_POST['quantity'];

        // Thêm thông tin thành viên vào cơ sở dữ liệu
        $sql = "UPDATE `library` SET `book_id`='$book_id',`book_name`='$book_name',`author`='$author',`publisher`='$publisher',`category`='$category',`book_type`='$book_type',`quantity`='$quantity'
        WHERE `book_id` = '$book_id_old'";

        $sql1 = "UPDATE `book_rental` SET `book_id` = '$book_id' WHERE `book_rental`.`book_id` IS NULL";

        $location = "library.php";
    }

    elseif ($_GET['edit']=='book_rental'){
        if (isset($_GET['status']) && $_GET['status'] == 'done') {
            $currentDate = date('d-m-Y');

            $rental_id = $_GET['rental_id'];
            $sql = "UPDATE `book_rental` SET `status`='done', `actual_date`='$currentDate'
            WHERE `rental_id` = '$rental_id'";
            $conn->query($sql);
            header("Location: ../index.php");
            exit();
        }        

        $rental_id_old = $_GET['rental_id_old'];
        
        $borrow_date = $_POST['borrow_date'];
        $due_date = $_POST['due_date'];
        $student_id = $_POST['student_id'];
        $book_id = $_POST['book_id'];
        // Thêm thông tin thành viên vào cơ sở dữ liệu
        $sql = "UPDATE `book_rental` SET `borrow_date`='$borrow_date',`due_date`='$due_date',`student_id`='$student_id',`book_id`='$book_id'
        WHERE `rental_id` = '$rental_id_old'";
        $location = "index.php";
    }
    if (($conn->query($sql) === TRUE)) {
        if ($_GET['edit']!='book_rental' && $_GET['edit']!='librarian')
            $conn->query($sql1);

        $location = "Location: ../".$location;
        header($location);
        exit();
    } else {
        echo "Lỗi: " . $conn->error;
    }
    exit();
}
?>
