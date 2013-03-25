<?php
if(!defined('INITIALIZED'))
	exit;

####################       CONFIG      ###################################################
# activate dotpay, zaypay and other systems: true / false
# making something active/not active here doesn't mean that people can somehow abuse X system to buy points
# 
/* INTERNATIONAL SYSTEMS:
 * all systems are automatic, players should receive points after they pay without any admin 'action', they just need to send SMS and type received code
 * zaypay - in this gesior version it uses custom zaypay script which uses 'payalogues'
 * paypal - most popular payment system
 * contenidopago - sms payments system
*/
$config['paypal_active'] = true; // config is in './custom_scripts/paypal/'
$config['zaypay_active'] = true; // config is in './custom_scripts/zaypay/'
$config['contenidopago_active'] = true; // config is in './custom_scripts/contenidopago/'
/* POLISH SYSTEMS:
 * wszystkie systemy sa automatyczne i po konfiguracji powinny dodawac punkty po wpisaniu kodu jaki klient dostanie SMSem/e-mailem
 * dotpay - to system dzieki ktoremu mozna otrzymac kase z SMS (30-40% z sms dla osob prywatnych) z polski oraz przelewow bankowych (~97%)
*/

$config['dotpay'] = array();
$config['dotpay_active'] = true; #active dotpay system?
$config['dotpay_active_sms'] = true; #active dotpay SMS system?
$config['dotpay_active_transfer'] = false; #active dotpay bank transfers [type=C1] system?
# przykladowy konfig dla SMS
$config['dotpay'][0]['id'] = 53098;       # numer ID zarejestrowanego klienta
$config['dotpay'][0]['code'] = "SWEV3"; # identyfikator uslug SMS
$config['dotpay'][0]['type'] = "sms";   # typ konta: C1 - 8 znakowy kod bezobslugowy wysylany mailem, sms - dla sprawdzania SMSow
$config['dotpay'][0]['addpoints'] = 200; # ile premium punktow daje dany sms
$config['dotpay'][0]['sms_number'] = 73068; # numer na jaki nalezy wyslac kod
$config['dotpay'][0]['sms_text'] = "AP.SWEV3"; # tresc jaka ma byc w SMSie
$config['dotpay'][0]['sms_cost'] = "3.69 zł brutto"; # cena za wyslanie sms

$config['dotpay'][1]['id'] = 53098;       # numer ID zarejestrowanego klienta
$config['dotpay'][1]['code'] = "SWEV5"; # identyfikator uslug SMS
$config['dotpay'][1]['type'] = "sms";   # typ konta: C1 - 8 znakowy kod bezobslugowy wysylany mailem, sms - dla sprawdzania SMSow
$config['dotpay'][1]['addpoints'] = 400; # ile premium punktow daje dany sms
$config['dotpay'][1]['sms_number'] = 75068; # numer na jaki nalezy wyslac kod
$config['dotpay'][1]['sms_text'] = "AP.SWEV5"; # tresc jaka ma byc w SMSie
$config['dotpay'][1]['sms_cost'] = "6.15 zł brutto"; # cena za wyslanie sms

$config['dotpay'][2]['id'] = 53098;       # numer ID zarejestrowanego klienta
$config['dotpay'][2]['code'] = "SWEV9"; # identyfikator uslug SMS
$config['dotpay'][2]['type'] = "sms";   # typ konta: C1 - 8 znakowy kod bezobslugowy wysylany mailem, sms - dla sprawdzania SMSow
$config['dotpay'][2]['addpoints'] = 1000; # ile premium punktow daje dany sms
$config['dotpay'][2]['sms_number'] = 79068; # numer na jaki nalezy wyslac kod
$config['dotpay'][2]['sms_text'] = "AP.SWEV9"; # tresc jaka ma byc w SMSie
$config['dotpay'][2]['sms_cost'] = "11.07 zł brutto"; # cena za wyslanie sms

$config['dotpay'][3]['id'] = 53098;       # numer ID zarejestrowanego klienta
$config['dotpay'][3]['code'] = "SWEV24"; # identyfikator uslug SMS
$config['dotpay'][3]['type'] = "sms";   # typ konta: C1 - 8 znakowy kod bezobslugowy wysylany mailem, sms - dla sprawdzania SMSow
$config['dotpay'][3]['addpoints'] = 2500; # ile premium punktow daje dany sms
$config['dotpay'][3]['sms_number'] = 92058; # numer na jaki nalezy wyslac kod
$config['dotpay'][3]['sms_text'] = "AP.SWEV24"; # tresc jaka ma byc w SMSie
$config['dotpay'][3]['sms_cost'] = "24.60 zł brutto"; # cena za wyslanie sms


$config['homepay'] = array();
$config['homepay_active'] = true;
$config['homepay_user_ID'] = 512; // ID uzytkownika w homepay
$config['homepay_email_kontaktowy'] = 'edytuj@konfig.pl';
# opcje transferu
$config['homepay_active_sms'] = true; #active homepay sms system?
$config['homepay_active_transfer'] = true; #active homepay transfer system?
# przykladowy konfig dla SMS
$config['homepay'][0]['acc_id'] = 2; // ID uslugi
$config['homepay'][0]['addpoints'] = 10;
$config['homepay'][0]['sms_number'] = "79550";
$config['homepay'][0]['type'] = "sms";
$config['homepay'][0]['sms_text'] = "HPAY.NASZAUSLUGA";
$config['homepay'][0]['sms_cost'] = "11.07 zł brutto";
# przykladowy konfig dla przelewu
$config['homepay'][1]['acc_id'] = 1;
$config['homepay'][1]['addpoints'] = 10;
$config['homepay'][1]['link'] = "https://ssl.homepay.pl/wplata/1-NASZAUSLUGA";
$config['homepay'][1]['type'] = "przelew";
$config['homepay'][1]['przelew_text'] = "NASZAUSLUGA";
$config['homepay'][1]['przelew_cost'] = "10.00 zł brutto";

#################################################################################
function save_trans($file, $acc, $code)
{
	$hak = fopen($file, "a");
	fwrite($hak, $code.'='.$acc.'
');
	fclose($hak);
}

function check_code_dotpay($code, $posted_code, $user_id, $type)
{
	$handle = fopen("http://dotpay.pl/check_code.php?id=".urlencode($user_id)."&code=".urlencode($code)."&check=".urlencode($posted_code)."&type=".urlencode($type)."&del=0", 'r');
    $status = fgets($handle, 8);
    $czas_zycia = fgets($handle, 24);
    fclose($handle);
    $czas_zycia = rtrim($czas_zycia);
	return array($status, $czas_zycia);
}

function delete_code_dotpay($code, $posted_code, $user_id, $type)
{
	$handle = fopen("http://dotpay.pl/check_code.php?id=".urlencode($user_id)."&code=".urlencode($code)."&check=".urlencode($posted_code)."&type=".urlencode($type)."&del=1", 'r');
    fclose($handle);
}

function check_code_homepay($code, $usluga)
{
	global $config;
	if(!preg_match("/^[A-Za-z0-9]{8}$/",$code)) return 0;
	$code=urlencode($code);
	$handle=fopen("http://homepay.pl/API/check_code.php?usr_id=" . (int) $config['homepay_user_ID'] . "&acc_id=".(int)($config['homepay'][$usluga]['acc_id'])."&code=".$code,'r');
	$status=fgets($handle,8);
	fclose($handle);
	return $status;
}

function check_tcode_homepay($code, $usluga)
{
	global $config;
	if(!preg_match("/^[A-Za-z0-9]{8}$/",$code)) return 0;
	$code=urlencode($code);
	$handle=fopen("http://homepay.pl/API/check_tcode.php?usr_id=" . (int) $config['homepay_user_ID'] . "&acc_id=".(int)($config['homepay'][$usluga]['acc_id'])."&code=".$code,'r');
	$status=fgets($handle,8);
	fclose($handle);
	return $status;
}

function add_points($account, $number_of_points)
{
	if($account->isLoaded())
	{
		$account->set('premium_points', ($account->getCustomField('premium_points')+$number_of_points));
		$account->save();
		return true;
	}
	else
		return false;
}


if($_REQUEST['system'] == 'dotpay' && $config['dotpay_active'])
{
	#################################################################################
	$sms_type = (int) $_POST['sms_type'];
	$posted_code = trim($_POST['code']);
	$to_user = trim($_POST['to_user']);
	#################################################################################
	if(!empty($to_user))
	{
		$account = new Account();
		$account->find($to_user);
		
		if(empty($posted_code))
			$errors[] = 'Prosze wpisac kod z SMSa/przelewu.';
			
		if(!$account->isLoaded())
			$errors[] = 'Konto o danej nazwie nie istnieje.';
			
		if(count($errors) == 0)
		{
			if(count($errors) == 0)
			{
				$code_info = check_code_dotpay($config['dotpay'][$sms_type]['code'], $posted_code, $config['dotpay'][$sms_type]['id'], $config['dotpay'][$sms_type]['type']);
				if($code_info[0] == 0)
					$errors[] = 'Podany kod z SMSa/przelewu jest niepoprawny lub wybrano zla opcje SMSa/przelewu.';
				else
				{
					if(add_points($account, $config['dotpay'][$sms_type]['addpoints']))
					{
						save_trans('cache/dotpay.log', $account->getId(), $posted_code);
						$code_info = delete_code_dotpay($config['dotpay'][$sms_type]['code'], $posted_code, $config['dotpay'][$sms_type]['id'], $config['dotpay'][$sms_type]['type']);
						$main_content .= '<h1><font color="red">Dodano '.$config['dotpay'][$sms_type]['addpoints'].' punktow premium do konta: '.htmlspecialchars($to_user).' !</font></h1>';
					}
					else
						$errors[] = 'Wystapil blad podczas dodawania punktow do konta, sproboj ponownie.';
				}
			}
		}
	}
	$main_content .= '<div style="background-color:gray; padding: 10px 10px 10px 10px">';
	if(count($errors) > 0)
	{
		$main_content .= 'Wystąpiły błędy:';
		foreach($errors as $error)
			$main_content .= '<br />* '.$error;
		$main_content .= '<hr /><hr />';
	}
	if($config['dotpay_active_sms'])
	{
		$main_content .= '<h2>SMS</h2><span style="font-size:16px">Kup punkty premium, możesz je wymienić w sklepie OTSa na przedmioty w grze, aby zakupić punkty premium wyślij SMSa:</span><br />';
		foreach($config['dotpay'] as $sms)
			if($sms['type'] == 'sms')
				$main_content .= '<br /><span style="font-size:20px"><b>* Na numer <font color="darkred">'.$sms['sms_number'].'</font> o treści <font color="darkred"><b>'.$sms['sms_text'].'</b></font> za <font color="darkred"><b>'.$sms['sms_cost'].'</b></font>, a za kod dostaniesz <font color="darkred"><b>'.$sms['addpoints'].'</b></font> punktów premium.</b></span>';
		$main_content .= '<span style="font-size:16px"><br />W SMSie zwrotnym otrzymasz specjalny kod. Wpisz ten kod w formularzu wraz z NAZWĄ KONTA (nie nickiem postaci!) osoby która ma otrzymać punkty.<br />
		Serwis SMS obsługiwany przez <a href="http://www.dotpay.pl" target="_blank">Dotpay.pl</a><br />
		Regulamin: <a href="http://www.dotpay.pl/regulaminsms" target="_blank">http://www.dotpay.pl/regulaminsms</a><br />
		Właścicielem serwisu jest <b>js-internet-solutions@wp.pl</b> - w razie problemów z płatnością proszę o kontakt na e-mail lub na Gadu-Gadu: 31166049<br />
		Usługa jest dostępna w sieciach: <b>Orange, Era, Plus, Play</b>.<br />
		<b>Właściciele serwisu nie odpowiadają za źle wpisane treści SMS.</b><br /><br />
		<b>Wiadomości po 3.69 zł i 6.15 zł wysyłane z jednego numeru częściej, niż co 2 minuty mogą zostać zablokowane. Prosimy o odczekanie 2 minut pomiędzy SMSami.</b><br /><br />
		<b>Wiadomości po 11.07 zł i 24.60 zł wysyłane z jednego numeru częściej, niż co 20 minut mogą zostać zablokowane. Prosimy o odczekanie 20 minut pomiędzy SMSami.</b></span><hr />';
	}
	if($config['dotpay_active_transfer'])
	{
		$main_content .= '<h2>Przelew/karta kredytowa</h2>Kup punkty premium, mozesz je wymienic w sklepie OTSa na PACC/przedmioty w grze, aby zakupic punkty premium wejdz na jeden z adresow i wypelnij formularz:';
		foreach($config['dotpay'] as $przelew)
			if($przelew['type'] == 'C1')
				$main_content .= '<br /><b>* Adres - <a href="https://ssl.allpay.pl/?id='.$przelew['id'].'&code='.$przelew['code'].'"><font color="red">https://ssl.allpay.pl/?id='.$przelew['id'].'&code='.$przelew['code'].'</font></a> - koszt <font color="red"><b>'.$przelew['sms_cost'].'</b></font>, a za kod dostaniesz <font color="red"><b>'.$przelew['addpoints'].'</b></font> punktow premium.</b>';
		$main_content .= 'Kiedy Twoj przelew dojdzie (z kart kredytowych i bankow internetowych z listy jest to kwestia paru sekund) na e-mail ktory podales w formularzu otrzymasz kod. Kod ten mozesz wymienic na tej stronie na punkty premium w formularzu ponizej.<hr />';
	}
	$main_content .= '<form action="?subtopic=buypoints&system=dotpay" method="POST"><table>';
	$main_content .= '<tr><td><b>NAZWA KONTA: </b></td><td><input type="text" size="20" value="'.htmlspecialchars($to_user).'" name="to_user" /></td></tr>
	<tr><td><b>Kod z SMSa: </b></td><td><input type="text" size="20" value="'.htmlspecialchars($posted_code).'" name="code" /></td></tr><tr><td><b>Typ wyslanego SMSa: </b></td><td><select name="sms_type">';
	foreach($config['dotpay'] as $id => $sms)
		if($sms['type'] == 'sms')
			$main_content .= '<option value="'.$id.'">numer '.$sms['sms_number'].' - kod '.$sms['sms_text'].' - SMS za '.$sms['sms_cost'].'</option>';
		elseif($przelew['type'] == 'C1')
			$main_content .= '<option value="'.$id.'">przelew - kod '.$sms['sms_text'].' - za '.$sms['sms_cost'].'</option>';
	$main_content .= '</select></td></tr>';
	$main_content .= '<tr><td></td><td><input type="submit" value="Sprawdź" /></td></tr></table></form>';
	$main_content .= '</div>';
}
elseif ($_REQUEST['system'] == 'homepay' && $config['homepay_active'])
{
	#################################################################################
	$sms_type = (int) $_POST['sms_type'];
	$posted_code = trim($_POST['code']);
	$to_user = trim($_POST['to_user']);
	#################################################################################
	if(!empty($to_user))
	{
		$account = new Account();
		$account->find($to_user);
		
		if(empty($posted_code))
			$errors[] = 'Prosze wpisac kod z SMSa/przelewu.';
			
		if(!$account->isLoaded())
			$errors[] = 'Konto o danej nazwie nie istnieje.';

		if(count($errors) == 0)
		{
			if($config['homepay'][$sms_type]['type']=="sms")
			   $code_info = check_code_homepay($posted_code,$sms_type);
			else
			   $code_info = check_tcode_homepay($posted_code,$sms_type);
			if($code_info != "1")
				$errors[] = 'Podany kod z SMSa/przelewu jest niepoprawny lub wybrano zla opcje SMSa/przelewu.';
			else
			{
				if(add_points($account, $config['homepay'][$sms_type]['addpoints']))
				{
					$main_content .= '<h1><font color="red">Dodano '.$config['homepay'][$sms_type]['addpoints'].' punktów premium do konta: '.$to_user.' !</font></h1>';
				}
				else
					$errors[] = 'Wystapił błąd podczas dodawania punktów do konta, sprobój ponownie.';
			}
		}
	}
	if(count($errors) > 0)
	{
		$main_content .= 'Wystapiły błędy:';
		foreach($errors as $error)
			$main_content .= '<br />* '.$error;
		$main_content .= '<hr /><hr />';
	}
	if($config['homepay_active_sms'])
	{
		$main_content .= '<table><tr><td><h2 align="center">SMS</h2>Prosimy zapoznać się z regulaminem świadczonych usług zamieszczonym na dole tej strony.<br/><br/>';
		foreach($config['homepay'] as $typ)
			if($typ['type'] == 'sms')
				$main_content .= '<b>* Na numer <font color="green">'.$typ['sms_number'].'</font> o tresci <font color="green"><b>'.$typ['sms_text'].'</b></font> za <font color="green"><b>'.$typ['sms_cost'].'</b></font>, a za kod dostaniesz <font color="green"><b>'.$typ['addpoints'].'</b></font> punktów premium.</b><br/>';
		$main_content .= '</td></tr></table><br />';
	}
	if($config['homepay_active_transfer'])
	{
		$main_content .= '<table><tr><td><h2 align="center">Przelew</h2>Prosimy zapoznać się z regulaminem świadczonych usług zamieszczonym na dole tej strony.<br/><br/>';
		foreach($config['homepay'] as $typ)
			if($typ['type'] == 'przelew')
				$main_content .= '<b>* Adres - <a href="'.$typ['link'].'" target="_blank"><font color="green">'.$typ['link'].'</font></a> - koszt <font color="green"><b>'.$typ['przelew_cost'].'</b></font>, a za kod dostaniesz <font color="green"><b>'.$typ['addpoints'].'</b></font> punktów premium.</b><br/>';
		$main_content .= '</td></tr></table><br />';
	}
	$main_content .= '<table><tr><td><form action="?subtopic=buypoints&system=homepay" method="POST"><table>';
	$main_content .= '<tr><td><b>Nazwa konta: </b></td><td><input type="text" size="20" value="'.htmlspecialchars($to_user).'" name="to_user" /></td></tr>
	<tr><td><b>Kod z SMSa: </b></td><td><input type="text" size="20" value="'.htmlspecialchars($posted_code).'" name="code" /></td></tr><tr><td><b>Typ wyslanego SMSa: </b></td><td><select name="sms_type">';
	foreach($config['homepay'] as $id => $typ)
		if($typ['type'] == 'sms')
			$main_content .= '<option value="'.$id.'">numer '.$typ['sms_number'].' - kod '.$typ['sms_text'].' - SMS za '.$typ['sms_cost'].'</option>';
		elseif($typ['type'] == 'przelew')
			$main_content .= '<option value="'.$id.'">przelew - kod '.$typ['przelew_text'].' - za '.$typ['przelew_cost'].'</option>';
	$main_content .= '</select></td></tr><tr><td></td><td><input type="submit" value="Sprawdz" /></td></tr></table></form>
	</td></tr></table><br />
	<table><tr><td>
	<center><img border="0" src="http://homepay.pl/theme/default/image/logo/homepay_logo26.png"></center><br />
	<hr>
	Serwis SMS obsługiwany przez <a href="http://www.homepay.pl" target="_blank">Homepay.pl</a><br />
		 Regulamin: <a href="http://homepay.pl/regulamin/regulamin_sms_premium/" target="_blank">http://homepay.pl/regulamin/regulamin_sms_premium/</a><br />
		 Usługa dostępna w sieciach: Era, Orange, Play, Plus GSM.<br/>
	<hr>
	<b>Regulamin usług dostępnych na stronie:</b><br/>
	<b>1.a)</b> Kiedy Twój poprawnie wysłany SMS zostanie dostarczony otrzymasz SMS zwrotny z kodem.<br/>
	<b>1.b)</b> Kiedy Twój przelew zostanie zaksięgowany (z kart kredytowych i bankow internetowych z listy, jest to kwestia paru sekund) na e-mail który podałeś w formularzu otrzymasz kod.<br/>
	<b>2.</b> Po otrzymaniu kodu SMS/przelewu i wpisaniu go wraz z <b>nazwą konta w grze</b> w powyższym formularzu, na serwerze '.$config['server']['serverName'].' podane konto zostanie automatycznie doładowane o okresloną ilość <b>punktów premium</b> które nastepnie mogą być zamienione na przedmioty w grze.</b>.<br/>
	<b>3.</b> Do pełnego skorzystania z usługi wymagana jest przeglądarka internetowa oraz połączenie z siecią Internet.<br/>
	<b>4.</b> <b>'.$config['server']['serverName'].'</b> nie odpowiada za źle wpisane treści SMS.<br/>
	<b>5.</b> W razie problemów z działaniem usługi należy kontaktować się z <a href="mailto:' . $config['homepay_email_kontaktowy'] . '">' . $config['homepay_email_kontaktowy'] . '</a>
	</td></tr></table>';
}
elseif ($_REQUEST['system'] == 'zaypay' && $config['zaypay_active'])
{
	if($logged)
	{
		require_once('custom_scripts/zaypay/config.php');
		$main_content .= '<span style=""><center><h1>Buy points by Zaypay</h1></center><br />Zaypay accepts SMSes and phone calls from many countries. Select how many points you want buy and check if your country is on list of accepted countries.<br />After payment you will receive points in 5-10 seconds.</span>';
		foreach($options as $option)
		{
			$main_content .= '<script src="http://www.zaypay.com/pay/' . $option['payalogue_id'] . '.js" type="text/javascript"></script>';
			$main_content .= '<br /><div style="width:100%;height:40px;background-color:#333333"><div style="float:left;width:50%;text-align:center;color:white"><h2>' . $option['name'] . ':</h2></div>';
			$main_content .= '<div style="float:right;height:40px;text-align:left"><a href="http://www.zaypay.com/pay/' . $option['payalogue_id'] . '?acc=' . $account_logged->getId() . '" onclick="ZPayment(this); return false" ><img src="http://www.zaypay.com/pay/' . $option['payalogue_id'] . '/img" border="0" style="margin-top:2px" /></a></div></div>';
		}
	}
	else
	{
		$main_content .= '<h3>You have to login to buy points!<br /><a href="?subtopic=accountmanagement" />LOGIN HERE</a></h3>';
	}
}
elseif($_REQUEST['system'] == 'contenidopago' && $config['contenidopago_active'])
{
	if($logged)
	{
		require_once('custom_scripts/contenidopago/config.php');
			$main_content .= '<script src="http://promo.contenidopago.com/js/contenidopago.js" type="text/javascript"></script>	
			<form name="cnt_frm" method="post">
			<input type="hidden" name="cnt_serviceid" value="' . $idOfService . '">
			<input type="hidden" name="cnt_name" value="' . $account_logged->getId() . '">
			<input type="image" name="cnt_button" class="contenidopago" src="http://promo.contenidopago.com/botones/boton2.png" border="0" alt="Realiza pagos con contenidopago" title="Realiza pagos con contenidopago" onClick="cnt_reDirect(this.form)">     
		</form>';
	}
	else
	{
		$main_content .= '<h3>You have to login to buy points!<br /><a href="?subtopic=accountmanagement" />LOGIN HERE</a></h3>';
	}
}
else
{
	if($config['dotpay_active'])
		$main_content .= '<br /><br /><div style="background-color:gray;padding:20px 20px 20px 20px"><center><a href="?subtopic=buypoints&system=dotpay"><h2>For Polish players - Dotpay.pl</h2><h3>Po co przepłacać? Kup punkty w promocyjnej cenie specjalnie dla polaków!</h3><h2>KLIKNIJ TU</h2></a></center></div>';
	if($config['homepay_active'])
		$main_content .= '<br /><br /><div style="background-color:gray;padding:20px 20px 20px 20px"><center><a href="?subtopic=buypoints&system=homepay"><h2>For Polish players - Homepay.pl</h2><h3>Po co przepłacać? Kup punkty w promocyjnej cenie specjalnie dla polaków!</h3><h2>KLIKNIJ TU</h2></a></center></div>';
	if($config['paypal_active'])
		$main_content .= '<br /><br /><div style="background-color:gray;padding:20px 20px 20px 20px"><center><a href="?subtopic=paypal"><h2>PayPal</h2><h3>Cheapest points! Send us money from your PayPal account or credit card.</h3><h2>PRESS HERE!</h2></a></center></div>';
	if($config['zaypay_active'])
		$main_content .= '<br /><br /><div style="background-color:gray;padding:20px 20px 20px 20px"><center><a href="?subtopic=buypoints&system=zaypay"><h2>ZayPay</h2><h3>Send us money using SMS or phone call.</h3><h2>PRESS HERE!</h2></a></center></div>';
	if($config['contenidopago_active'])
		$main_content .= '<br /><br /><div style="background-color:gray;padding:20px 20px 20px 20px"><center><a href="?subtopic=buypoints&system=contenidopago"><h2>Contenidopago</h2><h3>Send us money using SMS or phone call.</h3><h2>PRESS HERE!</h2></a></center></div>';
}