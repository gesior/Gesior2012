<?php
if(!defined('INITIALIZED'))
	exit;

if($subtopic == "tibiarules")
{
	$main_content .= '<B>'.htmlspecialchars($config['site']['server_name']).' Rules</B><BR><TEXTAREA ROWS="25" WRAP="physical" COLS="80" READONLY="true">';
}
$main_content .= $config['site']['server_name'] . " is an online role-playing game in which thousands of players from all over the world meet everyday. In order to ensure that the game is fun for everyone, ".$config['site']['server_name']." staff expects all players to behave in a reasonable and respectful manner.

".$config['site']['server_name']." staff reserves the right to stop destructive behaviour in the game or in the official website. Such behaviour includes, but is not limited to, the following offences:

1. Names
a) Offensive Name
Names that are insulting, racist, sexually related, drug-related, harassing or generally objectionable.
b) Invalid Name Format
Names that contain parts of sentences (except for guild names), badly formatted words or nonsensical combinations of letters.
c) Name Containing Forbidden Advertising
Names that advertise brands, products or services of third parties, content which is not related to the game or trades for real money.
d) Unsuitable Name
Names that express religious or political views or generally do not fit into ".$config['site']['server_name']."'s medieval fantasy setting.
e) Name Supporting Rule Violation
Names that support, incite, announce or imply a violation of the ".$config['site']['server_name']." Rules.
 
2. Statements
a) Offensive Statement
Insulting, racist, sexually related, drug-related, harassing or generally objectionable statements.
b) Spamming
Excessively repeating identical or similar statements or using badly formatted or nonsensical text.
c) Forbidden Advertising
Advertising brands, products or services of third parties, content which is not related to the game or trades for real money.
d) Off-Topic Public Statement
Religious or political public statements or other public statements which are not related to the topic of the used channel or board.
e) Violating Language Restriction
Non-English statements in boards and channels where the use of English is explicitly required.
f) Disclosing Personal Data of Others
Disclosing personal data of other people.
g) Supporting Rule Violation
Statements that support, incite, announce or imply a violation of the ".$config['site']['server_name']." Rules.
 
3. Cheating
a) Bug Abuse
Exploiting obvious errors of the game.
b) Using Unofficial Software to Play
Manipulating the official client program or using additional software to play the game.
 
4. ".$config['site']['server_name']." staff
a) Pretending to be ".$config['site']['server_name']." staff
Pretending to be a representative of ".$config['site']['server_name']." staff or to have their legitimation or powers.
b) Slandering or Agitating against ".$config['site']['server_name']." staff
Publishing clearly wrong information about or calling a boycott against ".$config['site']['server_name'].".
c) False Information to ".$config['site']['server_name']." staff
Intentionally giving wrong or misleading information to ".$config['site']['server_name']." staff in reports about rule violations, complaints, bug reports or support requests.
 
5. Legal Issues
a) Account Trading or Sharing
Offering account data to other players, accepting other players' account data or allowing other players to use your account.
b) Hacking
Stealing other players' account or personal data.
c) Attacking ".$config['site']['server_name']." Server
Attacking, disrupting or damaging the operation of ".$config['site']['server_name']." server.
d) Violating Law or Regulations
Violating any applicable law, the ".$config['site']['server_name']." Service Agreement or rights of third parties.
 

Violating or attempting to violate the ".$config['site']['server_name']." Rules may lead to a temporary suspension of characters and accounts. In severe cases the removal or modification of character skills, attributes and belongings, as well as the permanent removal of characters and accounts without any compensation may be considered. The sanction is based on the seriousness of the rule violation and the previous record of the player. It is determined at the sole discretion of ".$config['site']['server_name']." staff and can be imposed without any previous warning.

These rules may be changed at any time. All changes will be announced on the ".$config['site']['server_name']." website.";
if($subtopic == "tibiarules")
{
	$main_content .= '</TEXTAREA>';
}