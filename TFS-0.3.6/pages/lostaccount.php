<?php
if(!defined('INITIALIZED'))
	exit;

if($config['site']['send_emails'])
{
	if($action == '')
	{
		$main_content .= 'The Lost Account Interface can help you to get back your account name and password. Please enter your character name and select what you want to do.<BR>
		<FORM ACTION="?subtopic=lostaccount&action=step1" METHOD=post>
		<INPUT TYPE=hidden NAME="character" VALUE="">
		<TABLE CELLSPACING=1 CELLPADDING=4 BORDER=0 WIDTH=100%>
		<TR><TD BGCOLOR="'.$config['site']['vdarkborder'].'" CLASS=white><B>Please enter your character name</B></TD></TR>
		<TR><TD BGCOLOR="'.$config['site']['darkborder'].'">
		<INPUT TYPE=text NAME="nick" VALUE="" SIZE="40"><BR>
		</TD></TR>
		</TABLE>
		<TABLE CELLSPACING=1 CELLPADDING=4 BORDER=0 WIDTH=100%>
		<TR><TD BGCOLOR="'.$config['site']['vdarkborder'].'" CLASS=white><B>What do you want?</B></TD></TR>
		<TR><TD BGCOLOR="'.$config['site']['darkborder'].'">
		<INPUT TYPE=radio NAME="action_type" VALUE="email"> Send me new password and my account name to account e-mail adress.<BR>
		<INPUT TYPE=radio NAME="action_type" VALUE="reckey"> I got <b>recovery key</b> and want set new password and e-mail adress to my account.<BR>
		</TD></TR>
		</TABLE>
		<BR>
		<TABLE CELLSPACING=0 CELLPADDING=0 BORDER=0 WIDTH=100%><TR><TD><center>
		<INPUT TYPE=image NAME="Submit" ALT="Submit" SRC="'.$layout_name.'/images/buttons/sbutton_submit.gif" BORDER=0 WIDTH=120 HEIGHT=18></center>
		</TD></TR></FORM></TABLE></TABLE>';
	}
	elseif($action == 'step1' && $_REQUEST['action_type'] == '')
		$main_content .= 'Please select action.
		<BR /><TABLE CELLSPACING=0 CELLPADDING=0 BORDER=0 WIDTH=100%><TR><TD><center>
					<a href="?subtopic=lostaccount" border="0"><IMG SRC="'.$layout_name.'/images/buttons/sbutton_back.gif" NAME="Back" ALT="Back" BORDER=0 WIDTH=120 HEIGHT=18></a></center>
					</TD></TR></FORM></TABLE></TABLE>';
	elseif($action == 'step1' && $_REQUEST['action_type'] == 'email')
	{
		$nick = $_REQUEST['nick'];
		if(check_name($nick))
		{
			$player = new Player();
			$account = new Account();
			$player->find($nick);
			if($player->isLoaded())
				$account = $player->getAccount();
			if($account->isLoaded())
			{
				if($account->getCustomField('next_email') < time())
					$main_content .= 'Please enter e-mail to account with this character.<BR>
					<FORM ACTION="?subtopic=lostaccount&action=sendcode" METHOD=post>
					<INPUT TYPE=hidden NAME="character" VALUE="">
					<TABLE CELLSPACING=1 CELLPADDING=4 BORDER=0 WIDTH=100%>
					<TR><TD BGCOLOR="'.$config['site']['vdarkborder'].'" CLASS=white><B>Please enter e-mail to account</B></TD></TR>
					<TR><TD BGCOLOR="'.$config['site']['darkborder'].'">
					Character: <INPUT TYPE=text NAME="nick" VALUE="'.htmlspecialchars($nick).'" SIZE="40" readonly="readonly"><BR>
					E-mail to account:<INPUT TYPE=text NAME="email" VALUE="" SIZE="40"><BR>
					</TD></TR>
					</TABLE>
					<BR>
					<TABLE CELLSPACING=0 CELLPADDING=0 BORDER=0 WIDTH=100%><TR><TD><center>
					<INPUT TYPE=image NAME="Submit" ALT="Submit" SRC="'.$layout_name.'/images/buttons/sbutton_submit.gif" BORDER=0 WIDTH=120 HEIGHT=18></center>
					</TD></TR></FORM></TABLE></TABLE>';
				else
				{
					$insec = $account->getCustomField('next_email') - time();
					$minutesleft = floor($insec / 60);
					$secondsleft = $insec - ($minutesleft * 60);
					$timeleft = $minutesleft.' minutes '.$secondsleft.' seconds';
					$main_content .= 'Account of selected character (<b>'.htmlspecialchars($nick).'</b>) received e-mail in last '.ceil($config['site']['email_lai_sec_interval'] / 60).' minutes. You must wait '.$timeleft.' before you can use Lost Account Interface again.';
				}
			}
			else
				$main_content .= 'Player or account of player <b>'.htmlspecialchars($nick).'</b> doesn\'t exist.';
		}
		else
			$main_content .= 'Invalid player name format. If you have other characters on account try with other name.';
		$main_content .= '<BR /><TABLE CELLSPACING=0 CELLPADDING=0 BORDER=0 WIDTH=100%><TR><TD><center>
					<a href="?subtopic=lostaccount" border="0"><IMG SRC="'.$layout_name.'/images/buttons/sbutton_back.gif" NAME="Back" ALT="Back" BORDER=0 WIDTH=120 HEIGHT=18></a></center>
					</TD></TR></FORM></TABLE></TABLE>';
	}
	elseif($action == 'sendcode')
	{
		$email = $_REQUEST['email'];
		$nick = $_REQUEST['nick'];
		if(check_name($nick))
		{
			$player = new Player();
			$account = new Account();
			$player->find($nick);
			if($player->isLoaded())
				$account = $player->getAccount();
			if($account->isLoaded())
			{
				if($account->getCustomField('next_email') < time())
				{
					if($account->getEMail() == $email)
					{
						$acceptedChars = '123456789zxcvbnmasdfghjklqwertyuiop';
						$newcode = NULL;
						for($i=0; $i < 30; $i++) {
							$cnum[$i] = $acceptedChars{mt_rand(0, 33)};
							$newcode .= $cnum[$i];
						}
						$mailBody = '<html>
						<body>
						<h3>Your account name and password!</h3>
						<p>You or someone else requested new password for  your account on server <a href="'.$config['server']['url'].'"><b>'.htmlspecialchars($config['server']['serverName']).'</b></a> with this e-mail.</p>
						<p>Account name: '.htmlspecialchars($account->getName()).'</p>
						<p>Password: <i>You will set new password when you press on link.</i></p>
						<br />
						<p>Press on link to set new password. This link will work until next >new password request< in Lost Account Interface.</p>
						<p><a href="'.$config['server']['url'].'/?subtopic=lostaccount&action=checkcode&code='.urlencode($newcode).'&character='.urlencode($nick).'">'.$config['server']['url'].'/?subtopic=lostaccount&action=checkcode&code='.urlencode($newcode).'&character='.urlencode($nick).'</a></p>
						<p>or open page: <i>'.$config['server']['url'].'/?subtopic=lostaccount&action=checkcode</i> and in field "code" write <b>'.htmlspecialchars($newcode).'</b></p>
						<br /><p>If you don\'t want to change password to your account just delete this e-mail.
						<p><u>It\'s automatic e-mail from OTS Lost Account System. Do not reply!</u></p>
						</body>
						</html>';
						$mail = new PHPMailer();
						if ($config['site']['smtp_enabled'])
						{
							$mail->IsSMTP();
							$mail->Host = $config['site']['smtp_host'];
							$mail->Port = (int)$config['site']['smtp_port'];
							$mail->SMTPAuth = $config['site']['smtp_auth'];
							$mail->Username = $config['site']['smtp_user'];
							$mail->Password = $config['site']['smtp_pass'];
						}
						else
							$mail->IsMail();
						$mail->IsHTML(true);
						$mail->From = $config['site']['mail_address'];
						$mail->AddAddress($account->getCustomField('email'));
						$mail->Subject = $config['server']['serverName']." - Link to >set new password to account<";
						$mail->Body = $mailBody;
						if($mail->Send())
						{
							$account->set('email_code', $newcode);
							$account->set('next_email', (time() + $config['site']['email_lai_sec_interval']));
							$account->save();
							$main_content .= '<br />Link with informations needed to set new password has been sent to account e-mail address. You should receive this e-mail in 15 minutes. Please check your inbox/spam directory.';
						}
						else
						{
							$account->set('next_email', (time() + 60));
							$account->save();
							$main_content .= '<br />An error occorred while sending email! Try again or contact with admin.';
						}
					}
					else
						$main_content .= 'Invalid e-mail to account of character <b>'.htmlspecialchars($nick).'</b>. Try again.';
				}
				else
				{
					$insec = $account->getCustomField('next_email') - time();
					$minutesleft = floor($insec / 60);
					$secondsleft = $insec - ($minutesleft * 60);
					$timeleft = $minutesleft.' minutes '.$secondsleft.' seconds';
					$main_content .= 'Account of selected character (<b>'.htmlspecialchars($nick).'</b>) received e-mail in last '.ceil($config['site']['email_lai_sec_interval'] / 60).' minutes. You must wait '.$timeleft.' before you can use Lost Account Interface again.';
				}
			}
			else
				$main_content .= 'Player or account of player <b>'.htmlspecialchars($nick).'</b> doesn\'t exist.';
		}
		else
			$main_content .= 'Invalid player name format. If you have other characters on account try with other name.';
		$main_content .= '<BR /><TABLE CELLSPACING=0 CELLPADDING=0 BORDER=0 WIDTH=100%><TR><TD><center>
					<a href="?subtopic=lostaccount&action=step1&action_type=email&nick='.urlencode($nick).'" border="0"><IMG SRC="'.$layout_name.'/images/buttons/sbutton_back.gif" NAME="Back" ALT="Back" BORDER=0 WIDTH=120 HEIGHT=18></a></center>
					</TD></TR></FORM></TABLE></TABLE>';
	}
	elseif($action == 'step1' && $_REQUEST['action_type'] == 'reckey')
	{
		$nick = $_REQUEST['nick'];
		if(check_name($nick))
		{
			$player = new Player();
			$account = new Account();
			$player->find($nick);
			if($player->isLoaded())
				$account = $player->getAccount();
			if($account->isLoaded())
			{
				$account_key = $account->getCustomField('key');
				if(!empty($account_key))
				{
							$main_content .= 'If you enter right recovery key you will see form to set new e-mail and password to account. To this e-mail will be send your new password and account name.<BR>
							<FORM ACTION="?subtopic=lostaccount&action=step2" METHOD=post>
							<TABLE CELLSPACING=1 CELLPADDING=4 BORDER=0 WIDTH=100%>
							<TR><TD BGCOLOR="'.$config['site']['vdarkborder'].'" CLASS=white><B>Please enter your recovery key</B></TD></TR>
							<TR><TD BGCOLOR="'.$config['site']['darkborder'].'">
							Character name:&nbsp;<INPUT TYPE=text NAME="nick" VALUE="'.htmlspecialchars($nick).'" SIZE="40" readonly="readonly"><BR />
							Recovery key:&nbsp;&nbsp;&nbsp;&nbsp;<INPUT TYPE=text NAME="key" VALUE="" SIZE="40"><BR>
							</TD></TR>
							</TABLE>
							<BR>
							<TABLE CELLSPACING=0 CELLPADDING=0 BORDER=0 WIDTH=100%><TR><TD><center>
							<INPUT TYPE=image NAME="Submit" ALT="Submit" SRC="'.$layout_name.'/images/buttons/sbutton_submit.gif" BORDER=0 WIDTH=120 HEIGHT=18></center>
							</TD></TR></FORM></TABLE></TABLE>';
				}
				else
					$main_content .= 'Account of this character has no recovery key!';
			}
			else
				$main_content .= 'Player or account of player <b>'.htmlspecialchars($nick).'</b> doesn\'t exist.';
		}
		else
			$main_content .= 'Invalid player name format. If you have other characters on account try with other name.';
		$main_content .= '<BR /><TABLE CELLSPACING=0 CELLPADDING=0 BORDER=0 WIDTH=100%><TR><TD><center>
					<a href="?subtopic=lostaccount" border="0"><IMG SRC="'.$layout_name.'/images/buttons/sbutton_back.gif" NAME="Back" ALT="Back" BORDER=0 WIDTH=120 HEIGHT=18></a></center>
					</TD></TR></FORM></TABLE></TABLE>';
	}
	elseif($action == 'step2')
	{
		$rec_key = trim($_REQUEST['key']);
		$nick = $_REQUEST['nick'];
		if(check_name($nick))
		{
			$player = new Player();
			$account = new Account();
			$player->find($nick);
			if($player->isLoaded())
				$account = $player->getAccount();
			if($account->isLoaded())
			{
				$account_key = $account->getCustomField('key');
				if(!empty($account_key))
				{
					if($account_key == $rec_key)
					{
						$main_content .= '<script type="text/javascript">
						function validate_required(field,alerttxt)
						{
						with (field)
						{
						if (value==null||value==""||value==" ")
						  {alert(alerttxt);return false;}
						else {return true}
						}
						}
						function validate_email(field,alerttxt)
						{
						with (field)
						{
						apos=value.indexOf("@");
						dotpos=value.lastIndexOf(".");
						if (apos<1||dotpos-apos<2) 
						  {alert(alerttxt);return false;}
						else {return true;}
						}
						}
						function validate_form(thisform)
						{
						with (thisform)
						{
						if (validate_required(email,"Please enter your e-mail!")==false)
						  {email.focus();return false;}
						if (validate_email(email,"Invalid e-mail format!")==false)
						  {email.focus();return false;}
						if (validate_required(passor,"Please enter password!")==false)
						  {passor.focus();return false;}
						if (validate_required(passor2,"Please repeat password!")==false)
						  {passor2.focus();return false;}
						if (passor2.value!=passor.value)
						  {alert(\'Repeated password is not equal to password!\');return false;}
						}
						}
						</script>';
						$main_content .= 'Set new password and e-mail to your account.<BR>
						<FORM ACTION="?subtopic=lostaccount&action=step3" onsubmit="return validate_form(this)" METHOD=post>
						<INPUT TYPE=hidden NAME="character" VALUE="">
						<TABLE CELLSPACING=1 CELLPADDING=4 BORDER=0 WIDTH=100%>
						<TR><TD BGCOLOR="'.$config['site']['vdarkborder'].'" CLASS=white><B>Please enter new password and e-mail</B></TD></TR>
						<TR><TD BGCOLOR="'.$config['site']['darkborder'].'">
						Account of character:&nbsp;&nbsp;<INPUT TYPE=text NAME="nick" VALUE="'.htmlspecialchars($nick).'" SIZE="40" readonly="readonly"><BR />
						New password:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<INPUT id="passor" TYPE=password NAME="passor" VALUE="" SIZE="40"><BR>
						Repeat new password:&nbsp;&nbsp;<INPUT id="passor2" TYPE=password NAME="passor" VALUE="" SIZE="40"><BR>
						New e-mail address:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<INPUT id="email" TYPE=text NAME="email" VALUE="" SIZE="40"><BR>
						<INPUT TYPE=hidden NAME="key" VALUE="'.htmlspecialchars($rec_key).'">
						</TD></TR>
						</TABLE>
						<BR>
						<TABLE CELLSPACING=0 CELLPADDING=0 BORDER=0 WIDTH=100%><TR><TD><center>
						<INPUT TYPE=image NAME="Submit" ALT="Submit" SRC="'.$layout_name.'/images/buttons/sbutton_submit.gif" BORDER=0 WIDTH=120 HEIGHT=18></center>
						</TD></TR></FORM></TABLE></TABLE>';
					}
					else
						$main_content .= 'Wrong recovery key!';
				}
				else
					$main_content .= 'Account of this character has no recovery key!';
			}
			else
				$main_content .= 'Player or account of player <b>'.htmlspecialchars($nick).'</b> doesn\'t exist.';
		}
		else
			$main_content .= 'Invalid player name format. If you have other characters on account try with other name.';
		$main_content .= '<BR /><TABLE CELLSPACING=0 CELLPADDING=0 BORDER=0 WIDTH=100%><TR><TD><center>
					<a href="?subtopic=lostaccount&action=step1&action_type=reckey&nick='.urlencode($nick).'" border="0"><IMG SRC="'.$layout_name.'/images/buttons/sbutton_back.gif" NAME="Back" ALT="Back" BORDER=0 WIDTH=120 HEIGHT=18></a></center>
					</TD></TR></FORM></TABLE></TABLE>';
	}
	elseif($action == 'step3')
	{
		$rec_key = trim($_REQUEST['key']);
		$nick = $_REQUEST['nick'];
		$new_pass = trim($_REQUEST['passor']);
		$new_email = trim($_REQUEST['email']);
		if(check_name($nick))
		{
			$player = new Player();
			$account = new Account();
			$player->find($nick);
			if($player->isLoaded())
				$account = $player->getAccount();
			if($account->isLoaded())
			{
				$account_key = $account->getCustomField('key');
				if(!empty($account_key))
				{
					if($account_key == $rec_key)
					{
						if(check_password($new_pass))
						{
							if(check_mail($new_email))
							{
								$account->setEMail($new_email);
								$account->setPassword($new_pass);
								$account->save();
								$main_content .= 'Your account name, new password and new e-mail.<BR>
								<FORM ACTION="?subtopic=accountmanagement" onsubmit="return validate_form(this)" METHOD=post>
								<INPUT TYPE=hidden NAME="character" VALUE="">
								<TABLE CELLSPACING=1 CELLPADDING=4 BORDER=0 WIDTH=100%>
								<TR><TD BGCOLOR="'.$config['site']['vdarkborder'].'" CLASS=white><B>Your account name, new password and new e-mail</B></TD></TR>
								<TR><TD BGCOLOR="'.$config['site']['darkborder'].'">
								Account name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>'.htmlspecialchars($account->getName()).'</b><BR>
								New password:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>'.htmlspecialchars($new_pass).'</b><BR>
								New e-mail address:&nbsp;<b>'.htmlspecialchars($new_email).'</b><BR>';
								if($account->getCustomField('next_email') < time())
								{
									$mailBody = '<html>
									<body>
									<h3>Your account name and new password!</h3>
									<p>Changed password and e-mail to your account in Lost Account Interface on server <a href="'.$config['server']['url'].'"><b>'.$config['server']['serverName'].'</b></a></p>
									<p>Account name: <b>'.htmlspecialchars($account->getName()).'</b></p>
									<p>New password: <b>'.htmlspecialchars($new_pass).'</b></p>
									<p>E-mail: <b>'.htmlspecialchars($new_email).'</b> (this e-mail)</p>
									<br />
									<p><u>It\'s automatic e-mail from OTS Lost Account System. Do not reply!</u></p>
									</body>
									</html>';
									$mail = new PHPMailer();
									if ($config['site']['smtp_enabled'])
									{
										$mail->IsSMTP();
										$mail->Host = $config['site']['smtp_host'];
										$mail->Port = (int)$config['site']['smtp_port'];
										$mail->SMTPAuth = $config['site']['smtp_auth'];
										$mail->Username = $config['site']['smtp_user'];
										$mail->Password = $config['site']['smtp_pass'];
									}
									else
										$mail->IsMail();
									$mail->IsHTML(true);
									$mail->From = $config['site']['mail_address'];
									$mail->AddAddress($account->getCustomField('email'));
									$mail->Subject = $config['server']['serverName']." - New password to your account";
									$mail->Body = $mailBody;
									if($mail->Send())
									{
										$main_content .= '<br /><small>Sent e-mail with your account name and password to new e-mail. You should receive this e-mail in 15 minutes. You can login now with new password!';
									}
									else
									{
										$main_content .= '<br /><small>An error occorred while sending email! You will not receive e-mail with this informations.';
									}
								}
								else
								{
									$main_content .= '<br /><small>You will not receive e-mail with this informations.';
								}
								$main_content .= '<INPUT TYPE=hidden NAME="account_login" VALUE="'.$account->getId().'">
								<INPUT TYPE=hidden NAME="password_login" VALUE="'.htmlspecialchars($new_pass).'">
								</TD></TR></TABLE><BR>
								<TABLE CELLSPACING=0 CELLPADDING=0 BORDER=0 WIDTH=100%><TR><TD><center>
								<INPUT TYPE=image NAME="Login" ALT="Login" SRC="'.$layout_name.'/images/buttons/sbutton_login.gif" BORDER=0 WIDTH=120 HEIGHT=18></center>
								</TD></TR></FORM></TABLE></TABLE>';
							}
							else
								$main_content .= 'Wrong e-mail format.';
						}
						else
							$main_content .= 'Wrong password format. Use only a-Z, A-Z, 0-9';
					}
					else
						$main_content .= 'Wrong recovery key!';
				}
				else
					$main_content .= 'Account of this character has no recovery key!';
			}
			else
				$main_content .= 'Player or account of player <b>'.htmlspecialchars($nick).'</b> doesn\'t exist.';
		}
		else
			$main_content .= 'Invalid player name format. If you have other characters on account try with other name.';
		$main_content .= '<BR /><TABLE CELLSPACING=0 CELLPADDING=0 BORDER=0 WIDTH=100%><TR><TD><center>
					<a href="?subtopic=lostaccount&action=step1&action_type=reckey&nick='.urlencode($nick).'" border="0"><IMG SRC="'.$layout_name.'/images/buttons/sbutton_back.gif" NAME="Back" ALT="Back" BORDER=0 WIDTH=120 HEIGHT=18></a></center>
					</TD></TR></FORM></TABLE></TABLE>';
	}
	elseif($action == 'checkcode')
	{
		$code = trim($_REQUEST['code']);
		$character = trim($_REQUEST['character']);
		if(empty($code) || empty($character))
			$main_content .= 'Please enter code from e-mail and name of one character from account. Then press Submit.<BR>
					<FORM ACTION="?subtopic=lostaccount&action=checkcode" METHOD=post>
					<TABLE CELLSPACING=1 CELLPADDING=4 BORDER=0 WIDTH=100%>
					<TR><TD BGCOLOR="'.$config['site']['vdarkborder'].'" CLASS=white><B>Code & character name</B></TD></TR>
					<TR><TD BGCOLOR="'.$config['site']['darkborder'].'">
					Your code:&nbsp;<INPUT TYPE=text NAME="code" VALUE="" SIZE="40")><BR />
					Character:&nbsp;<INPUT TYPE=text NAME="character" VALUE="" SIZE="40")><BR />
					</TD></TR>
					</TABLE>
					<BR>
					<TABLE CELLSPACING=0 CELLPADDING=0 BORDER=0 WIDTH=100%><TR><TD><center>
					<INPUT TYPE=image NAME="Submit" ALT="Submit" SRC="'.$layout_name.'/images/buttons/sbutton_submit.gif" BORDER=0 WIDTH=120 HEIGHT=18></center>
					</TD></TR></FORM></TABLE></TABLE>';
		else
		{
			$player = new Player();
			$account = new Account();
			$player->find($character);
			if($player->isLoaded())
				$account = $player->getAccount();
			if($account->isLoaded())
			{
				if($account->getCustomField('email_code') == $code)
				{
					$main_content .= '<script type="text/javascript">
					function validate_required(field,alerttxt)
					{
					with (field)
					{
					if (value==null||value==""||value==" ")
					  {alert(alerttxt);return false;}
					else {return true}
					}
					}

					function validate_form(thisform)
					{
					with (thisform)
					{
					if (validate_required(passor,"Please enter password!")==false)
					  {passor.focus();return false;}
					if (validate_required(passor2,"Please repeat password!")==false)
					  {passor2.focus();return false;}
					if (passor2.value!=passor.value)
					  {alert(\'Repeated password is not equal to password!\');return false;}
					}
					}
					</script>
					Please enter new password to your account and repeat to make sure you remember password.<BR>
					<FORM ACTION="?subtopic=lostaccount&action=setnewpassword" onsubmit="return validate_form(this)" METHOD=post>
					<INPUT TYPE=hidden NAME="character" VALUE="'.htmlspecialchars($character).'">
					<INPUT TYPE=hidden NAME="code" VALUE="'.htmlspecialchars($code).'">
					<TABLE CELLSPACING=1 CELLPADDING=4 BORDER=0 WIDTH=100%>
					<TR><TD BGCOLOR="'.$config['site']['vdarkborder'].'" CLASS=white><B>Code & account name</B></TD></TR>
					<TR><TD BGCOLOR="'.$config['site']['darkborder'].'">
					New password:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<INPUT TYPE=password ID="passor" NAME="passor" VALUE="" SIZE="40")><BR />
					Repeat new password:&nbsp;<INPUT TYPE=password ID="passor2" NAME="passor2" VALUE="" SIZE="40")><BR />
					</TD></TR>
					</TABLE>
					<BR>
					<TABLE CELLSPACING=0 CELLPADDING=0 BORDER=0 WIDTH=100%><TR><TD><center>
					<INPUT TYPE=image NAME="Submit" ALT="Submit" SRC="'.$layout_name.'/images/buttons/sbutton_submit.gif" BORDER=0 WIDTH=120 HEIGHT=18></center>
					</TD></TR></FORM></TABLE></TABLE>';
				}
				else
					$error= 'Wrong code to change password.';
			}
			else
				$error = 'Account of this character or this character doesn\'t exist.';
		}
		if(!empty($error))
					$main_content .= '<font color="red"><b>'.$error.'</b></font><br />Please enter code from e-mail and name of one character from account. Then press Submit.<BR>
					<FORM ACTION="?subtopic=lostaccount&action=checkcode" METHOD=post>
					<TABLE CELLSPACING=1 CELLPADDING=4 BORDER=0 WIDTH=100%>
					<TR><TD BGCOLOR="'.$config['site']['vdarkborder'].'" CLASS=white><B>Code & character name</B></TD></TR>
					<TR><TD BGCOLOR="'.$config['site']['darkborder'].'">
					Your code:&nbsp;<INPUT TYPE=text NAME="code" VALUE="" SIZE="40")><BR />
					Character:&nbsp;<INPUT TYPE=text NAME="character" VALUE="" SIZE="40")><BR />
					</TD></TR>
					</TABLE>
					<BR>
					<TABLE CELLSPACING=0 CELLPADDING=0 BORDER=0 WIDTH=100%><TR><TD><center>
					<INPUT TYPE=image NAME="Submit" ALT="Submit" SRC="'.$layout_name.'/images/buttons/sbutton_submit.gif" BORDER=0 WIDTH=120 HEIGHT=18></center>
					</TD></TR></FORM></TABLE></TABLE>';
	}
	elseif($action == 'setnewpassword')
	{
		$newpassword = $_REQUEST['passor'];
		$code = $_REQUEST['code'];
		$character = $_REQUEST['character'];
		$main_content .= '';
		if(empty($code) || empty($character) || empty($newpassword))
			$main_content .= '<font color="red"><b>Error. Try again.</b></font><br />Please enter code from e-mail and name of one character from account. Then press Submit.<BR>
					<BR><FORM ACTION="?subtopic=lostaccount&action=checkcode" METHOD=post>
					<TABLE CELLSPACING=0 CELLPADDING=0 BORDER=0 WIDTH=100%><TR><TD><center>
					<INPUT TYPE=image NAME="Back" ALT="Back" SRC="'.$layout_name.'/images/buttons/sbutton_back.gif" BORDER=0 WIDTH=120 HEIGHT=18></center>
					</TD></TR></FORM></TABLE></TABLE>';
		else
		{
			$player = new Player();
			$account = new Account();
			$player->find($character);
			if($player->isLoaded())
				$account = $player->getAccount();
			if($account->isLoaded())
			{
				if($account->getCustomField('email_code') == $code)
				{
					if(check_password($newpassword))
					{
						$account->setPassword($newpassword);
						$account->set('email_code', '');
						$account->save();
						$main_content .= 'New password to your account is below. Now you can login.<BR>
						<INPUT TYPE="hidden" NAME="character" VALUE="'.htmlspecialchars($character).'">
						<TABLE CELLSPACING=1 CELLPADDING=4 BORDER=0 WIDTH=100%>
						<TR><TD BGCOLOR="'.$config['site']['vdarkborder'].'" CLASS=white><B>Changed password</B></TD></TR>
						<TR><TD BGCOLOR="'.$config['site']['darkborder'].'">
						New password:&nbsp;<b>'.htmlspecialchars($newpassword).'</b><BR />
						Account name:&nbsp;&nbsp;&nbsp;<i>(Already on your e-mail)</i><BR />';
						$mailBody = '<html>
						<body>
						<h3>Your account name and password!</h3>
						<p>Changed password to your account in Lost Account Interface on server <a href="'.$config['server']['url'].'"><b>'.htmlspecialchars($config['server']['serverName']).'</b></a></p>
						<p>Account name: <b>'.htmlspecialchars($account->getName()).'</b></p>
						<p>New password: <b>'.htmlspecialchars($newpassword).'</b></p>
						<br />
						<p><u>It\'s automatic e-mail from OTS Lost Account System. Do not reply!</u></p>
						</body>
						</html>';
						$mail = new PHPMailer();
						if ($config['site']['smtp_enabled'])
						{
							$mail->IsSMTP();
							$mail->Host = $config['site']['smtp_host'];
							$mail->Port = (int)$config['site']['smtp_port'];
							$mail->SMTPAuth = $config['site']['smtp_auth'];
							$mail->Username = $config['site']['smtp_user'];
							$mail->Password = $config['site']['smtp_pass'];

						}
						else
							$mail->IsMail();
						$mail->IsHTML(true);
						$mail->From = $config['site']['mail_address'];
						$mail->AddAddress($account->getCustomField('email'));
						$mail->Subject = $config['server']['serverName']." - New password to your account";
						$mail->Body = $mailBody;
						if($mail->Send())
						{
							$main_content .= '<br /><small>New password work! Sent e-mail with your password and account name. You should receive this e-mail in 15 minutes. You can login now with new password!';
						}
						else
						{
							$main_content .= '<br /><small>New password work! An error occorred while sending email! You will not receive e-mail with new password.';
						}
					$main_content .= '</TD></TR>
					</TABLE>
					<BR>
					<TABLE CELLSPACING=0 CELLPADDING=0 BORDER=0 WIDTH=100%><TR><TD><center>
					<FORM ACTION="?subtopic=accountmanagement" METHOD=post>
					<INPUT TYPE=image NAME="Login" ALT="Login" SRC="'.$layout_name.'/images/buttons/sbutton_login.gif" BORDER=0 WIDTH=120 HEIGHT=18></center>
					</TD></TR></FORM></TABLE></TABLE>';
					}
					else
						$error= 'Wrong password format. Use only a-z, A-Z, 0-9.';
				}
				else
					$error= 'Wrong code to change password.';
			}
			else
				$error = 'Account of this character or this character doesn\'t exist.';
		}
		if(!empty($error))
					$main_content .= '<font color="red"><b>'.$error.'</b></font><br />Please enter code from e-mail and name of one character from account. Then press Submit.<BR>
					<FORM ACTION="?subtopic=lostaccount&action=checkcode" METHOD=post>
					<TABLE CELLSPACING=1 CELLPADDING=4 BORDER=0 WIDTH=100%>
					<TR><TD BGCOLOR="'.$config['site']['vdarkborder'].'" CLASS=white><B>Code & character name</B></TD></TR>
					<TR><TD BGCOLOR="'.$config['site']['darkborder'].'">
					Your code:&nbsp;<INPUT TYPE=text NAME="code" VALUE="" SIZE="40")><BR />
					Character:&nbsp;<INPUT TYPE=text NAME="character" VALUE="" SIZE="40")><BR />
					</TD></TR>
					</TABLE>
					<BR>
					<TABLE CELLSPACING=0 CELLPADDING=0 BORDER=0 WIDTH=100%><TR><TD><center>
					<INPUT TYPE=image NAME="Submit" ALT="Submit" SRC="'.$layout_name.'/images/buttons/sbutton_submit.gif" BORDER=0 WIDTH=120 HEIGHT=18></center>
					</TD></TR></FORM></TABLE></TABLE>';
	}
}
else
	$main_content .= '<b>Account maker is not configured to send e-mails, you can\'t use Lost Account Interface. Contact with admin to get help.</b>';