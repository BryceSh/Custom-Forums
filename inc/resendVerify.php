<?

use PHPMailer\PHPMailer\PHPMailer;

if (isset($_GET['userID'])) {

    require 'db.inc.php';

    $userID = $_GET['userID'];

    $checkSQL = mysqli_query($conn, "SELECT * FROM users WHERE u_id='$userID'");
    if (mysqli_num_rows($checkSQL) == "1") {

        $userHash = generateRandomHash();
        $updateSQL = mysqli_query($conn, "UPDATE users SET u_token='$userHash' WHERE u_id='$userID'");

        // Lets get the user information
        $user = mysqli_fetch_assoc($checkSQL);
        $userEmail = $user['u_email'];
        $userUsername = $user['u_username'];

        $generatedLink = "forums.ubinids.com/inc/verify.php?userID=" . $user['u_id'] . "&token=" . $userHash;


        require '../vendor/autoload.php';
        require '../AppSettings.php';


        $emailBody = '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <style>
                .btn {
                    border-radius: 16px;
                    padding: 12px 24px;
                    color: white;
                    background-color: rgb(33, 154, 214);
                    text-decoration: none;
                }
                body {
                    text-align: center;
                }
            </style>
        </head>
        <body>
            <h3>Plase confirm your email</h3>
            <p>Hello '. $user['u_username'] .',<br>
                Please click the button below to confirm your email address<br>
                If you did not create this account, please disregard this email<br>
                <a href="'.$generatedLink.'">If the buttom below does not work, click here!</a>  
            </p>
            <a class="btn" href='.$generatedLink.'>Click here!</a>
        </body>
        </html>';

        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->SMTPDegub = 2;
        $mail->Host = $smtpHost;
        $mail->Port = $smtpPort;
        $mail->isHTML(true);
        $mail->SMTPSecure = $smtpSecure;
        $mail->SMTPAuth = true;
        $mail->Username = $smtpUsername;
        $mail->Password = $smtpPassword;
        $mail->setFrom($smtpUsername, $smtpAccountName);
        $mail->addReplyTo($smtpUsername, $smtpAccountName);
        $mail->addAddress($userEmail, $userUsername);
        $mail->Subject = "Email Conformation";
        $mail->Body = $emailBody;

        if (!$mail->send()) {
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo '<br><br>The email message was send!';
        }

        header("Location: ../profile.php");

    } else {
        redirect();

    }

} else {
    redirect();
}

function redirect() {
    header("Location: ../index.php");
}

function generateRandomHash() {
    $lenghOfString = 16;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($lenghOfString);
    $randomString = '';
    for ($i = 0; $i < $lenghOfString; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return md5($randomString);
}

?>