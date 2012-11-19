<?php
if(!defined('INITIALIZED'))
	exit;

function replaceSmile($text, $smile)
{
    $smileys = array(';D' => 1, ':D' => 1, ':cool:' => 2, ';cool;' => 2, ':ekk:' => 3, ';ekk;' => 3, ';o' => 4, ';O' => 4, ':o' => 4, ':O' => 4, ':(' => 5, ';(' => 5, ':mad:' => 6, ';mad;' => 6, ';rolleyes;' => 7, ':rolleyes:' => 7, ':)' => 8, ';d' => 9, ':d' => 9, ';)' => 10);
    if($smile == 1)
        return $text;
    else
    {
        foreach($smileys as $search => $replace)
            $text = str_replace($search, '<img src="images/forum/smile/'.$replace.'.gif" />', $text);
        return $text;
    }
}

function replaceAll($text, $smile)
{
    $rows = 0;
    while(stripos($text, '[code]') !== false && stripos($text, '[/code]') !== false )
    {
        $code = substr($text, stripos($text, '[code]')+6, stripos($text, '[/code]') - stripos($text, '[code]') - 6);
        if(!is_int($rows / 2)) { $bgcolor = 'ABED25'; } else { $bgcolor = '23ED25'; } $rows++;
        $text = str_ireplace('[code]'.$code.'[/code]', '<i>Code:</i><br /><table cellpadding="0" style="background-color: #'.$bgcolor.'; width: 480px; border-style: dotted; border-color: #CCCCCC; border-width: 2px"><tr><td>'.$code.'</td></tr></table>', $text);
    }
    $rows = 0;
    while(stripos($text, '[quote]') !== false && stripos($text, '[/quote]') !== false )
    {
        $quote = substr($text, stripos($text, '[quote]')+7, stripos($text, '[/quote]') - stripos($text, '[quote]') - 7);
        if(!is_int($rows / 2)) { $bgcolor = 'AAAAAA'; } else { $bgcolor = 'CCCCCC'; } $rows++;
        $text = str_ireplace('[quote]'.$quote.'[/quote]', '<table cellpadding="0" style="background-color: #'.$bgcolor.'; width: 480px; border-style: dotted; border-color: #007900; border-width: 2px"><tr><td>'.$quote.'</td></tr></table>', $text);
    }
    $rows = 0;
    while(stripos($text, '[url]') !== false && stripos($text, '[/url]') !== false )
    {
        $url = substr($text, stripos($text, '[url]')+5, stripos($text, '[/url]') - stripos($text, '[url]') - 5);
        $text = str_ireplace('[url]'.$url.'[/url]', '<a href="'.$url.'" target="_blank">'.$url.'</a>', $text);
    }
    while(stripos($text, '[player]') !== false && stripos($text, '[/player]') !== false )
    {
        $player = substr($text, stripos($text, '[player]')+8, stripos($text, '[/player]') - stripos($text, '[player]') - 8);
        $text = str_ireplace('[player]'.$player.'[/player]', '<a href="?subtopic=characters&name='.urlencode($player).'">'.$player.'</a>', $text);
    }
    while(stripos($text, '[img]') !== false && stripos($text, '[/img]') !== false )
    {
        $img = substr($text, stripos($text, '[img]')+5, stripos($text, '[/img]') - stripos($text, '[img]') - 5);
        $text = str_ireplace('[img]'.$img.'[/img]', '<img src="'.$img.'">', $text);
    }
    while(stripos($text, '[b]') !== false && stripos($text, '[/b]') !== false )
    {
        $b = substr($text, stripos($text, '[b]')+3, stripos($text, '[/b]') - stripos($text, '[b]') - 3);
        $text = str_ireplace('[b]'.$b.'[/b]', '<b>'.$b.'</b>', $text);
    }
    while(stripos($text, '[i]') !== false && stripos($text, '[/i]') !== false )
    {
        $i = substr($text, stripos($text, '[i]')+3, stripos($text, '[/i]') - stripos($text, '[i]') - 3);
        $text = str_ireplace('[i]'.$i.'[/i]', '<i>'.$i.'</i>', $text);
    }
    while(stripos($text, '[u]') !== false && stripos($text, '[/u]') !== false )
    {
        $u = substr($text, stripos($text, '[u]')+3, stripos($text, '[/u]') - stripos($text, '[u]') - 3);
        $text = str_ireplace('[u]'.$u.'[/u]', '<u>'.$u.'</u>', $text);
    }
    return replaceSmile($text, $smile);
}

function showPost($topic, $text, $smile)
{
    $text = nl2br($text);
    $post = '';
    if(!empty($topic))
        $post .= '<b>'.replaceSmile($topic, $smile).'</b>';
    $post .= replaceAll($text, $smile);
    return $post;
}


    $last_threads = $SQL->query('SELECT ' . $SQL->tableName('players') . '.' . $SQL->fieldName('name') . ', ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('post_text') . ', ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('post_topic') . ', ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('post_smile') . ', ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('id') . ', ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('replies') . ', ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('post_date') . ' FROM ' . $SQL->tableName('players') . ', ' . $SQL->tableName('z_forum') . ' WHERE ' . $SQL->tableName('players') . '.' . $SQL->fieldName('id') . ' = ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('author_guid') . ' AND ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('section') . ' = 1 AND ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('first_post') . ' = ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('id') . ' ORDER BY ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('last_post') . ' DESC LIMIT ' . $config['site']['news_limit'])->fetchAll();
    if(isset($last_threads[0]))
    {
        $main_content .= '<table width="100%">';
        foreach($last_threads as $thread)
        {
            $main_content .= '<tr><td><b>' . htmlspecialchars($thread['post_topic']) . '</a></td><td style="text-align:right">' . date('d.m.y H:i:s', $thread['post_date']) . '</td></tr>';
            $main_content .= '<tr><td colspan="2">' . showPost('', $thread['post_text'], $thread['post_smile']) . '</td></tr>';
			$main_content .= '<tr><td>by <a href="?subtopic=characters&name='. urlencode($thread['name']) .'">'. htmlspecialchars($thread['name']) .'</a></td><td style="text-align:right"><a href="?subtopic=forum&action=show_thread&id=' . $thread['id'] . '">[Comments: ' . $thread['replies'] . ']</a></td></tr>';
			$main_content .= '<tr style="background-color:black;width:100%;height:3px"><td colspan="2"></td></tr>';
        }
        $main_content .= '</table>';
    }
    else
        $main_content .= '<h3>No news. Go forum and make new thread on board News.</h3>';