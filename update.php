<?php
include('config.php');
session_start();
$user_id = $_SESSION['user_id'];

if (isset($_POST['update_profile'])) {
    $update_name = mysqli_real_escape_string($con, $_POST['update_name']);
    $update_email = mysqli_real_escape_string($con, $_POST['update_email']);

    // Update name and email
    mysqli_query($con, "UPDATE user_form SET name='$update_name', email='$update_email' WHERE id='$user_id'")
        or die('Query failed');

    // Handle Password Update
    $old_password = md5($_POST['old_password']);
    $new_password = mysqli_real_escape_string($con, md5($_POST['new_password']));
    $confirm_password = mysqli_real_escape_string($con, md5($_POST['confirm_password']));

    if (!empty($_POST['new_password']) || !empty($_POST['confirm_password'])) {
        $select_password = mysqli_query($con, "SELECT password FROM user_form WHERE id='$user_id'")
            or die('Query failed');
        $fetch_password = mysqli_fetch_assoc($select_password)['password'];

        if ($old_password != $fetch_password) {
            $message[] = 'Old password does not match!';
        } elseif ($new_password != $confirm_password) {
            $message[] = 'Confirm password does not match!';
        } else {
            mysqli_query($con, "UPDATE user_form SET password='$new_password' WHERE id='$user_id'")
                or die('Query failed');
            $message[] = 'Password updated successfully!';
        }
    }

    // Handle Image Update
    if (!empty($_FILES['update_image']['name'])) {
        $image_name = $_FILES['update_image']['name'];
        $image_size = $_FILES['update_image']['size'];
        $image_tmp_name = $_FILES['update_image']['tmp_name'];
        $image_folder = 'uploaded-img/' . $image_name;

        if ($image_size > 2000000) {
            $message[] = 'Image file size is too large!';
        } else {
            move_uploaded_file($image_tmp_name, $image_folder);
            mysqli_query($con, "UPDATE user_form SET image='$image_name' WHERE id='$user_id'")
                or die('Query failed');
            $message[] = 'Image updated successfully!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="css/update.css">
</head>

<body>
    <div class="update-profile">
        <?php
        $select = mysqli_query($con, "SELECT * FROM user_form WHERE id=$user_id")
            or die('Query failed: ' . mysqli_error($con));

        if (mysqli_num_rows($select) > 0) {
            $fetch = mysqli_fetch_assoc($select);
        }
        ?>
        <form action="" method="post" enctype="multipart/form-data">
            <?php
            if ($fetch['image'] == 0) {
                echo '<img src="images/default-avatar.jpg" alt="Default Avatar">';
            } else {
                echo '<img src="uploaded-img/' . htmlspecialchars($fetch['image'], ENT_QUOTES) . '" alt="User Avatar">';
            }
            if (isset($message)) {
                foreach ($message as $msg) {
                    echo "<div class='message'>$msg</div>";
                }
            }
            ?>
            <div class="flex">
                <div class="inputBox">
                    <span>Username:</span>
                    <input type="text" name="update_name" value="<?php echo $fetch['name']; ?>" class="box">
                    <span>Your Email:</span>
                    <input type="email" name="update_email" value="<?php echo $fetch['email']; ?>" class="box">
                    <span>Update Your Picture:</span>
                    <input type="file" name="update_image" class="box">
                </div>
                <div class="inputBox">
                    <span>Old Password:</span>
                    <input type="password" name="old_password" class="box" placeholder="Enter previous password">
                    <span>New Password:</span>
                    <input type="password" name="new_password" placeholder="Enter new password" class="box">
                    <span>Confirm Password:</span>
                    <input type="password" name="confirm_password" placeholder="Confirm new password" class="box">
                </div>
            </div>
            <input type="submit" value="Update Profile" name="update_profile" class="btn">
            <a href="home.php" class="delete-btn">Go Back</a>
        </form>
    </div>
</body>

</html>
