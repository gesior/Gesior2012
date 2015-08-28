<?php
if(!defined('INITIALIZED'))
	exit;

//CREATE ACCOUNT FORM PAGE
if($action == "")
{
	$main_content .= '<script type="text/javascript">

var accountHttp;

function checkAccount()
{
	if(document.getElementById("account_name").value=="")
	{
		document.getElementById("acc_name_check").innerHTML = \'<b><font color="red">Please enter account name.</font></b>\';
		return;
	}
	accountHttp=GetXmlHttpObject();
	if (accountHttp==null)
	{
		return;
	}
	var account = document.getElementById("account_name").value;
	var url="?subtopic=ajax_check_account&account=" + account + "&uid="+Math.random();
	accountHttp.onreadystatechange=AccountStateChanged;
	accountHttp.open("GET",url,true);
	accountHttp.send(null);
} 

function AccountStateChanged() 
{ 
	if (accountHttp.readyState==4)
	{ 
		document.getElementById("acc_name_check").innerHTML=accountHttp.responseText;
	}
}

var emailHttp;

//sprawdza czy dane konto istnieje czy nie
function checkEmail()
{
	if(document.getElementById("email").value=="")
	{
		document.getElementById("email_check").innerHTML = \'<b><font color="red">Please enter e-mail.</font></b>\';
		return;
	}
	emailHttp=GetXmlHttpObject();
	if (emailHttp==null)
	{
		return;
	}
	var email = document.getElementById("email").value;
	var url="?subtopic=ajax_check_email&email=" + email + "&uid="+Math.random();
	emailHttp.onreadystatechange=EmailStateChanged;
	emailHttp.open("GET",url,true);
	emailHttp.send(null);
} 

function EmailStateChanged() 
{
	if (emailHttp.readyState==4)
	{
		document.getElementById("email_check").innerHTML=emailHttp.responseText;
	}
}

	function validate_required(field,alerttxt)
	{
		with (field)
			{
			if (value==null||value==""||value==" ")
			{
				alert(alerttxt);
				return false;
			}
			else
			{
				return true;
			}
		}
	}

	function validate_email(field,alerttxt)
	{
		with (field)
		{
			apos=value.indexOf("@");
			dotpos=value.lastIndexOf(".");
			if (apos<1||dotpos-apos<2) 
			{
				alert(alerttxt);
				return false;
			}
			else
			{
				return true;
			}
		}
	}

	function validate_form(thisform)
	{
		with (thisform)
		{
			if(validate_required(account_name,"Please enter name of new account!")==false)
			{
				account_name.focus();
				return false;
			}
			if(validate_required(email,"Please enter your e-mail!")==false)
			{
				email.focus();
				return false;
			}
			if(validate_email(email,"Invalid e-mail format!")==false)
			{
				email.focus();
				return false;
			}
			if(verifpass==1)
			{
				if(validate_required(passor,"Please enter password!")==false)
				{
					passor.focus();
					return false;
				}
				if (validate_required(passor2,"Please repeat password!")==false)
				{
					passor2.focus();
					return false;
				}
				if(passor2.value!=passor.value)
				{
					alert(\'Repeated password is not equal to password!\');
					return false;
				}
			}
			if(verifya==1)
			{
				if (validate_required(verify,"Please enter verification code!")==false)
				{
					verify.focus();return false;
				}
			}
			if(rules.checked==false)
			{
				alert(\'To create account you must accept server rules!\');
				return false;
			}
		}
	}
	</script>';
	$main_content .= 'To play on '.htmlspecialchars($config['server']['serverName']).' you need an account. 
						All you have to do to create your new account is to enter your email address, password to new account, verification code from picture and to agree to the terms presented below. 
						If you have done so, your account name, password and e-mail address will be shown on the following page and your account and password will be sent 
						to your email address along with further instructions.<BR><BR>
						<FORM ACTION="?subtopic=createaccount&action=saveaccount" onsubmit="return validate_form(this)" METHOD=post>
						<TABLE WIDTH=100% BORDER=0 CELLSPACING=1 CELLPADDING=4>
						<TR><TD BGCOLOR="'.$config['site']['vdarkborder'].'" CLASS=white><B>Create an '.htmlspecialchars($config['server']['serverName']).' Account</B></TD></TR>
						<TR><TD BGCOLOR="'.$config['site']['darkborder'].'"><TABLE BORDER=0 CELLSPACING=8 CELLPADDING=0>
						  <TR><TD>
						    <TABLE BORDER=0 CELLSPACING=5 CELLPADDING=0>';
	$main_content .= '<TR><TD width="150" valign="top"><B>Account name: </B></TD><TD colspan="2"><INPUT id="account_name" NAME="reg_name" onkeyup="checkAccount();" VALUE="" SIZE=30 MAXLENGTH=30><BR><font size="1" face="verdana,arial,helvetica">(Please enter your new account name)</font></TD></TR>
					  <TR><TD width="150"><b>Name status:</b></TD><TD colspan="2"><b><div id="acc_name_check">Please enter your account name.</div></b></TD></TR>
					<TR><TD width="150" valign="top"><B>Email address: </B></TD><TD colspan="2"><INPUT id="email" NAME="reg_email" onkeyup="checkEmail();" VALUE="" SIZE=30 MAXLENGTH=50><BR><font size="1" face="verdana,arial,helvetica">(Your email address is required to recovery an '.htmlspecialchars($config['server']['serverName']).' account)</font></TD></TR>
					  <TR><TD width="150"><b>Email status:</b></TD><TD colspan="2"><b><div id="email_check">Please enter your e-mail.</div></b></TD></TR>';
	if(!$config['site']['create_account_verify_mail'])
	$main_content .= '<script type="text/javascript">var verifpass=1;</script>
						<TR><TD width="150" valign="top"><B>Password: </B></TD><TD colspan="2"><INPUT TYPE="password" id="passor" NAME="reg_password" VALUE="" SIZE=30 MAXLENGTH=29><BR><font size="1" face="verdana,arial,helvetica">(Here write your password to new account on '.htmlspecialchars($config['server']['serverName']).')</font></TD></TR>
					  <TR><TD width="150" valign="top"><B>Repeat password: </B></TD><TD colspan="2"><INPUT TYPE="password" id="passor2" NAME="reg_password2" VALUE="" SIZE=30 MAXLENGTH=29><BR><font size="1" face="verdana,arial,helvetica">(Repeat your password)</font></TD></TR>';
	else
		$main_content .= '<script type="text/javascript">var verifpass=0;</script>';
	if ($config['site']['select_flag']) {
		// Country list
		$main_content .= '<tr><td width="150" valign="top"><b>Country: </b></td><td colspan="2"><select name="reg_country" class="w230">
	                    <option value="unknown">Please choose your country</option>
	                    <option value="af">Afghanistan</option><option value="al">Albania</option><option value="dz">Algeria</option><option value="ad">Andorra</option><option value="ao">Angola</option><option value="ai">Anguilla</option><option value="ar">Argentina</option><option value="am">Armenia</option><option value="au">Australia</option><option value="at">Austria</option><option value="az">Azerbaijan</option><option value="bs">Bahamas</option><option value="bh">Bahrain</option><option value="bd">Bangladesh</option><option value="bb">Barbados</option><option value="by">Belarus</option><option value="be">Belgium</option><option value="bj">Benin</option><option value="bt">Bhutan</option><option value="bo">Bolivia</option><option value="ba">Bosnia and Herzegovina</option><option value="bw">Botswana</option><option value="br">Brazil</option><option value="bg">Bulgaria</option><option value="bf">Burkina Faso</option>
	                    <option value="kh">Cambodia</option><option value="cm">Cameroon</option><option value="ca">Canada</option><option value="td">Chad</option><option value="cl">Chile</option><option value="cn">China</option><option value="co">Colombia</option><option value="cd">Congo</option><option value="cr">Costa Rica</option><option value="hr">Croatia</option><option value="cu">Cuba</option><option value="cz">Czech Republic</option><option value="dk">Denmark</option><option value="do">Dominican Republic</option><option value="ec">Ecuador</option><option value="eg">Egypt</option><option value="ee">Estonia</option><option value="et">Ethiopia</option><option value="fj">Fiji</option><option value="fi">Finland</option><option value="fr">France</option><option value="ga">Gabon</option><option value="de">Germany</option><option value="gh">Ghana</option><option value="gr">Greece</option><option value="ht">Haiti</option><option value="hk">Hong Kong</option>
	                    <option value="hu">Hungary</option><option value="id">Indonesia</option><option value="iq">Iraq</option><option value="ie">Ireland</option><option value="il">Israel</option><option value="it">Italy</option>
	                    <option value="jm">Jamaica</option><option value="jp">Japan</option><option value="kz">Kazakhstan</option><option value="lv">Latvia</option><option value="lt">Lithuania</option><option value="lu">Luxembourg</option><option value="mx">Mexico</option><option value="ma">Morocco</option><option value="nl">Netherlands</option><option value="nz">New Zealand</option><option value="no">Norway</option><option value="om">Oman</option><option value="pk">Pakistan</option><option value="pa">Panama</option><option value="pg">Papua New Guinea</option><option value="py">Paraguay</option><option value="pe">Peru</option><option value="pl">Poland</option><option value="pt">Portugal</option><option value="pr">Puerto Rico</option><option value="qa">Qatar</option><option value="ro">Romania</option><option value="ru">Russian Federation</option><option value="sk">Slovakia</option><option value="za">South Africa</option><option value="es">Spain</option><option value="se">Sweden</option><option value="ch">Switzerland</option><option value="tw">Taiwan</option><option value="tr">Turkey</option><option value="ua">Ukraine</option><option value="gb">United Kingdom</option><option value="us">United States</option><option value="uy">Uruguay</option>
	                    <option value="ve">Venezuela</option><option value="vn">Vietnam</option><option value="zm">Zambia</option><option value="zw">Zimbabwe</option>
	                </select></td></tr>';
	}
	if($config['site']['verify_code'])
		$main_content .= '<script type="text/javascript">var verifya=1;</script><TR><TD width="150"><B>Code: </B></TD><TD colspan="2"><img src="?subtopic=imagebuilder&image_refresher='.mt_rand(1,99999).'" border="0" alt="Image Verification is missing, please contact the administrator"></TD></TR>
						  <TR><TD width="150" valign="top"><B>Verification Code: </B></TD><TD colspan="2"><INPUT id="verify" NAME="reg_code" VALUE="" SIZE=30 MAXLENGTH=50><BR><font size="1" face="verdana,arial,helvetica">(Here write verification code from picture)</font></TD></TR>';
	else
		$main_content .= '<script type="text/javascript">var verifya=0;</script>';
	$main_content .= '</TABLE>
					  </TD></TR>
					  <TR><TD>
					    <TABLE BORDER=0 CELLSPACING=5 CELLPADDING=0><TR><TD>
					       Please review the following terms and state your agreement below.
					    </TD></TR>
					    <TR><TD>
					      <B>'.htmlspecialchars($config['server']['serverName']).' Rules</B><BR>
					      <TEXTAREA ROWS="16" WRAP="physical" COLS="75" READONLY="true">';
	//load server rules from file
	include("pages/tibiarules.php");
	$main_content .= '</TEXTAREA>
					    </TD></TR></TABLE>
					  </TD></TR>
					  <TR><TD>
					    <TABLE BORDER=0 CELLSPACING=5 CELLPADDING=0>
					    <TR><TD>
					      <INPUT TYPE="checkbox" NAME="rules" id="rules" value="true" /><label for="rules"><u> I agree to the '.htmlspecialchars($config['server']['serverName']).' Rules.</u></lable><BR>
					    </TD></TR>
					    <TR><TD>
					      If you fully agree to these terms, click on the "I Agree" button in order to create an '.htmlspecialchars($config['server']['serverName']).' account.<BR>
					      If you do not agree to these terms or do not want to create an '.htmlspecialchars($config['server']['serverName']).' account, please click on the "Cancel" button.
					    </TD></TR></TABLE>
					  </TD></TR>
					</TABLE></TD></TR>
					</TABLE>
					<BR>
					<TABLE BORDER=0 WIDTH=100%>
					  <TR><TD ALIGN=center>
					    <IMG SRC="'.$layout_name.'/images/blank.gif" WIDTH=120 HEIGHT=1 BORDER=0><BR>
					  </TD><TD ALIGN=center VALIGN=top>
					    <INPUT TYPE=image NAME="I Agree" SRC="'.$layout_name.'/images/buttons/sbutton_iagree.gif" BORDER=0 WIDTH=120 HEIGHT=18>
					    </FORM>
					  </TD><TD ALIGN=center>
					    <FORM  ACTION="?subtopic=latestnews" METHOD=post>
					    <INPUT TYPE=image NAME="Cancel" SRC="'.$layout_name.'/images/buttons/sbutton_cancel.gif" BORDER=0 WIDTH=120 HEIGHT=18>
					    </FORM>
					  </TD><TD ALIGN=center>
					    <IMG SRC="/images/blank.gif" WIDTH=120 HEIGHT=1 BORDER=0><BR>
					  </TD></TR>
					</TABLE>
					</TD>
					<TD><IMG SRC="'.$layout_name.'/images/blank.gif" WIDTH=10 HEIGHT=1 BORDER=0></TD>
					</TR>
					</TABLE>';
}
//CREATE ACCOUNT PAGE (save account in database)
if($action == "saveaccount")
{
	$reg_name = strtoupper(trim($_POST['reg_name']));
	$reg_email = trim($_POST['reg_email']);
	$reg_password = trim($_POST['reg_password']);
	$reg_code = trim($_POST['reg_code']);
	if ($config['site']['select_flag']) {
		$reg_country = trim($_POST['reg_country']);
	}
	//FIRST check
	//check e-mail
	if(empty($reg_name))
		$reg_form_errors[] = "Please enter account name.";
	elseif(!check_account_name($reg_name))
		$reg_form_errors[] = "Invalid account name format. Use only A-Z and numbers 0-9.";
	if(empty($reg_email))
		$reg_form_errors[] = "Please enter your email address.";
	else
	{
		if(!check_mail($reg_email))
			$reg_form_errors[] = "E-mail address is not correct.";
	}
	if($config['site']['verify_code'])
	{
		//check verification code
		$string = strtoupper($_SESSION['string']);
		$userstring = strtoupper($reg_code);
		session_destroy();
		if(empty($string))
			$reg_form_errors[] = "Information about verification code in session is empty.";
		else
		{
			if(empty($userstring))
				$reg_form_errors[] = "Please enter verification code.";
			else
			{
				if($string != $userstring)
					$reg_form_errors[] = "Verification code is incorrect.";
			}
		}
	}
	//check password
	if(empty($reg_password) && !$config['site']['create_account_verify_mail'])
		$reg_form_errors[] = "Please enter password to your new account.";
	elseif(!$config['site']['create_account_verify_mail'])
	{
		if(!check_password($reg_password))
			$reg_form_errors[] = "Password contains illegal chars (a-z, A-Z and 0-9 only!) or lenght.";
	}
	//SECOND check
	//check e-mail address in database
	if(empty($reg_form_errors))
	{
		if($config['site']['one_email'])
		{
			$test_email_account = new Account();
			//load account with this e-mail
			$test_email_account->findByEmail($reg_email);
			if($test_email_account->isLoaded())
				$reg_form_errors[] = "Account with this e-mail address already exist in database.";
		}
		$account_db = new Account();
		$account_db->find($reg_name);
		if($account_db->isLoaded())
			$reg_form_errors[] = 'Account with this name already exist.';
	}
	// ----------creates account-------------(save in database)
	if(empty($reg_form_errors))
	{
		//create object 'account' and generate new acc. number
		if($config['site']['create_account_verify_mail'])
		{
			$reg_password = '';
			for ($i = 1; $i <= 6; $i++)
				$reg_password .= mt_rand(0,9);
		}
		$reg_account = new Account();
		// saves account information in database
		$reg_account->setName($reg_name);
		$reg_account->setPassword($reg_password);
		$reg_account->setEMail($reg_email);
		$reg_account->setCreateDate(time());
		$reg_account->setCreateIP(Visitor::getIP());
		if ($config['site']['select_flag']) {
			$reg_account->setFlag($reg_country);
		} else {
			$reg_account->setFlag(Website::getCountryCode(long2ip(Visitor::getIP())));
		}
		if(isset($config['site']['newaccount_premdays']) && $config['site']['newaccount_premdays'] > 0)
		{
			$reg_account->set("premdays", $config['site']['newaccount_premdays']);
			$reg_account->set("lastday", time());
		}
		$reg_account->save();
		//show information about registration
		if($config['site']['send_emails'] && $config['site']['create_account_verify_mail'])
		{
			$mailBody = '<html>
			<body>
			<h3>Your account name and password!</h3>
			<p>You or someone else registred on server <a href="'.$config['server']['url'].'"><b>'.htmlspecialchars($config['server']['serverName']).'</b></a> with this e-mail.</p>
			<p>Account name: <b>'.htmlspecialchars($reg_name).'</b></p>
			<p>Password: <b>'.htmlspecialchars(trim($reg_password)).'</b></p>
			<br />
			<p>After login you can:</p>
			<li>Create new characters
			<li>Change your current password
			<li>Change your current e-mail
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
			$mail->AddAddress($reg_email);
			$mail->Subject = $config['server']['serverName']." - Registration";
			$mail->Body = $mailBody;
			if($mail->Send())
			{
				$main_content .= 'Your account has been created. Check your e-mail. See you in Tibia!<BR><BR>';
				$main_content .= '<TABLE WIDTH=100% BORDER=0 CELLSPACING=1 CELLPADDING=4>
				<TR><TD BGCOLOR="'.$config['site']['vdarkborder'].'" CLASS=white><B>Account Created</B></TD></TR>
				<TR><TD BGCOLOR="'.$config['site']['darkborder'].'">
				  <TABLE BORDER=0 CELLPADDING=1><TR><TD>
				    <BR>Your account name is <b>'.$reg_name.'</b>.
					<BR><b><i>You will receive e-mail (<b>'.htmlspecialchars($reg_email).'</b>) with your password.</b></i><br>';
				$main_content .= 'You will need the account name and your password to play on '.htmlspecialchars($config['server']['serverName']).'.
				    Please keep your account name and password in a safe place and
				    never give your account name or password to anybody.<BR><BR>';
				$main_content .= '<br /><small>These informations were send on email address <b>'.htmlspecialchars($reg_email).'</b>. Please check your inbox/spam folder.';
			}
			else
			{
				$main_content .= '<br /><small>An error occorred while sending email! Account not created. Try again.</small>';
				$reg_account->delete();
			}
		}
		else
		{
			$main_content .= 'Your account has been created. Now you can login and create your first character. See you in Tibia!<BR><BR>';
			$main_content .= '<TABLE WIDTH=100% BORDER=0 CELLSPACING=1 CELLPADDING=4>
			<TR><TD BGCOLOR="'.$config['site']['vdarkborder'].'" CLASS=white><B>Account Created</B></TD></TR>
			<TR><TD BGCOLOR="'.$config['site']['darkborder'].'">
			  <TABLE BORDER=0 CELLPADDING=1><TR><TD>
			    <BR>Your account name is <b>'.htmlspecialchars($reg_name).'</b><br>You will need the account name and your password to play on '.htmlspecialchars($config['server']['serverName']).'.
			    Please keep your account name and password in a safe place and
			    never give your account name or password to anybody.<BR><BR>';
			if($config['site']['send_emails'] && $config['site']['send_register_email'])
			{
				$mailBody = '<html>
				<body>
				<h3>Your account name and password!</h3>
				<p>You or someone else registred on server <a href="'.$config['server']['url'].'"><b>'.htmlspecialchars($config['server']['serverName']).'</b></a> with this e-mail.</p>
				<p>Account name: <b>'.htmlspecialchars($reg_name).'</b></p>
				<p>Password: <b>'.htmlspecialchars(trim($reg_password)).'</b></p>
				<br />
				<p>After login you can:</p>
				<li>Create new characters
				<li>Change your current password
				<li>Change your current e-mail
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
				$mail->AddAddress($reg_email);
				$mail->Subject = $config['server']['serverName']." - Registration";
				$mail->Body = $mailBody;
				if($mail->Send())
					$main_content .= '<br /><small>These informations were send on email address <b>'.htmlspecialchars($reg_email).'</b>.';
				else
					$main_content .= '<br /><small>An error occorred while sending email (<b>'.htmlspecialchars($reg_email).'</b>)!</small>';
			}
		}
		$main_content .= '</TD></TR></TABLE></TD></TR></TABLE><BR><BR>';
	}
	else
	{
		//SHOW ERRORs if data from form is wrong
		$main_content .= '<div class="SmallBox" >  <div class="MessageContainer" >    <div class="BoxFrameHorizontal" style="background-image:url('.$layout_name.'/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeLeftTop" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeRightTop" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="ErrorMessage" >      <div class="BoxFrameVerticalLeft" style="background-image:url('.$layout_name.'/images/content/box-frame-vertical.gif);" /></div>      <div class="BoxFrameVerticalRight" style="background-image:url('.$layout_name.'/images/content/box-frame-vertical.gif);" /></div>      <div class="AttentionSign" style="background-image:url('.$layout_name.'/images/content/attentionsign.gif);" /></div><b>The Following Errors Have Occurred:</b><br/>';
		foreach($reg_form_errors as $show_msg)
		{
					$main_content .= '<li>'.$show_msg.'</li>';
		}
		$main_content .= '</div>    <div class="BoxFrameHorizontal" style="background-image:url('.$layout_name.'/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeRightBottom" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeLeftBottom" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>  </div></div><br/>
		<BR>
		<CENTER>
		<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0><FORM ACTION=?subtopic=createaccount METHOD=post><TR><TD>
		<INPUT TYPE=hidden NAME=email VALUE="">

		<INPUT TYPE=image NAME="Back" ALT="Back" SRC="'.$layout_name.'/images/buttons/sbutton_back.gif" BORDER=0 WIDTH=120 HEIGHT=18>
		</TD></TR></FORM></TABLE>
		</CENTER>';
	}
}