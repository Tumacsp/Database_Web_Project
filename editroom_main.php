
<?php

session_start();

// ถ้ามี $_SESSION['is_logged_in'] แสดงว่ามีการ login เข้ามาแล้ว

if (!isset($_SESSION['is_login'])) {
    header('location: login.php'); // ถ้าไม่มีให้เด้งไป login

}elseif ($_SESSION["role"] != 'HOTELOWNER'){
    header('location: registerhotel.php');

}


?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Mitr:wght@200;300;400;500;600&family=Noto+Sans+Thai:wght@100&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/styles.css">

    <title>Hotel Project</title>

</head>

<body>

    <!---- Navbar ---->
    <?php require('navbar.php'); ?>

    <!-- Change Profile-->
    <div class="container">
        <div class="row">
            <h1 class="mt-5">Edit Room</h1>
            <p>แก้ไขข้อมูลห้องพักของคุณ</p>

                <div class="modal-body">
                    <hr>
                    <div style="height: 100%; overflow: auto;">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Room Id</th>
                                    <th>Room Type</th>
                                    <th>Room Size</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php

                                    include('BackEnd/includes/connect_database.php'); // ดึงไฟล์เชื่อม database เข้ามา


                                    // คำสั่ง SQL สำหรับดึงข้อมูลจากตาราง room
                                    $sql = "SELECT * FROM rooms WHERE hotel_id = :hotel_id";

                                    $stmt = $db->prepare($sql);
                                    $stmt->bindParam(':hotel_id', $_SESSION["hotel_id"]);
                                    $stmt->execute();


                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) :
                                ?>
                                    <tr>
                                        <form method="POST" action="BackEnd/editroommain_db.php">
                                            <td><?php echo $row["room_id"]; ?></td>
                                            <td><?php echo $row["rooms_type"]; ?></td>
                                            <td><?php echo $row["rooms_size"]; ?></td>
                                            <td><?php echo $row["rooms_description"]; ?></td>
                                            <td>
                                                <?php $_SESSION["room_id"] = $row['room_id']; ?> 
                                                <?php $_SESSION["hotel_id"] = $row['hotel_id']; ?>


                                                <button type="submit" name="editroom" class="btn btn-success">Edit</button>
                                                <button type="submit" name="editroom_delete" class="btn btn-danger">Delete</button>

                                            </td>
                                        </form>
                                    </tr>
                                <?php endwhile ?>

                            </tbody>
                        </table>
                    </div>
                </div>	
        </div>
    </div>





    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>