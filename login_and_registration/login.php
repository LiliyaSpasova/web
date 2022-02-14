<?php
session_start();
 
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    if ($_SESSION["role"] == 1 || $_SESSION["role"] == 0)
        header("location: ..\index.php");
    else 
        header("location: ..\indexStudent.php");
    exit;
}
 
require_once "config.php";
 
$username = $password = "";
$usernameError = $passwordError = $loginError = "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    if(empty(trim($_POST["username"]))){
        $usernameError = "Моля въведете потребителско име.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    if(empty(trim($_POST["password"]))){
        $passwordError = "Моля въведете парола.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    if(empty($usernameError) && empty($passwordError)){

        $sql = "SELECT id, username, password, role_id, is_approved FROM users WHERE username = :username";
        
        if($stmt = $pdo->prepare($sql)){

            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            
            $param_username = trim($_POST["username"]);
            
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    if($row = $stmt->fetch()){
                        $id = $row["id"];
                        $username = $row["username"];
                        $hashed_password = $row["password"];
                        $role = $row["role_id"];
                        $is_approved = $row["is_approved"];
                        if(password_verify($password, $hashed_password)){
                            if ($is_approved == 1) {
                                session_start();
                            
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["username"] = $username;       
                                $_SESSION["role"] = $role;

                                if ($_SESSION["role"] == 0 || $_SESSION["role"] == 1) {
                                    header("location: ..\index.php");
                                }
                                else {
                                    header("location: ..\indexStudent.php");
                                }
                            }
                            else {
                                header("location: ..\\notApproved.php");
                            }

                        } else{
                            $loginError = "Грешно потребителско име или парола, опитай отново!";
                        }
                    }
                } else{
                    $loginError = "Грешка при обработка, има два акаунта с подобна информация.";
                }
            } else{
                echo "Грешката е при нас. Моля опитайте по-късно.";
            }

            unset($stmt);
        }
    }
    unset($pdo);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="login_style.css">

    <style>
    body {
        font: 14px sans-serif;
    }

    .wrapper {
        width: 360px;
        padding: 20px;
    }
    </style>
</head>

<body>
    <div>
        <form id="login_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <?php if ($usernameError != ''): ?>
            <p style="color: red;">
            <?php echo $usernameError; ?>
            </p>
            <?php endif; ?>
            <?php if ($passwordError != ''): ?>
            <p style="color: red;">
            <?php echo $passwordError; ?>
            </p>
            <?php endif; ?>
            <?php if ($loginError != ''): ?>
            <p style="color: red;">
            <?php echo $loginError; ?>
            </p>
            <?php endif; ?>
            <fieldset>
                <p id="login_form_header">Вход</p>
                <p>Моля въведете потребителското си име и парола за достъп.</p>
                <div class="form-group">
                    <label>Потребителско име</label>
                    <input type="text" name="username"
                        class="form-control <?php echo (!empty($usernameError)) ? 'invalid' : ''; ?>"
                        value="<?php echo $username; ?>">
                    <span class="invalid-feedback"><?php echo $usernameError; ?></span>
                </div>
                <div class="form-group">
                    <label>Парола</label>
                    <input type="password" name="password"
                        class="form-control <?php echo (!empty($passwordError)) ? 'invalid' : ''; ?>">
                    <span class="invalid-feedback"><?php echo $passwordError; ?></span>
                </div>
                <div class="form-group">
                    <input id="login_button" type="submit" class="btn btn-primary" value="Вход" >
                </div>
                <p>Ако нямате акаунт,<a href="register.php">Регистрирайте се сега.</a></p>
            </fieldset>
        </form>
    </div>
</body>

</html>