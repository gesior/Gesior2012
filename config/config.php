<?PHP
# Account Maker Config
$config['site']['serverPath'] = "/home/ots/";
$config['site']['server_name'] = 'Forgotten';
$towns_list = array(2 => 'Thais', 1 => 'Venore', 4 => 'Carlin');

$config['site']['outfit_images_url'] = 'https://outfit-images.ots.me/idleOutfits1092/outfit.php';
$config['site']['item_images_url'] = 'https://item-images.ots.me/1092/';
$config['site']['item_images_extension'] = '.gif';
$config['site']['flag_images_url'] = 'https://flag-images.ots.me/';
$config['site']['flag_images_extension'] = '.png';

# Create Account Options
$config['site']['one_email'] = false;
$config['site']['create_account_verify_mail'] = false;
$config['site']['verify_code'] = true;
// generate keys on https://www.google.com/recaptcha/admin with version 'v2' and type 'im not a robot'
$config['site']['recaptcha'] = false;
$config['site']['recaptcha_site_key'] = '';
$config['site']['recaptcha_secret_key'] = '';
$config['site']['email_days_to_change'] = 3;
$config['site']['newaccount_premdays'] = 999;
$config['site']['send_register_email'] = false;
$config['site']['select_flag'] = true;

# Create Character Options
$config['site']['newchar_vocations'] = array(1 => 'Sorcerer Sample', 2 => 'Druid Sample', 3 => 'Paladin Sample', 4 => 'Knight Sample');
$config['site']['newchar_towns'] = array(2);
$config['site']['max_players_per_account'] = 7;

# Emails Config
# to use Gmail as mailer, you must enable not secure apps access: https://myaccount.google.com/lesssecureapps
$config['site']['send_emails'] = false;
$config['site']['mail_address'] = "youraccount@gmail.com";
$config['site']['smtp_enabled'] = true;
$config['site']['smtp_host'] = "smtp.gmail.com";
$config['site']['smtp_port'] = 587;
$config['site']['smtp_auth'] = true;
$config['site']['smtp_user'] = "youraccount@gmail.com";
$config['site']['smtp_pass'] = "yourpassword";

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

# PAGE: guilds.php
$config['site']['guild_need_level'] = 15;
$config['site']['guild_need_pacc'] = false;
$config['site']['guild_image_size_kb'] = 50;
$config['site']['guild_description_chars_limit'] = 2000;
$config['site']['guild_description_lines_limit'] = 6;
$config['site']['guild_motd_chars_limit'] = 250;

# PAGE: adminpanel.php
$config['site']['access_admin_panel'] = 3;

# PAGE: latestnews.php
$config['site']['news_limit'] = 6;

# PAGE: killstatistics.php
$config['site']['last_deaths_limit'] = 40;

# PAGE: team.php
$config['site']['groups_support'] = array(2, 3, 4, 5, 6);

# PAGE: highscores.php
$config['site']['groups_hidden'] = array(4, 5, 6);
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

# character trade
$config['site']['trade_player_create_require_recovery_key'] = false;
$config['site']['trade_player_minimum_price'] = 100;
$config['site']['trade_player_minimum_level'] = 30;
$config['site']['trade_player_public_commission_fixed'] = 10;
$config['site']['trade_player_public_commission_percent'] = 10;
$config['site']['trade_player_private_commission_fixed'] = 0;
$config['site']['trade_player_private_commission_percent'] = 20;
