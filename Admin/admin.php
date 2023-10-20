<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Mitr:wght@200;300;400;500;600&family=Noto+Sans+Thai:wght@100&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Admin</title>
</head>
<body>
    <div class="row">
        <div class="col-2">
            <div class="box1">
                <h2>Admin</h2>
                <hr>
                <a href="admin.php?page=user" class="btn mb-3" >User</a><br>
                <a href="admin.php?page=hotel" class="btn mb-3" >Hotel</a><br>
                <a href="admin.php?page=payment" class="btn mb-3" >Payments</a><br>
                <a href="admin.php?page=booking" class="btn mb-3" >Cancle Booking</a><br>
                <a href="admin.php?page=Requests" class="btn mb-5" >Requests</a><br>

                <hr>
                <a href="../BackEnd/logout_db.php" class="btn" >Logout</a><br>

                <hr>
            </div>
        </div>

        <div class="col-9">
            <div class="content">
                <?php
                // ตรวจสอบหน้าที่ถูกเลือกจากการคลิกที่ลิงก์
                if (isset($_GET['page'])) {
                    $page = $_GET['page'];
                    switch ($page) {
                        case 'user':
                            // แสดงหน้า User
                            include("user.php");
                            
                            break;
                        case 'payment':
                            // แสดงหน้า payment
                            include("ad_payment.php");

                            break;
                        case 'hotel':
                            // แสดงหน้า Hotel
                            include("hotel.php");

                            break;
                        case 'Requests':
                           // แสดงหน้า Requests 
                            include('reqhotel.php'); 
                            break; 
                        
                        case 'booking':
                            // แสดงหน้า bookig
                                include('ad_booking_cancle.php'); 
                                break;
                        default:
                            // หากหน้าไม่ถูกต้อง
                            echo "Invalid Page";
                            break;
                    }
                }?>

            </div>
        </div>
        

    </div>
</body>
</html>