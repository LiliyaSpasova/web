<?php
session_start();
 
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: ..\index.php");
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

        $sql = "SELECT id, username, password FROM users WHERE username = :username";
        
        if($stmt = $pdo->prepare($sql)){

            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            
            $param_username = trim($_POST["username"]);
            
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    if($row = $stmt->fetch()){
                        $id = $row["id"];
                        $username = $row["username"];
                        $hashed_password = $row["password"];
                        if(password_verify($password, $hashed_password)){
                            session_start();
                            
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;       
                            
                            $getRole = "SELECT role_id from users where id=:id";
                            
                            if($stmt = $pdo->prepare($getRole)) {
                                $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                                if($stmt->execute()) {
                                    if($getRow = $stmt->fetch()) {
                                        $_SESSION["role"] = $getRow["role_id"];
                                    }
                                }
                            }

                            header("location: ..\index.php");
                        } else{
                            $loginError = "Невалидно потребителско име или парола.";
                        }
                    }
                } else{
                    $loginError = "Невалидно потребителско име или парола.";
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
    <link rel="stylesheet" href="">
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
    <div >
        <link rel="stylesheet" href="login_style.css">
        <form id="login_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <?php 
            if(!empty($loginError)){
                echo '<div class="alert alert-danger">' . $loginError . '</div>';
            }        
            ?>
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