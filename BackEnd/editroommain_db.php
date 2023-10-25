
<?php

session_start();
include('includes/connect_database.php'); // ดึงไฟล์เชื่อม database เข้ามา


// เช็คว่า กดปุ่ม update
if (isset($_POST['editroom'])) {

    // ไปหน้า room Edit
    $_SESSION["room_idse"] = $_POST["room_id"];
    header('location: ../editroom.php');
    
}



elseif(isset($_POST['editroom_delete'])){
    $room_id = $_POST["room_id"];

    // ลบห้องพัก
    $sql4 = "DELETE FROM rooms WHERE room_id = :room_id";

    $sql5 = "DELETE FROM roomsfacility WHERE room_id = :room_id";

    $stmt4 = $db->prepare($sql4);
    $stmt4->bindParam(':room_id', $room_id);
    $stmt4->execute();

    $stmt5 = $db->prepare($sql5);
    $stmt5->bindParam(':room_id', $room_id);
    $stmt5->execute();

    $_SESSION['is_roomedit'] = false;
    header('location: ../editroom_main.php');


}



      


?>

