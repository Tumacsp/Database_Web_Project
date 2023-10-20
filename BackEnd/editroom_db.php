
<?php

session_start();
include('includes/connect_database.php'); // ดึงไฟล์เชื่อม database เข้ามา


// เช็คว่า กดปุ่ม update

if (isset($_POST['editroom_update'])) {

    $room_id = $_POST["room_id"];
    $roomtype = $_POST['room_type'];
    $roomdes = $_POST['room_description'];
    $roomprice = $_POST['room_price'];
    $roomsize = $_POST['room_size'];
    $room_facility = $_POST['room_facility'];
    $room_num = $_POST['room_num'];




    // เรียกใช้ hotel id
    $select_stmt4 = $db->prepare("SELECT * FROM hotels WHERE user_id = :user_id");
    $select_stmt4->bindParam(':user_id', $_SESSION["userid"]);
    $select_stmt4->execute();
    $row = $select_stmt4->fetch(PDO::FETCH_ASSOC);

    $_SESSION["hotel_id"] = $row['hotel_id']; // เอาไปใช้ต่อ


    // เช็คว่า เป็นข้อมูลที่รับมาเป็นค่าว่าง หรือไม่
    if ((empty($roomtype) || empty($roomdes) || empty($roomprice) 
    || empty($roomsize) || empty($room_facility) || empty($room_num))) {

        $_SESSION['err_editroom'] = "โปรดระบุข้อมูลของคุณให้ครบถ้วน";
        header('location: ../insertroom.php'); // กลับไปหน้า edit
        exit; // จบการทำงาน
    }



    // กรอกข้อมูลครบ 
    else {

        //////////////////////////// IMAGE FILES ////////////////////////////

        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            exit('POST request method required');
        }

        if (empty($_FILES)) {
            exit('$_FILES is empty - is file_uploads set to "Off" in php.ini?');
        }

        if ($_FILES["room_img"]["error"] !== UPLOAD_ERR_OK) {

            switch ($_FILES["room_img"]["error"]) {
                case UPLOAD_ERR_PARTIAL:
                    exit('File only partially uploaded');
                case UPLOAD_ERR_NO_FILE:
                    $_SESSION['err_editroomimg'] = "โปรดใส่รูปภาพของคุณให้ครบถ้วน";
                    header('location: ../insertroom.php'); // กลับไปหน้า edit
                    exit;

                case UPLOAD_ERR_EXTENSION:
                    exit('File upload stopped by a PHP extension');
                case UPLOAD_ERR_FORM_SIZE:
                    exit('File exceeds MAX_FILE_SIZE in the HTML form');
                case UPLOAD_ERR_INI_SIZE:
                    exit('File exceeds upload_max_filesize in php.ini');
                case UPLOAD_ERR_NO_TMP_DIR:
                    exit('Temporary folder not found');
                case UPLOAD_ERR_CANT_WRITE:
                    exit('Failed to write file');
                default:
                    exit('Unknown upload error');
            }
        }


        // เช็คว่านามสกุลไฟล์เป็น
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime_type = $finfo->file($_FILES["room_img"]["tmp_name"]);

        $mime_types = ["image/gif", "image/png", "image/jpeg"];

        if (!in_array($_FILES["room_img"]["type"], $mime_types)) {
            print_r($_FILES);
            exit("Invalid file type");
        }

        // แก้ชื่อไฟล์ถ้าชื่อไฟล์มีตัวอักษรพิเศษ
        $pathinfo = pathinfo($_FILES["room_img"]["name"]);

        $base = $pathinfo["filename"];

        $base = preg_replace("/[^\w-]/", "_", $base);

        $filename = $base . "." . $pathinfo["extension"];

        //ตำแหน่งไฟล์
        $destination = __DIR__ . "/uploads_img/" . $filename;

        // ชื่อไฟล์ซ้ำ
        $i = 1;

        while (file_exists($destination)) {

            $filename = $base . "($i)." . $pathinfo["extension"];
            $destination = __DIR__ . "/uploads_img/" . $filename;

            $i++;
        }

        if (!move_uploaded_file($_FILES["room_img"]["tmp_name"], $destination)) {

            exit("Can't move uploaded file");
        }

        // echo "File uploaded successfully.";

        //////////////////////////// END IMAGE FILES ////////////////////////////

        // ลบรูปก่อนที่จะเก็บไฟล์ใหม่
        $delete_stmt = $db->prepare('SELECT * FROM rooms WHERE room_id = :room_id');
        $delete_stmt->bindParam(':room_id', $room_id);
        $delete_stmt->execute();
        $row = $delete_stmt->fetch(PDO::FETCH_ASSOC);

        unlink(__DIR__ . "/uploads_img/" . $row['rooms_img']);


        //  เพิ่มข้อมูลลงในตาราง rooms
        $sql = "UPDATE rooms 
            SET rooms_price = :room_price, 
                rooms_type = :room_type, 
                rooms_size = :room_size, 
                rooms_description = :room_description, 
                rooms_img = :rooms_img,
                rooms_number = :room_num 
            WHERE room_id = :room_id";

        $update_stmt = $db->prepare($sql);

        $update_stmt->bindParam(':room_price', $roomprice);
        $update_stmt->bindParam(':room_type', $roomtype);
        $update_stmt->bindParam(':room_size', $roomsize);
        $update_stmt->bindParam(':room_description', $roomdes);
        $update_stmt->bindParam(':rooms_img', $filename);
        $update_stmt->bindParam(':room_num', $room_num);
        $update_stmt->bindParam(':room_id', $room_id);


        $update_stmt->execute();


        ////////////////////////////////////////////////////////////

        // อัปเดทข้อมูลลงในตาราง roomsfacility

         // ลบข้อมูลเก่า
         $sql6 = "DELETE FROM roomsfacility WHERE room_id = :room_id";
         $destmt6 = $db->prepare($sql6);
         $destmt6->bindParam(':room_id',  $room_id);
         $destmt6->execute();


        // เพิ่มข้อมูลลงในตาราง roomsfacility

        foreach ($room_facility as $facility_id) {
            $sqlf = "INSERT INTO `roomsfacility` (`facility_id`, `room_id`)
            VALUES(:facility_id, :room_id)";

            $select_stmt6 = $db->prepare($sqlf);

            $select_stmt6->bindParam(':facility_id',  $facility_id);
            $select_stmt6->bindParam(':room_id', $room_id);
            $select_stmt6->execute();
        }



        // เพิ่มข้อมูลแล้ว 
        if ($update_stmt) {
            $_SESSION['room_update'] = "อัปเดตข้อมูลของคุณเรียบร้อยแล้ว";
            header('location: ../editroom.php');
        }

        // เพิ่มข้อมูลไม่สำเร็จ
        else {
            $_SESSION['err_update'] = "ไม่สามารถนำเข้าข้อมูลได้";
            header('location: ../editroom.php');
            exit;
        }
    }
} elseif (isset($_POST['editroom_back'])) {

    header('location: ../profilehotel.php');
    exit;
} elseif (isset($_POST['editroom_cancel'])) {

    header('location: ../profilehotel.php');
    exit;
}






?>

