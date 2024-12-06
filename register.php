<?php

    include('config.php');
    if(isset($_POST['submit'])){
        $name=mysqli_real_escape_string($con,$_POST['name']);
        $email=mysqli_real_escape_string($con,$_POST['email']);
        $password=mysqli_real_escape_string($con,md5($_POST['password']));
        $cpassword=mysqli_real_escape_string($con,md5($_POST['cpassword']));
        $password=mysqli_real_escape_string($con,md5($_POST['password']));
        $image= $_FILES['image']['name'];
        $image_size= $_FILES['image']['size'];
        $image_tmp_name= $_FILES['image']['tmp_name'];
        $image_folder='uploaded-img/'.$image;

        $select=mysqli_query($con,"SELECT * FROM `user_form`WHERE email='$email' AND password='$password'") or die ('query failed');

        if(mysqli_num_rows($select) >0){
            $message[]=' user already exist';
        }else{
            if($password !=$cpassword ){
                $message[]='confirm passowrd not matched!';
            }
            elseif($image_size>2000000){
                $message[]='image size is to large!';
            }
            else
            {
                $insert=mysqli_query($con,"INSERT INTO user_form(name,email,password,image)
                    VALUES ('$name', '$email','$password','$image')") or die("query failed");

                    if($insert){
                        move_uploaded_file($image_tmp_name,$image_folder);
                        $message[]='registered successfully!';
                        header("location: login.php");
                    }else
                    {
                        $message[]='registeration failed!';
                    }
            }
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>register</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="form-container">
        <form action="" method="post" enctype="multipart/form-data">
            <h3>register now</h3>
            <?php
                if(isset($message))
                {
                    foreach($message as $message)
                    {
                        echo "<div class='message'>$message</div>";
                    }
                }
            ?>
            <input type="text" name="name" class="box" require c placeholder="enter username">
            <input type="email" name="email" class="box" require c placeholder="enter email">
            <input type="password" name="password" class="box" require c placeholder="enter password">
            <input type="password" name="cpassword" class="box" require c placeholder="confirm password">
            <input type="file" class="box" name="image">
            <input type="submit" name="submit" value="register now" class="btn">
            <p>already have an account? <a href="login.php">login now</a></p>
        </form>
    </div>
</body>
</html>