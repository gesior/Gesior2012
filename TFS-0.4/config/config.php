<?PHP

$config['site']['serverPath'] = "C:/xampp/htdocs/";

$config['site']['useServerConfigCache'] = false; // cache server config in .php file, pages will load faster ~0.004 sec, remember that you must remove file '/config/server.config.php' to reload password to database from config.lua
$config['site']['worlds'] = array(0 => 'Acc. Maker Test World', 1 => 'Acc. Maker Test World 2');
//Others
$config['site']['private-servlist.com_server_id'] = 319; // server id on private-servlist.com to show Players Online Chart (whoisonline.php), set 0 to disable
$config['site']['quests'] = array('Annihilator' => 100,'Demon Helmet' => 2645,'Pits of Inferno' => 5550); // list of quests, 'questname' => storage-id,


// ACCOUNT config
$config['site']['one_email'] = false; // one e-mail can be used only to create one account
$config['site']['verify_code'] = true; // show verify code to block stupid scripts, set 0 if you have problems with image
$config['site']['email_days_to_change'] = 3; // how many days user need to change e-mail to account - block hackers
$config['site']['newaccount_premdays'] = 999; // how many PACC days receive new account

// E-MAIL config
$config['site']['send_emails'] = false; // is acc. maker configured to send e-mails?
$config['site']['mail_address'] = "xxxx@gmx.com"; // e-mail address
$config['site']['smtp_enabled'] = true; // send by smtp or mail function (set false if use mail function)
$config['site']['smtp_host'] = "mail.gmx.com"; // address
$config['site']['smtp_port'] = 25; // port
$config['site']['smtp_auth'] = false; // need authorization? (set false if not need auth)
$config['site']['smtp_user'] = "xxx@gmx.com"; // login
$config['site']['smtp_pass'] = "xxxx"; // password


// USE ONLY IF YOU CONFIGURED E-MAIL AND IT WORK
$config['site']['create_account_verify_mail'] = false; // when create account player must use right e-mail, he will receive random password to account like on RL tibia
$config['site']['generate_new_reckey'] = false; // let player generate new recovery key, he will receive e-mail with new rec key (not display on page, hacker can't generate rec key)
$config['site']['generate_new_reckey_price'] = 5; // you can get some Premium Points for new rec key
$config['site']['send_mail_when_change_password'] = true; // send e-mail with new password when change password to account, set false if someone abuse to send spam
$config['site']['send_mail_when_generate_reckey'] = true; // send e-mail with rec key (key is displayed on page when generate anyway), set false if someone abuse to send spam
$config['site']['send_register_email'] = true; // send e-mail when register account

// CHARACTER config, format: ID_of_vocation => 'Name of Character to copy', load vocations names from data/XML/vocations.xml
$config['site']['newchar_vocations'][0] = array(1 => 'Sorcerer Sample', 2 => 'Druid Sample', 3 => 'Paladin Sample', 4 => 'Knight Sample');
$config['site']['newchar_vocations'][1] = array(1 => 'Sorcerer Sample', 2 => 'Druid Sample', 3 => 'Paladin Sample', 4 => 'Knight Sample');
// sample, if rook only:             $config['site']['newchar_vocations'][0] = array(0 => 'Rook Sample');
$config['site']['newchar_towns'][0] = array(1);
$config['site']['newchar_towns'][1] = array(1);

// list of towns on ots
$towns_list[0] = array(1 => 'Thais', 2 => 'Venore', 3 => 'Carlin'); // list of towns, id => 'name', $towns_list[0] - for world id 0
$towns_list[1] = array(1 => 'Thais'); // list of towns, id => 'name', $towns_list[1] - for world id 1

$config['site']['max_players_per_account'] = 7; // max. number of characters on account


// GUILDS config
$config['site']['guild_need_level'] = 15; // minimum level to create guild
$config['site']['guild_need_pacc'] = false; // guild need pacc false / true
$config['site']['guild_image_size_kb'] = 50; // maximum size of image in KB
$config['site']['guild_description_chars_limit'] = 2000; // limit of guild description
$config['site']['guild_description_lines_limit'] = 6; // limit of lines, if description has more lines it will be showed as long text, without 'enters'
$config['site']['guild_motd_chars_limit'] = 250; // limit of MOTD (show in game?)


// ACC MAKER OPTIONS config
$config['site']['access_admin_panel'] = 3; // access level needed to open admin panel
$config['site']['news_limit'] = 6; // limit of news on latest news page
$config['site']['forum_link'] = "?subtopic=forum"; // link to server forum, leave empty if server doesn't have forum
$config['site']['last_deaths_limit'] = 40; // max. number of death on last death page
$config['site']['groups_support'] = array(2, 3, 4, 5, 6);
$config['site']['groups_hidden'] = array(4, 5, 6); // hidden in highscores
$config['site']['accounts_hidden'] = array(1); // hidden in highscores
$config['site']['shop_system'] = true; // show server shop page? use only if you installed LUA scripts of shop
$config['site']['download_page'] = true; // show download page?
$config['site']['serverinfo_page'] = true; // show server info page?
$config['site']['email_lai_sec_interval'] = 180; // time in seconds between e-mails to one account from lost account interface, block spam
$config['site']['show_skills_info'] = true;
$config['site']['show_vip_storage'] = 0;// the storage of vip, set 0 to hide it on page

// layout, available layouts: tibiacom
$config['site']['layout'] = 'tibiacom'; // layout name
$config['site']['vdarkborder'] = '#505050';
$config['site']['darkborder'] = '#D4C0A1';
$config['site']['lightborder'] = '#F1E0C6';