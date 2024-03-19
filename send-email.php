<?php
// Constantes pour les informations sensibles
define('SMTP_HOST', 'ssl0.ovh.net');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'marco-contact@marco-folio.fr');
define('SMTP_PASSWORD', 'mm09102000');
define('DESTINATION_EMAIL', 'marco.m2011@hotmail.fr');

// Fonction pour valider et nettoyer les données
function sanitizeData($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES);
}

// Vérifier les limites de caractères
function validateCharacterLimit($data, $maxLimit) {
    return mb_strlen($data, 'utf-8') <= $maxLimit;
}

$name = sanitizeData($_POST["name"]);
$email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
$objet = sanitizeData($_POST["objet"]);
$message = sanitizeData($_POST["message"]);

// Définir les limites de caractères pour chaque champ
$maxNameLimit = 16;
$maxObjetLimit = 64;
$maxMessageLimit = 540;

// Validation des champs obligatoires
if (empty($name) || empty($email) || empty($objet) || empty($message)) {
    echo "Veuillez remplir tous les champs du formulaire.";
    exit;
}

// Validation des limites de caractères
if (!validateCharacterLimit($name, $maxNameLimit) || !validateCharacterLimit($objet, $maxObjetLimit) || !validateCharacterLimit($message, $maxMessageLimit)) {
    echo "Les limites de caractères sont dépassées pour certains champs. (Vérifiez votre Nom, votre adresse mail, la limite de caractère du message est également de 540 caractères)";
    exit;
}

// Validation de l'adresse e-mail
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "L'adresse e-mail n'est pas valide.";
    exit;
}

require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

try {
    $mail = new PHPMailer(true);
    $mail->SMTPAuth = true;
    $mail->Host = SMTP_HOST;
    $mail->SMTPSecure = 'tls';
    $mail->Port = SMTP_PORT;
    $mail->Username = SMTP_USERNAME;
    $mail->Password = SMTP_PASSWORD;
    $mail->setFrom($email, $name);
    $mail->addAddress(DESTINATION_EMAIL);
    $mail->Subject = $objet;
    $mail->Body = $message;

    $mail->send();

    header("Location: message.html");
    exit;
} catch (Exception $e) {
    echo 'Une erreur est survenue lors de l\'envoi du message. Veuillez réessayer plus tard : ' . $e->getMessage();
}
?>
