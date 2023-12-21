<?php
session_start();
if(isset($_SESSION['login']) && $_SESSION['login'] == 1){
    header('Location: http://localhost/bit/autorizacija/index.php');
    die;
}
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if($_POST['password'] != $_POST['password2']){
        $_SESSION['error'] = 'Passwords do not match';
        $_SESSION['old_data'] = $_POST;
        header('Location: http://localhost/bit/autorizacija/register.php');
        exit;
    }
    $users = file_get_contents(__DIR__.'/data/users.ser');
    $users = unserialize($users);
    
    foreach($users as $user){
        if($user['email'] == $_POST['email']){
            $_SESSION['error'] = 'User with this email already exists';
            $_SESSION['old_data'] = $_POST;
            header('Location: http://localhost/bit/autorizacija/register.php');
            exit;
        }
    }
    $user = [
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'password' => sha1($_POST['password']),
    ];
    $users[] = $user;
    file_put_contents(__DIR__.'/data/users.ser', serialize($users));
    header('Location: http://localhost/bit/autorizacija/login.php');
    exit;
  }  
  if(isset($_SESSION['error'])){
        $error = $_SESSION['error'];
        unset($_SESSION['error']);
    }
    if(isset($_SESSION['old_data'])){
        $old_data = $_SESSION['old_data'];
        unset($_SESSION['old_data']);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forest</title>
</head>
<body>
    <h1>Register to Forest</h1>
    <?php if(isset($error)): ?>
        <h1 style="color: crimson;"> <?= $error ?></h1>
        <?php endif ?>
    <form action="" method="post">
        <input type="text" name="name" placeholder="Name" value="<?= $old_data['name'] ?? '' ?>">
        <input type="text" name="email" placeholder="Email" value="<?= $old_data['email'] ?? '' ?>">
        <input type="password" name="password" placeholder="password">
        <input type="password" name="password2" placeholder="repeat password">
        <button type="submit">Register</button>
    </form>
    
</body>
</html>