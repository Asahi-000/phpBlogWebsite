<?php
    session_start();
    require 'config/database.php';

    if(isset($_POST['submit']))
    {
        $firstname = filter_var($_POST['firstname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $lastname = filter_var($_POST['lastname'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $username = filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $createpassword = filter_var($_POST['createpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $confirmpassword = filter_var($_POST['confirmpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $is_admin = filter_var($_POST['userrole'], FILTER_SANITIZE_NUMBER_INT);
        $avatar = $_FILES['avatar'];

        if(!$firstname){
            $_SESSION['add-user'] = "Please enter your first name.";
        }
        else if(!$lastname){
            $_SESSION['add-user'] = "Please enter your last name.";
        }
        else if(!$username){
            $_SESSION['add-user'] = "Please enter your username.";
        }
        else if(!$email){
            $_SESSION['add-user'] = "Please enter a valid email.";
        }
        else if(strlen($createpassword) < 8 || strlen($confirmpassword) < 8)
        {
            $_SESSION['add-user'] = "Password is too short. Enter a password not less than 8 characters.";
        }
        else if(!$avatar['name']){
            $_SESSION['add-user'] = "Please add an avatar.";
        }
        else{
            if($createpassword !== $confirmpassword){
                $_SESSION['add-user'] = "Passwords do not match.";
            }
            else{
                $hashed_password = password_hash($createpassword, PASSWORD_DEFAULT);

                $user_check_query = "SELECT * FROM users WHERE username = '$username' OR email ='$email'";
                $user_check_result = mysqli_query($conn, $user_check_query);
                if(mysqli_num_rows($user_check_result) > 0)
                {
                    $_SESSION['add-user'] = "Username or Email already exists.";
                }
                else
                {
                    $time = time();
                    $avatar_name = $time . $avatar['name'];
                    $avatar_tmp_name = $avatar['tmp_name'];
                    $avatar_destination_path = '../images/' . $avatar_name;

                    $allowed_files = ['png', 'jpg', 'jpeg'];
                    $extension = explode('.', $avatar_name);
                    $extension = end($extension);

                    if(in_array($extension, $allowed_files))
                    {
                        if($avatar['size'] < 1000000)
                        {
                            move_uploaded_file($avatar_tmp_name, $avatar_destination_path);
                        }
                        else{
                            $_SESSION['add-user'] = "File size is too big. Upload less than 1MB.";
                        }

                    }
                    else{
                        $_SESSION['add-user'] = "File should be png, jpg or jpeg.";
                    }
                }
            }
        }

        if(isset($_SESSION['add-user']))
        {
            $_SESSION['add-user-data'] = $_POST;
            header('Location: ' .ROOT_URL. 'admin/add-user.php');
            die();
        }
        else{
            $insert_user_query = "INSERT INTO users SET firstname='$firstname', lastname='$lastname', username='$username', email='$email', password='$hashed_password', avatar='$avatar_name', is_admin=$is_admin";
            $insert_user_result = mysqli_query($conn, $insert_user_query);

            if(!mysqli_errno($conn))
            {
                $_SESSION['add-user-success'] = "New user $firstname $lastname added successfully.";
                header('Location: ' .ROOT_URL. 'admin/manage-users.php');
                die();
            }
        }

    }
    else
    {
        header('Location: ' .ROOT_URL. 'admin/add-user.php');
        die();
    }
?>