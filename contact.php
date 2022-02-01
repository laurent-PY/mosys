<?php include 'template/header.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';




function sendMail($nomPrenom, $mailUser, $message)
{
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = 0;                     //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'SSL0.OVH.NET';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'contact@margouli.fr';                     //SMTP username
        $mail->Password   = '%Contact!!%';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS
        $mail->CharSet = 'UTF-8';

        //Recipients
        $mail->setFrom('ne-pas-repondre@margouli.fr', 'Administrateur Margouli.fr');
        $mail->addAddress($mail->Username);     //Add a recipient
        //$mail->addAddress('ellen@example.com');               //Name is optional
        //$mail->addReplyTo('info@example.com', 'Information');
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = '🔔'.' Formulaire de renseignements Mosys' . ' : ' . $nomPrenom;
        $mail->Body    = 'Demande du client : '.$message .'<br />'.' Adresse email du client : '. $mailUser .'<br />'.'Nom et prénom du client : ' . $nomPrenom;
        //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        header('Location:  http://82.66.81.89/mosys/success_mail.php');
        //echo 'Le message a été envoyé';
    } catch (Exception $e) {
        //echo "Le message n'a pas été envoyé. Mailer Erreur: {$mail->ErrorInfo}";
    }
}
if (isset($_POST['envoyer'])) {
    $nomPrenom = htmlspecialchars($_POST['nomPrenom']);
    $mailUser = htmlspecialchars($_POST['mail']);
    $message = htmlspecialchars($_POST['message']);

    if (!empty($_POST['nomPrenom']) && !empty($_POST['mail']) && !empty($_POST['message'])) {

        $nomPrenomLen = strlen($nomPrenom);
        if ($nomPrenomLen <= 50) {
            if (filter_var($mailUser, FILTER_VALIDATE_EMAIL)) {
                sendMail($nomPrenom, $mailUser, $message);
                
            } else {
                $erreur = "Votre adresse email est incorrect";
            }
        } else {
            $erreur = "Votre nom et prénom sont trop long. (100 caractères max)";
        }
    } else {
        $erreur = "Merci de bien renseigner tous les champs de saisie";
    }
}
?>

<section class="banner-contact d-flex justify-content-center align-items-center ">
    <div class="container my-5">
      <div class="col-12 d-flex justify-content-center align-items-center">
        <h1 class="gill bg-dark text-light bg-opacity-75 d-inline" >LET'S GET IN TOUCH</h1>
      </div>
      <!-- Début Formulaire contact -->
    <div class="container">
        <div class="row mt-5 d-flex justify-content-center">
            <div class="col-6 gillP">
            <?php
                    if (isset($erreur)) {
                        echo '<font color="red">' . $erreur . '</font>';
                    }
                ?>
                <form method="POST" class="mb-3">
                    <div class="mb-5">
                        <label for="nomPrenom" class="form-label">Vos nom et prénom</label>
                        <input type="text" class="form-control" name="nomPrenom" value="<?php if (isset($nomPrenom)) {echo $nomPrenom;} ?>">
                    </div>
                    <div class="mb-5">
                        <label for="mail" class="form-label">Votre adresse Email</label>
                        <input type="mail" class="form-control" name="mail" value="<?php if (isset($mailUser)) { echo $mailUser;} ?>">
                    </div>
                        <div class="mb-5">
                        <label for="message" class="form-label">Votre message</label>
                        <input type="text" class="form-control" rows="3" name="message" value="<?php if (isset($message)) { echo $message;} ?>">
                    </div>
                    
                    <button type="submit" class="btn btn-info mb-5" name="envoyer">Envoyer</button>
                </form>
                
            </div>
        </div>
    </div>      
  </section>
<?php include 'template/footer.php'; ?>