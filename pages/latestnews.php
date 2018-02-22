<?php
if(!defined('INITIALIZED'))
	exit;
/*
// top kills - guilds
$main_content .= '<table border="0" width="100%">
	<tr>
		<td style="text-align: center; font-weight: bold;">
			<center><font color="red">Most powerfull guilds</font></center>
		</td>
	</tr>
</table>';

$main_content .= '<table border="0" cellspacing="3" cellpadding="4" width="100%"><tr>';

foreach($SQL->query('SELECT ' . $SQL->tableName('g') . '.' . $SQL->fieldName('id') . ' AS ' . $SQL->fieldName('id') . ', ' . $SQL->tableName('g') . '.' . $SQL->fieldName('name') . ' AS ' . $SQL->fieldName('name') . ', COUNT(' . $SQL->tableName('g') . '.' . $SQL->fieldName('name') . ') AS ' . $SQL->fieldName('frags') . ' FROM ' . $SQL->tableName('killers') . ' k LEFT JOIN ' . $SQL->tableName('player_killers') . ' pk ON ' . $SQL->tableName('k') . '.' . $SQL->fieldName('id') . ' = ' . $SQL->tableName('pk') . '.' . $SQL->fieldName('kill_id') . ' LEFT JOIN ' . $SQL->tableName('players') . ' p ON ' . $SQL->tableName('pk') . '.' . $SQL->fieldName('player_id') . ' = ' . $SQL->tableName('p') . '.' . $SQL->fieldName('id') . ' LEFT JOIN ' . $SQL->tableName('guild_ranks') . ' gr ON ' . $SQL->tableName('p') . '.' . $SQL->fieldName('rank_id') . ' = ' . $SQL->tableName('gr') . '.' . $SQL->fieldName('id') . ' LEFT JOIN ' . $SQL->tableName('guilds') . ' g ON ' . $SQL->tableName('gr') . '.' . $SQL->fieldName('guild_id') . ' = ' . $SQL->tableName('g') . '.' . $SQL->fieldName('id') . ' WHERE ' . $SQL->tableName('g') . '.' . $SQL->fieldName('id') . ' > 0 AND ' . $SQL->tableName('k') . '.' . $SQL->fieldName('unjustified') . ' = 1 AND ' . $SQL->tableName('k') . '.' . $SQL->fieldName('final_hit') . ' = 1 GROUP BY ' . $SQL->fieldName('name') . ' ORDER BY ' . $SQL->fieldName('frags') . ' DESC, ' . $SQL->fieldName('name') . ' ASC LIMIT 4;') as $guild)
$main_content .= '<td style="width: 25%; text-align: center;"><a href="?subtopic=guilds&action=show&guild=' . $guild['id'] . '"><img src="guild_image.php?id=' . $guild['id'] . '" width="64" height="64" border="0"/><br />' . htmlspecialchars($guild['name']) . '</a><br />' . $guild['frags'] . ' kills
</td>';
$main_content .= '</tr></table>';
*/




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

    $last_tickers = $SQL->query('SELECT ' . $SQL->tableName('players') . '.' . $SQL->fieldName('name') . ', ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('post_text') . ', ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('post_topic') . ', ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('post_smile') . ', ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('id') . ', ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('replies') . ', ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('post_date') . ' FROM ' . $SQL->tableName('players') . ', ' . $SQL->tableName('z_forum') . ' WHERE ' . $SQL->tableName('players') . '.' . $SQL->fieldName('id') . ' = ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('author_guid') . ' AND ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('section') . ' = 1 AND ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('first_post') . ' = ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('id') . ' AND ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('post_topic') . '=\'News Ticker\'' . ' ORDER BY ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('last_post') . ' DESC LIMIT 5')->fetchAll();
    if(isset($last_tickers[0]))
    {

    $newsTicker = '<div id="newsticker" class="Box">
    <div class="Corner-tl" style="background-image:url('.$layout_name.'/images/general/corner-tl.gif);"></div>
    <div class="Corner-tr" style="background-image:url('.$layout_name.'/images/general/corner-tr.gif);"></div>
    <div class="Border_1" style="background-image:url('.$layout_name.'/images/general/border-1.gif);"></div>
    <div class="BorderTitleText" style="background-image:url('.$layout_name.'/images/general/title-background-green.gif);"></div><img id="ContentBoxHeadline" class="Title" src="'.$layout_name.'/images/general/headline-newsticker.gif" alt="Contentbox headline">    
    <div class="Border_2">
      <div class="Border_3">
        <div class="BoxContent" style="background-image:url('.$layout_name.'/images/general/scroll.gif);">';

    foreach($last_tickers as $ticker)
    {
        $newsTicker .= '<div id="TickerEntry-'.$ticker['id'].'" class="Row" onclick="TickerAction(&quot;TickerEntry-'.$ticker['id'].'&quot;)">
  <div class="Odd">
    <div class="NewsTickerIcon" style="background-image:url('.$layout_name.'/images/general/newsicon_support_small.gif)"></div>
    <div id="TickerEntry-'.$ticker['id'].'-Button" class="NewsTickerExtend" style="background-image: url('.$layout_name.'/images/general/plus.gif);"></div>
    <div class="NewsTickerText">
      <span class="NewsTickerDate"> '.date('M d Y', $ticker['post_date']).' &nbsp;&nbsp;-&nbsp;</span>
      <div id="TickerEntry-'.$ticker['id'].'-ShortText" class="NewsTickerShortText" style="display: block;">'.substr(showPost('', $ticker['post_text'], $ticker['post_smile']), 0, 70).'...</div>
      <div id="TickerEntry-'.$ticker['id'].'-FullText" class="NewsTickerFullText" style="display: none;">'.showPost('', $ticker['post_text'], $ticker['post_smile']).'</div>
    </div>
  </div>
</div>';
    }

    $newsTicker .= '</div>
      </div>
    </div>
    <div class="Border_1" style="background-image:url('.$layout_name.'/images/general/border-1.gif);"></div>
    <div class="CornerWrapper-b"><div class="Corner-bl" style="background-image:url('.$layout_name.'/images/general/corner-bl.gif);"></div></div>
    <div class="CornerWrapper-b"><div class="Corner-br" style="background-image:url('.$layout_name.'/images/general/corner-br.gif);"></div></div>
  </div>';
}

    $last_featuredArticle = $SQL->query('SELECT ' . $SQL->tableName('players') . '.' . $SQL->fieldName('name') . ', ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('post_text') . ', ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('post_topic') . ', ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('post_smile') . ', ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('id') . ', ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('replies') . ', ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('post_date') . ' FROM ' . $SQL->tableName('players') . ', ' . $SQL->tableName('z_forum') . ' WHERE ' . $SQL->tableName('players') . '.' . $SQL->fieldName('id') . ' = ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('author_guid') . ' AND ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('section') . ' = 1 AND ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('first_post') . ' = ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('id') . ' AND ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('post_topic') . '=\'Featured Article\'' . ' ORDER BY ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('last_post') . ' DESC LIMIT 1')->fetchAll();
    if(isset($last_featuredArticle[0]))
    {

  $featuredArticle = '<div id="featuredarticle" class="Box">
    <div class="Corner-tl" style="background-image:url('.$layout_name.'/images/general/corner-tl.gif);"></div>
    <div class="Corner-tr" style="background-image:url('.$layout_name.'/images/general/corner-tr.gif);"></div>
    <div class="Border_1" style="background-image:url('.$layout_name.'/images/general/border-1.gif);"></div>
    <div class="BorderTitleText" style="background-image:url('.$layout_name.'/images/general/title-background-green.gif);"></div><img id="ContentBoxHeadline" class="Title" src="'.$layout_name.'/images/general/headline-featuredarticle.gif" alt="Contentbox headline">    
    <div class="Border_2">
      <div class="Border_3">
        <div class="BoxContent" style="background-image:url('.$layout_name.'/images/general/scroll.gif);">';

    foreach($last_featuredArticle as $fArticle)
    {
        $featuredArticle .= '<a id="Link" style="position: absolute; margin-bottom: 10px; top: 2px;" href="?subtopic=forum&action=show_thread&id=' . $fArticle['id'] . '">» read more</a><div id="TeaserText">'.substr(showPost('', $fArticle['post_text'], $fArticle['post_smile']), 0, 400).'...</div>';
    }

    $featuredArticle .= '</div>
      </div>
    </div>
    <div class="Border_1" style="background-image:url('.$layout_name.'/images/general/border-1.gif);"></div>
    <div class="CornerWrapper-b"><div class="Corner-bl" style="background-image:url('.$layout_name.'/images/general/corner-bl.gif);"></div></div>
    <div class="CornerWrapper-b"><div class="Corner-br" style="background-image:url('.$layout_name.'/images/general/corner-br.gif);"></div></div>
  </div>';
}

    $last_threads = $SQL->query('SELECT ' . $SQL->tableName('players') . '.' . $SQL->fieldName('name') . ', ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('post_text') . ', ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('post_topic') . ', ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('post_smile') . ', ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('id') . ', ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('replies') . ', ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('post_date') . ' FROM ' . $SQL->tableName('players') . ', ' . $SQL->tableName('z_forum') . ' WHERE ' . $SQL->tableName('players') . '.' . $SQL->fieldName('id') . ' = ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('author_guid') . ' AND ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('post_topic') . '!=\'News Ticker\'' . ' AND ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('post_topic') . '!=\'Featured Article\'' . ' AND ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('section') . ' = 1 AND ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('first_post') . ' = ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('id') . ' ORDER BY ' . $SQL->tableName('z_forum') . '.' . $SQL->fieldName('last_post') . ' DESC LIMIT ' . $config['site']['news_limit'])->fetchAll();
    if(isset($last_threads[0]))
    {
        foreach($last_threads as $thread)
        {
            $main_content .= '<div class="NewsHeadline" style="margin-bottom: 10px;">
              <div class="NewsHeadlineBackground" style="background-image:url('.$layout_name.'/images/general/newsheadline_background.gif)">
                <img src="'.$layout_name.'/images/general/newsicon_community_big.gif" class="NewsHeadlineIcon" alt="">
                <div class="NewsHeadlineDate">'.date('M d Y', $thread['post_date']).' - </div>
                <div class="NewsHeadlineText">'.htmlspecialchars($thread['post_topic']).'</div>
              </div>
            </div>
            <table style="clear:both" border="0" cellpadding="0" cellspacing="0" width="100%"><tbody>
            <tr>'.showPost('', $thread['post_text'], $thread['post_smile']).'</tr><tr><td><div style="text-align:right;margin:10px 10px 0 0;"><a href="?subtopic=forum&action=show_thread&id=' . $thread['id'] . '">» Comment on this news</a></div></td></tr></tbody>
            </table>
            <br>';
        }
    }
    else
        $main_content .= '<h3>No news. Go forum and make new thread on board News.</h3>';