<?php
    use PHPMailer\PHPMailer\Exception;

    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';

    use PHPMailer\PHPMailer\PHPMailer;

    if (isset($_POST['name']) && 
        isset($_POST['email']) && 
        isset($_POST['phone']) && 
        isset($_POST['message'])) {

        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $message = $_POST['message'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid Email Format";
            header("Location: index.php?mailsend=invalidemail");
            exit();
        }

        if (empty($name) || empty($email) || empty($phone) || empty($message)) {
            echo "All fields are required";
            header("Location: index.php?mailsend=emptyfields");
            exit();
        }

        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                       //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'triangleceo2023@gmail.com';            //SMTP username
            $mail->Password   = 'jttxugjwhlohxlvz';                     //app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit SSL encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 465 for SSL

            //Recipients
            $mail->setFrom($email, $name);
            $mail->addAddress('triangleceo2023@gmail.com'); // Add a recipient

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'New Contact Form Submission';
            $mail->Body    = "
                            <h1>New Contact Form Submission</h1>
                            <p><strong>Name:</strong> $name</p>
                            <p><strong>Email:</strong> $email</p>
                            <p><strong>Phone:</strong> $phone</p>
                            <p><strong>Message:</strong> $message</p>";
            $mail->AltBody = "Name: $name\nEmail: $email\nPhone: $phone\nMessage: $message";

            $mail->send();
            $message = "Message has been sent";
            header("Location: index.php?mailsend=success");
        } catch (Exception $e) {
            $email = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
?>