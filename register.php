<?php
require_once "config.php";

$username = $password = $confirmPassword =  "";
$usernameError = $passwordError = $confirmPasswordError = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(!isset($_POST)) {
        echo("data not coming through");
    }
    else {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $confirmPassword = $_POST["psw-repeat"];

        $usernameError = $passwordError = $confirmPasswordError = "";

        if (empty(trim($username))) {
            $usernameError = "Моля въведете потребителско име.";
        }
        else if(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))) {
            $usernameError = "Потребителското име може да съдържа само букви, цифри и долни черти.";
        }
        else {
            $sql = "SELECT id from users where username = :username";

            if($stmt = $pdo->prepare($sql)) {
                if($stmt->execute(array(
                    ":username" => $username
                ))) {
                    if($stmt->rowCount() == 1) {
                        $usernameError = "Вече съществува акаунт с това потребителско име.";
                    }
                }
            }
            else {
                $username = "Моля опитайте по-късно";
            }   
        }

        if(empty(trim($password))){
            $passwordError = "Моля въведете парола.";     
        } elseif(strlen(trim($_POST["password"])) < 4){
            $passwordError = "Паролата трябва да съдържа поне 4 символа.";
        } else{
            $password = trim($_POST["password"]);
        }
        if(empty(trim($confirmPassword))){
            $confirmPasswordError = "Моля потвърдете паролата.";     
        } else{
            $confirmPassword = trim($confirmPassword);
            if(empty($passwordError) && ($password != $confirmPassword)){
                $confirmPasswordError = "Паролите не съвпадат.";
            }
        }

        if(empty($usernameError) && empty($passwordError) && empty($confirmPasswordError)) {
            $sql = "INSERT into users (username, password, role) values (:username, :password, 2)";

            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            
            if($stmt = $pdo->prepare($sql))
            {
                if($stmt->execute(
                    array(":username" => $param_username,
                        ":password" => $param_password)
                )) {
                    header("location: login.php");
                }
                else {
                    echo ("Нещо се обърка. Моля опитайте по-късно.");
                }
            }
            else {
                echo ("Нещо се обърка. Моля опитайте по-късно.");
            }
                
            unset($stmt);
        }
    }
    unset($pdo);
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel = "stylesheet" href = "register_style.css">
    <title>Register</title>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="username">Потребителско име</label>
        <input type="text" name="username" id="username" class="form-control <?php echo (!empty($usernameError)) ? 'invalid' : ''; ?>" value="<?php echo $username; ?>">

        <label for="password">Парола</label>
        <input type="password" name="password" id="password">

        <label for="psw-repeat">Повторете паролата</label>
        <input type="password" name="psw-repeat" id="psw-repeat">

        <button type="submit">Регистрирай се</button>
    </form>
</body>
</html>