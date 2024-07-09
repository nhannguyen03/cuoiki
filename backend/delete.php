<?php
include 'condb.php';

if (isset($_GET['delete'])) {
    if ($_GET['delete']=='students'){
        $student_id = $_GET['student_id'];

        // Xóa thành viên từ cơ sở dữ liệu
        $sql = "DELETE FROM students WHERE `students`.`student_id` = '$student_id'";

        $sql1 = "DELETE FROM `book_rental` WHERE `book_rental`.`student_id` IS NULL";

        $location = "students.html";
    }

    elseif ($_GET['delete']=='library'){
        $book_id = $_GET['book_id'];

        // Xóa thành viên từ cơ sở dữ liệu
        $sql = "DELETE FROM library WHERE `library`.`book_id` = '$book_id'";

        $sql1 = "DELETE FROM `book_rental` WHERE `book_rental`.`book_id` IS NULL";
        
        $location = "library.php";
    }

    elseif ($_GET['delete']=='librarian'){
        $name = $_GET['name'];

        // Xóa thành viên từ cơ sở dữ liệu
        $sql = "DELETE FROM librarian WHERE `librarian`.`name` = '$name'";

        $sql1 = "DELETE FROM `book_rental` WHERE `book_rental`.`librarian` IS NULL";
        
        $location = "librarian.html";
    }

    elseif ($_GET['delete']=='author_mgr'){
        $author_name = $_GET['author_name'];

        // Xóa thành viên từ cơ sở dữ liệu
        $sql = "DELETE FROM author_mgr WHERE `author_mgr`.`author_name` = '$author_name'";

        $sql1 = "DELETE FROM `library` WHERE `library`.`author`  = '$author_name'";
        $conn->query($sql1);
        $location = "library.php?status=author";
    }

    elseif ($_GET['delete']=='publisher_mgr'){
        $publisher_name = $_GET['publisher_name'];
        // Xóa thành viên từ cơ sở dữ liệu
        $sql = "DELETE FROM publisher_mgr WHERE `publisher_mgr`.`publisher_name` = '$publisher_name'";

        $sql1 = "DELETE FROM `library` WHERE `library`.`publisher`  = '$publisher_name'";
        $conn->query($sql1);

        $location = "library.php?status=publisher";
    }

    elseif ($_GET['delete']=='book_rental'){
        $rental_id = $_GET['rental_id'];

        // Xóa thành viên từ cơ sở dữ liệu
        $sql = "DELETE FROM book_rental WHERE `book_rental`.`rental_id` = $rental_id";

        $location = "index.php";
    }

    if ($conn->query($sql) === TRUE) {
        if ($_GET['delete']!='book_rental')
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
