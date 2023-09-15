
<?php 
session_start();
include('includes/connection.php'); // ดึงไฟล์เชื่อม database เข้ามา

// เช็คว่ามีการกดปุ่ม submit มาหรือไม่

if (isset($_POST['submit'])){
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // เช็คว่าข้อมูลที่รับมา เป็นค่าว่างหรือไม่ มีข้อมูลมั้ย
    if(empty($username) || empty($password) || empty($confirm_password)){

        // ถ้าเป็นข้อมูลว่าง  กำหนด error จะเก็บไว้ใน session
        $_SESSION['err_fill'] = "กรุณากรอกข้อมูลให้ครบถ้วน";

        // พอมี error ให้ส่งกลับไปที่หน้า สมัครสมาชิกเหมือนเดิม
        header('location: ../FrontEnd/register.php');
        exit; // จบการทำงาน
    }


    // ถ้าข้อมูลไม่เป็นค่าว่าง มีข้อมูล
    else{
        
        // กรณีที่ รหัสผ่านไม่ตรงกัน
        if($password != $confirm_password){
            $_SESSION['err_pw'] = "กรุณากรอกรหัสผ่านให้ตรงกัน";
            header('location: ../FrontEnd/register.php');
            exit;
        }

        // ถ้าข้อมูลถูกต้อง ทำการเพิ่มข้อมูล ลงใน database
        else{

            //เช็คว่าข้อมูลที่กรอกเข้ามาซ้ำ ใน database หรือไม่
            $select_stmt = $db->prepare("SELECT COUNT(username) AS count_uname FROM users WHERE username = :username");
            $select_stmt2 = $db->prepare("SELECT COUNT(email) AS count_email FROM users WHERE email= :email");
            
            $select_stmt->bindParam(':username', $username);
            $select_stmt2->bindParam(':email', $email);

            $select_stmt->execute();
            $select_stmt2->execute();

            $rowuser = $select_stmt->fetch(PDO::FETCH_ASSOC); // ดึงข้อมูลออกมา username
            $rowem = $select_stmt2->fetch(PDO::FETCH_ASSOC); // ดึงข้อมูลออกมา email

            // ถ้าแสดงแถวแล้วได้จำนวน > 0 แปลว่า ข้อมูลซ้ำ
            if($rowuser['count_uname'] != 0 ) {
                $_SESSION['exist_uname'] = "มี Username นี้แล้วในระบบ";
                header('location: ../FrontEnd/register.php');
                exit;

            }
            // ถ้า เมลในระบบ ซ้ำ
            else if ($rowem['count_email'] != 0){
                $_SESSION['exist_email'] = "มี Email นี้แล้วในระบบ";
                header('location: ../FrontEnd/register.php');
                exit;
            }

            // ถ้าไม่มี username ซ้ำ สมัครได้
            else{
                $insert_stmt = $db->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, 'user')");
                $insert_stmt->bindParam(':username', $username);
                $insert_stmt->bindParam(':password', $password);
                $insert_stmt->execute();

                // สมัครสำเร็จ เช็คว่า ถ้าเพิ่มข้อมูลผ่านแล้ว จะให้ทำการเก็บ username เอาไปใช้ต่อ
                if ($insert_stmt){

                    $_SESSION['username'] = $username;
                    $_SESSION['is_login'] = true; // สถานะการ login ให้ login เข้าไปเลย
                    header('location: ../FrontEnd/login.php');
                }

                // สมัครไม่สำเร็จ
                else{
                    $_SESSION['err_insert'] = "ไม่สามารถนำเข้าข้อมูลได้";
                    header('location: ../FrontEnd/register.php');
                    exit;

                }

            }


        }


    }

}






