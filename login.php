<?php
session_start();
if (isset($_SESSION['login']) && $_SESSION['login'] == 1){
    header('Location: http://localhost/bit/autorizacija/index.php');
    exit;
}
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $users = file_get_contents(__DIR__.'/data/users.ser');
        $users = unserialize($users);
        foreach ($users as $user) {
            if ($user['email'] == $_POST['email']) {
                if ($user['password'] == sha1($_POST['password'])) {
                    $_SESSION['login'] = 1;
                    $_SESSION['name'] = $user['name'];
                    header('Location: http://localhost/bit/autorizacija/auth.php');
                    die;
                }
            }
        }
        $_SESSION['error'] = 'Wrong email or password';
        header('Location: http://localhost/bit/autorizacija/login.php');
        die;
    }

    if (isset($_SESSION['error'])) {
        $error = $_SESSION['error'];
        unset($_SESSION['error']);
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
    <h1>Login</h1>
    <?php if(isset($error)): ?>
    <h1 style="color: crimson;"> <?= $error ?></h1>
    <?php endif ?>
    <form action="" method="post">
        <input type="text" name="email" placeholder="Email">
        <input type="password" name="password" placeholder="password">
        <button type="submit">Log In</button>
    </form>
    
</body>
</html>