<?php

if (!defined('INITIALIZED')) {
    exit;
}

$passwordToMailer = '';
$toMail = 'test@example.com';

if (empty($passwordToMailer)) {
    exit('Edit file pages/testmail.php and set password to access this site.');
}

if (isset($_REQUEST['mailer_password']) && $_REQUEST['mailer_password'] != $passwordToMailer) {
    echo '<div style="color:red;font-size: 2em">Invalid password. You can find it in file pages/testmail.php</div>';
}

if (!isset($_REQUEST['mailer_password']) || $_REQUEST['mailer_password'] != $passwordToMailer) {
    echo '<form action="?subtopic=testmail" method="POST">';
    echo 'Password to mailer: <input type="text" name="mailer_password" /><br/>';
    echo '<input type="submit" value="Send mail" />';
    echo '</form>';
    exit;
}

echo '<div style="color:red;font-size:2em">SENSITIVE INFORMATION BELOW</div>';
echo '<div style="color:red;font-size:1em">Code below contains your login and password to e-mail account. It may be in encoded format, but programmers will read it easily.</div>';
echo '<div style="color:red;font-size:1.5em">Do not share it with untrusted persons. DO NOT POST IT ON FORUM!</div>';
if ($config['site']['send_emails']) {
    $mailBody = '<html>
			<body>
			<h3>Test mail</h3>
			<p>test mail</p>
			</body>
			</html>';

    $mail = new PHPMailer();
    $mail->SMTPDebug = SMTP::DEBUG_LOWLEVEL;

    if ($config['site']['smtp_enabled']) {
        $mail->IsSMTP();
        $mail->Host = $config['site']['smtp_host'];
        $mail->Port = (int)$config['site']['smtp_port'];
        $mail->SMTPAuth = $config['site']['smtp_auth'];
        $mail->Username = $config['site']['smtp_user'];
        $mail->Password = $config['site']['smtp_pass'];
    } else {
        $mail->IsMail();
    }
    $mail->IsHTML(true);
    $mail->From = $config['site']['mail_address'];
    $mail->AddAddress($toMail);
    $mail->Subject = $config['server']['serverName'] . " - test mail";
    $mail->Body = $mailBody;
    if ($mail->Send()) {
        echo '<div style="color:green;font-size:2em">E-mail sent</div>';
    } else {
        echo '<div style="color:red;font-size:2em">E-mail error</div>';
    }
} else {
    echo '<div style="color:red;font-size:2em">"send_emails" is set to "false" in config/config.php</div>';
}
