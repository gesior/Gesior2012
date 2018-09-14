<?PHP
# Account Maker Config
$config['site']['serverPath'] = "C:/otsy/rlmap/";
$config['site']['serverName'] = 'RL OTS';
$config['site']['mysqlHost'] = '127.0.0.1';
$config['site']['mysqlPort'] = '3306';
$config['site']['mysqlUser'] = 'root';
$config['site']['mysqlPass'] = 'root';
$config['site']['mysqlDatabase'] = 'rlots';
$config['site']['url'] = 'http://127.0.0.1/';

$config['site']['outfit_images_url'] = 'http://outfit-images.ots.me/outfit.php';
$config['site']['item_images_url'] = 'http://item-images.ots.me/960/';
$config['site']['item_images_extension'] = '.gif';
$config['site']['flag_images_url'] = 'http://flag-images.ots.me/';
$config['site']['flag_images_extension'] = '.png';

# Create Account Options
$config['site']['one_email'] = false;
$config['site']['create_account_verify_mail'] = false;
$config['site']['verify_code'] = true;
$config['site']['email_days_to_change'] = 3;
$config['site']['send_register_email'] = false;
$config['site']['select_flag'] = true;

# Create Character Options
$config['site']['newchar_towns'] = ['Rookgaard'];
$config['site']['max_players_per_account'] = 1; // in RL OTS it's limited to 1 in database

# Emails Config
$config['site']['send_emails'] = false;
$config['site']['mail_address'] = "xxxx@gmx.com";
$config['site']['smtp_enabled'] = true;
$config['site']['smtp_host'] = "mail.gmx.com";
$config['site']['smtp_port'] = 25;
$config['site']['smtp_auth'] = false;
$config['site']['smtp_user'] = "xxx@gmx.com";
$config['site']['smtp_pass'] = "xxxx";

# PAGE: whoisonline.php
$config['site']['private-servlist.com_server_id'] = 0;
/*
Server id on 'private-servlist.com' to show Players Online Chart (whoisonline.php page), set 0 to disable Chart feature.
To use this feature you must register on 'private-servlist.com' and add your server.
Format: number, 0 [disable] or higher
*/

# PAGE: characters.php
$config['site']['quests'] = array();
$config['site']['show_skills_info'] = true;
$config['site']['show_vip_storage'] = 0;

# PAGE: accountmanagement.php
$config['site']['send_mail_when_change_password'] = true;
$config['site']['send_mail_when_generate_reckey'] = true;
$config['site']['generate_new_reckey'] = false;
$config['site']['generate_new_reckey_price'] = 500;


# PAGE: adminpanel.php
$config['site']['access_admin_panel'] = 3;

# PAGE: latestnews.php
$config['site']['news_limit'] = 6;

# PAGE: highscores.php
$config['site']['accounts_hidden'] = array(1);

# PAGE: shopsystem.php
$config['site']['shop_system'] = false;

# PAGE: lostaccount.php
$config['site']['email_lai_sec_interval'] = 180;

# Layout Config
$config['site']['layout'] = 'tibiacom';
$config['site']['vdarkborder'] = '#505050';
$config['site']['darkborder'] = '#D4C0A1';
$config['site']['lightborder'] = '#F1E0C6';
$config['site']['download_page'] = false;
$config['site']['serverinfo_page'] = true;
