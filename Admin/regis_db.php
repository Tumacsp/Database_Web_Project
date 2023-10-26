<?php
session_start();
include('../BackEnd/includes/connect_database.php'); // ดึงไฟล์เชื่อม database เข้ามา


if (isset($_POST['ad_submit'])) {
    $request_id = $_POST["request_id"];
    $user_id = $_POST["user_id"];
    $location_id = $_POST["location_id"];


    // เปลี่ยนสถานะของ ผู้ใช้ที่ร้องขอ  เป็น Approve
    $sql2 = "UPDATE requests SET req_status = 'Approved' WHERE request_id = :request_id";

    $stmt2 = $db->prepare($sql2);
    $stmt2->bindParam(':request_id', $request_id);
    $stmt2->execute();

    // ดึงสถานะการร้องขอ ของผู้ใช้
    $select_stmt3 = $db->prepare("SELECT req_status FROM requests WHERE user_id = :user_id");
    $select_stmt3->bindParam(':user_id', $user_id);
    $select_stmt3->execute();

    $rowstatus = $select_stmt3->fetch(PDO::FETCH_ASSOC); // ดึงข้อมูลออกมา 
    $_SESSION["req_status"] = $rowstatus['req_status'];
    $_SESSION['is_register'] = true;
    


    // เปลี่ยน role ของผู้ใช้
    $sql3 = "UPDATE users SET users_role = 'HOTELOWNER' WHERE user_id = :user_id";

    $stmt3 = $db->prepare($sql3);
    $stmt3->bindParam(':user_id', $user_id);
    $stmt3->execute();


    header('location: admin.php?page=Requests');

    



        

    
// ลงทะเบียนไม่ผ่าน

}elseif(isset($_POST['ad_cancel'])){
    $request_id = $_POST["request_id"];
    $hotel_id = $_POST["hotel_id"];




    // ลบคำร้องขอ ของผู้ใช้

    $sql4 = "DELETE FROM requests WHERE request_id = :request_id";

    $sql5 = "DELETE FROM hotels WHERE request_id = :request_id";

   
    $stmt4 = $db->prepare($sql4);
    $stmt4->bindParam(':request_id', $request_id);

    $stmt5 = $db->prepare($sql5);
    $stmt5->bindParam(':request_id', $request_id);

    
    $stmt4->execute();
    $stmt5->execute();




    $_SESSION['is_register'] = false;
    header('location: admin.php?page=Requests');



}
