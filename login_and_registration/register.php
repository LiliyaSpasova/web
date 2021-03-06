<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'C:\xampp\htdocs\web_project\PHPMailer-master\src\Exception.php';
require 'C:\xampp\htdocs\web_project\PHPMailer-master\src\PHPMailer.php';
require 'C:\xampp\htdocs\web_project\PHPMailer-master\src\SMTP.php';
require_once "config.php";
$username = $password = $confirmPassword = $name = $email = "";
$usernameError = $passwordError = $confirmPasswordError = $nameError = $emailError = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(!isset($_POST)) {
        echo("data not coming through");
    }
    else {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $name = $_POST["name"];
        $email = $_POST["email"];
        $confirmPassword = $_POST["psw-repeat"];

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
        } elseif(strlen(trim($password)) < 4){
            $passwordError = "Паролата трябва да съдържа поне 4 символа.";
        } else{
            $password = trim($password);
        }
        if(empty(trim($confirmPassword))){
            $confirmPasswordError = "Моля потвърдете паролата.";     
        } else{
            $confirmPassword = trim($confirmPassword);
            if(empty($passwordError) && ($password != $confirmPassword)){
                $confirmPasswordError = "Паролите не съвпадат.";
            }
        }
        if (empty(trim($name))) {
            $nameError = "Името не може да бъде празно.";
        }
        if (empty(trim($email))) {
            $emailError = "Полето не може да бъде празно";
        }

        if(empty($usernameError) && empty($passwordError) && empty($confirmPasswordError) && empty($nameError) && empty($emailError)) {
            $sql = "INSERT into users (username, password, email, role_id, name,is_approved) values (:username, :password, :email, 1, :name,:is_approved)";

            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            $accept_link = "localhost:80/web_project/login_and_registration/approve.php?e=" . $username . "&h=" . hash('sha512', 'ACCEPT');
            $decline_link = "localhost:80/web_project/login_and_registration/approve.php?e="  . $username . "&h=" . hash('sha512', 'DECLINE');
            $mail = new PHPMailer();
            $mail->IsSMTP();
            $mail->Mailer = "smtp";
            $mail->SMTPDebug  = 1;  
            $mail->SMTPAuth   = TRUE;
            $mail->SMTPSecure = "tls";
            $mail->Port       = 587;
            $mail->Host       = "smtp.gmail.com";
            $mail->Username   = "emailapprovalswebproject@gmail.com";
            $mail->Password   = "dveteBubi1";
            $mail->IsHTML(false);
            $mail->AddAddress("emailapprovalswebproject@gmail.com", "Liliya");
            $mail->SetFrom("emailapprovalswebproject@gmail.com", "Web project");
            $mail->Subject = "User needs approval";
            $content = "The user'. $param_username. ' needs your approval" .
            '----------------------------------- ' . "\r\n" .
            'Accept:  ' . $accept_link . "\r\n" ;
            $mail->MsgHTML($content); 
            if(!$mail->Send()) {
            echo "Error while sending Email.";
            var_dump($mail);
            } else {
            echo "Email sent successfully";
            }
            if($stmt = $pdo->prepare($sql))
            {
                if($stmt->execute(
                    array(":username" => $param_username,
                        ":password" => $param_password,
                        ":email" => $email,
                        ":name" => $name,
                        ":is_approved" => false
                ))) {
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
    <title>Регистрация</title>
</head>
<body >
    <div >
    <link rel="stylesheet" href="register_style.css" >
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="registration_form">
    <fieldset>
    <?php if ($passwordError != ''): ?>
        <p style="color: red;">
        <?php echo $passwordError; ?>
        </p>
        <?php endif; ?>
        <?php if ($usernameError != ''): ?>
        <p style="color: red;">
        <?php echo $usernameError; ?>
        </p>
        <?php endif; ?>
        <?php if ($confirmPasswordError != ''): ?>
        <p style="color: red;">
        <?php echo $confirmPasswordError; ?>
        </p>
        <?php endif; ?>
        <?php if ($nameError != ''): ?>
        <p style="color: red;">
        <?php echo $nameError; ?>
        </p>
        <?php endif; ?>
        <?php if ($emailError != ''): ?>
        <p style="color: red;">
        <?php echo $emailError; ?>
        </p>
        <?php endif; ?>
        <div><p id="registration_form_header">Регистрирай се тук!</p></div>
        <div>
        <label for="username">Потребителско име</label>
        <input type="text" name="username" id="username" class="form-control " value="<?php echo $username; ?>">
        </div>
        <div>
        <label for="name">Име</label>
        <input type="text" name="name" id="name"  class="form-control " value="<?php echo $nameError; ?>">
        </div>
        <div>
        <label for="email">Email</label>
        <input type="text" name="email" id="email"  class="form-control " value="<?php echo $emailError; ?>">
        </div>
        <div>
        <label for="password">Парола</label>
        <input type="password" name="password" id="password"  class="form-control " value="<?php echo $passwordError; ?>">
        </div>
        <div>
        <label for="psw-repeat">Повторете паролата</label>
        <input type="password" name="psw-repeat" id="psw-repeat"  class="form-control " value="<?php echo $confirmPasswordError; ?>">
        </div>
        <div>
        <button type="submit" id ="registration_button">Регистрирай се</button>
        </div>
        <div>
        <a href="login.php"> Вече имаш регистрация? Влез в профила си сега!</a>
        </div>
        </fieldset>
    </form>
</div>
</body>
</html>