
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

            <form class="p-5 card" action="BackEnd/editroom_db.php" method="post">
                
                <!-- เช็คว่ามี error มั้ย  ถ้าเป็นค่าว่าง -->
                <?php if (isset($_SESSION['err_editroom'])) : ?>
                    <!-- ถ้ามี error ให้แสดง alert  ถ้าเป็นข้อมูลว่าง แสดงข้อความเตือน-->
                    <div class="alert alert-danger" role="alert">
                        <?php echo $_SESSION['err_editroom']; ?>
                    </div>
                <?php endif; ?>

                <!-- เช็คว่ามี error มั้ย  อัปเดตข้อมูลไม่สำเร็จ -->
                <?php if (isset($_SESSION['err_update'])) : ?>
                    <!-- ถ้ามี error ให้แสดง alert -->
                    <div class="alert alert-danger" role="alert">
                        <?php echo $_SESSION['err_update']; ?>
                    </div>
                <?php endif; ?>

                    <!-- อัปเดตข้อมูลแล้ว -->
                <?php if (isset($_SESSION['room_update'])) : ?>
                    <!-- ให้แสดง alert -->
                    <div class="alert alert-success" role="alert">
                        <?php echo $_SESSION['room_update']; ?>
                    </div>
                <?php endif; ?>

                <!-- เช็คว่ามี error มั้ย  ถ้าเป็นค่าว่าง -->
                <?php if (isset($_SESSION['err_editroomimg'])) : ?>
                    <!-- ถ้ามี error ให้แสดง alert  ถ้าเป็นข้อมูลว่าง แสดงข้อความเตือน-->
                    <div class="alert alert-danger" role="alert">
                        <?php echo $_SESSION['err_editroomimg']; ?>
                    </div>
                <?php endif; ?>



                <div class="modal-body">
                    <span class="rounded-pill bg-light text-dark mb-5 text-wrap lh-base">หมายเหตุ: รายละเอียดของคุณต้องตรงกับข้อมูลเท็จจริง

                    <div class="container-fluid mt-5">
                        <div class="row">
                            <div class="col-md-6 ps-0 mb-3">
                                <label class="form-label">ประเภทห้องพัก</label>
                                <select name="room_type" class="form-select shadow-none">
                                    <option value="Standard Room">Standard Room</option>
                                    <option value="Deluxe Room">Deluxe Room</option>
                                    <option value="Family Room">Family Room</option>
                                </select>
                                
                            </div>

                            <div class="col-md-12 ps-0 mb-3">
                                <label class="form-label">สิ่งอำนวยความสะดวก</label>
                                <textarea type="text" name="room_remake" class="form-control shadow-none"></textarea>
                            </div>

                            <div class="col-md-12 ps-0 mb-3">
                                <label class="form-label">รายละเอียดเพิ่มเติมเกี่ยวกับห้องพัก</label>
                                <textarea type="text" name="room_description" class="form-control shadow-none"></textarea>
                            </div>

                            <div class="col-md-4 ps-0 mb-3">
                                <label class="form-label">ระบุบราคาห้องพัก</label>
                                <input type="number" name="room_price" class="form-control shadow-none">
                            </div>

                            <div class="col-md-4 ps-0 mb-3">
                                <label class="form-label">กำหนดจำนวนพักได้สูงสุด</label>
                                <input type="number" name="room_size" class="form-control shadow-none">
                            </div>

                            <div class="col-md-6 ps-0 mb-3">
                                <label class="form-label">อัปโหลดรูปภาพห้องพักของคุณ</label>
                                <input type="file" name="room_img" class="form-control shadow-none">
                            </div>

                        <div class="text-center my-1">
                            <!-- อัปเดตข้อมูลแล้ว -->
                            <?php if (isset($_SESSION['room_update'])) : ?>
                                <!-- ให้แสดง button back -->
                                <button type="submit" name="editroom_back" class="btn btn-danger shadow-none mb-4 mt-4 me-lg-3 me-2">Go Back</button>
                                <button type="submit" name="editroom_update" class="btn btn-primary shadow-none mb-4 mt-4 me-lg-3 me-2">Update</button>

                            <?php else : ?>
                                <button type="submit" name="editroom_cancel" class="btn btn-secondary shadow-none mb-4 mt-4 me-lg-3 me-2">Cancel</button>
                                <button type="submit" name="editroom_update" class="btn btn-primary shadow-none mb-4 mt-4 me-lg-3 me-2">Update</button>

                            <?php endif; ?>
                        
                        </div>
                    </div>
                </div>	
            </form>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>

<?php

if (isset($_SESSION['err_editroom']) || isset($_SESSION['err_update']) 
    || isset($_SESSION['room_update']) || isset($_SESSION['err_editroomimg'])) {
    unset($_SESSION['err_editroom']);
    unset($_SESSION['err_update']);
    unset($_SESSION['room_update']);
    unset($_SESSION['err_editroomimg']);
}


?>