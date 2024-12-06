<?php

    include('config.php');
    session_start();
    if(isset($_POST['submit'])){
        $email=mysqli_real_escape_string($con,$_POST['email']);
        $password=mysqli_real_escape_string($con,md5($_POST['password']));

        $select=mysqli_query($con,"SELECT * FROM `user_form`WHERE email='$email' AND password='$password'") or die ('query failed');

        if(mysqli_num_rows($select) >0){
            $row=mysqli_fetch_assoc($select);
            $_SESSION['user_id']=$row['id'];
            header('location: home.php');
        }
        else{
            $message[]="incorrect email or password!";
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="form-container">
        <form action="" method="post" enctype="multipart/form-data">
            <h3>login now</h3>
            <?php
                if(isset($message))
                {
                    foreach($message as $message)
                    {
                        echo "<div class='message'>$message</div>";
                    }
                }
            ?>
            <input type="email" name="email" class="box" require c placeholder="enter email">
            <input type="password" name="password" class="box" require c placeholder="enter password">
            <input type="submit" name="submit" value="login now" class="btn">
            <p>don't have an account? <a href="register.php">register now</a></p>
        </form>
    </div>
</body>
</html>