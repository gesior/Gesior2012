<?PHP
if(!isset($_REQUEST['id']))
	die('not set $_REQUEST[\'id\'] parameter');

if($_REQUEST['id'] < 100)
	die('ID must be higher then 99');

define('HEX_PREFIX', '0x');

// let's define 9.60 attributes hex IDs
define('DatAttribIsGround', 0x00);
define('DatAttribIsGroundBorder', 0x01);
define('DatAttribIsOnBottom', 0x02);
define('DatAttribIsOnTop', 0x03);
define('DatAttribIsContainer', 0x04);
define('DatAttribIsStackable', 0x05);
define('DatAttribIsForceUse', 0x06);
define('DatAttribIsMultiUse', 0x07);
define('DatAttribIsWritable', 0x08);
define('DatAttribIsWritableOnce', 0x09);
define('DatAttribIsFluidContainer', 0x0A);
define('DatAttribIsFluid', 0x0B);
define('DatAttribIsNotWalkable', 0x0C);
define('DatAttribIsNotMoveable', 0x0D);
define('DatAttribBlockProjectile', 0x0E);
define('DatAttribIsNotPathable', 0x0F);
define('DatAttribIsPickupable', 0x10);
define('DatAttribIsHangable', 0x11);
define('DatAttribHookSouth', 0x12);
define('DatAttribHookEast', 0x13);
define('DatAttribIsRotateable', 0x14);
define('DatAttribHasLight', 0x15);
define('DatAttribDontHide', 0x16);
define('DatAttribIsTranslucent', 0x17);
define('DatAttribHasDisplacement', 0x18);
define('DatAttribHasElevation', 0x19);
define('DatAttribIsLyingCorpse', 0x1A);
define('DatAttribAnimateAlways', 0x1B);
define('DatAttribMiniMapColor', 0x1C);
define('DatAttribLensHelp', 0x1D);
define('DatAttribIsFullGround', 0x1E);
define('DatAttribIgnoreLook', 0x1F);
define('DatAttribCloth', 0x20);
define('DatAttribMarket', 0x21);

$item_ots_id = $_REQUEST['id'];

// example of empty graphic
$empty = sha1(file_get_contents('empty_960.gif'));

/* READ OTB */
$otb = fopen('./data960/items.otb', 'rb');
$init = false;
while(false !== ($char = fgetc($otb)))
{
	$byte = HEX_PREFIX.bin2hex($char);
	if($byte == 0xFE)
		$init = true;
	elseif($byte == 0x10 and $init)
	{
		extract(unpack('x2/Ssid', fread($otb, 4)));
		if($item_ots_id == $sid)
		{
			if(HEX_PREFIX.bin2hex(fread($otb, 1)) == 0x11)
			{
				extract(unpack('x2/Sitem_id_in_dat_file', fread($otb, 4)));
				break;
			}
		}
		$init = false;
	}
}
fclose( $otb );
/* CLOSE OTB */

if(!isset($item_id_in_dat_file))
{
	file_put_contents('otb_error.txt', $item_ots_id . ', ', FILE_APPEND);
	echo '<script>window.location = "getsingle960.php?id=' . ($item_ots_id+1) . '";</script>';
	exit;
	//die('item with ID ' . $item_ots_id . ' not found in .otb');
}

/* READ DAT */
$dat = fopen('./data960/Tibia.dat', 'rb');
$max = array_sum(unpack('x4/S*', fread($dat, 16)));

if($item_id_in_dat_file > $max)
{
	file_put_contents('dat_error.txt', $item_ots_id . ', ', FILE_APPEND);
	echo '<script>window.location = "getsingle960.php?id=' . ($item_ots_id+1) . '";</script>';
	exit;
	//die($item_id_in_dat_file . ' ID is higher then number of items in .dat file ' . $max);
}

for($i = 100; $i <= $item_id_in_dat_file; $i++) // it's binary file, we must read all until we go to ID that we need
{
	while(($byte = HEX_PREFIX.bin2hex(fgetc( $dat ))) != 0xFF)
	{
		switch($byte)
		{
			case DatAttribIsGround:
				fseek($dat, 2, SEEK_CUR);
				break;
			case DatAttribIsGroundBorder:
				break;
			case DatAttribIsOnBottom:
				break;
			case DatAttribIsOnTop:
				break;
			case DatAttribIsContainer:
				break;
			case DatAttribIsStackable:
				break;
			case DatAttribIsForceUse:
				break;
			case DatAttribIsMultiUse:
				break;
			case DatAttribIsWritable:
				fseek($dat, 2, SEEK_CUR);
				break;
			case DatAttribIsWritableOnce:
				fseek($dat, 2, SEEK_CUR);
				break;
			case DatAttribIsFluidContainer:
				break;
			case DatAttribIsFluid:
				break;
			case DatAttribIsNotWalkable:
				break;
			case DatAttribIsNotMoveable:
				break;
			case DatAttribBlockProjectile:
				break;
			case DatAttribIsNotPathable:
				break;
			case DatAttribIsPickupable:
				break;
			case DatAttribIsHangable:
				break;
			case DatAttribHookSouth:
				break;
			case DatAttribHookEast:
				break;
			case DatAttribIsRotateable:
				break;
			case DatAttribHasLight:
				fseek($dat, 4, SEEK_CUR);
				break;
			case DatAttribDontHide:
				break;
			case DatAttribIsTranslucent:
				break;
			case DatAttribHasDisplacement:
				fseek($dat, 4, SEEK_CUR);
				break;
			case DatAttribHasElevation:
				fseek($dat, 2, SEEK_CUR);
				break;
			case DatAttribIsLyingCorpse:
				break;
			case DatAttribAnimateAlways:
				break;
			case DatAttribMiniMapColor:
				fseek($dat, 2, SEEK_CUR);
				break;
			case DatAttribLensHelp:
				fseek($dat, 2, SEEK_CUR);
				break;
			case DatAttribIsFullGround:
				break;
			case DatAttribIgnoreLook:
				break;
			case DatAttribCloth:
				fseek($dat, 2, SEEK_CUR);
				break;
			case DatAttribMarket:
				fseek($dat, 6, SEEK_CUR);
				$stringLength = array_product(unpack('C*', fread($dat, 1)))+1;
				if($stringLength > 0)
				{
					fseek($dat, $stringLength, SEEK_CUR);
				}
				fseek($dat, 4, SEEK_CUR);
				break;
			default:
			$errorByte = ftell($dat);
			die(sprintf( 'Unknown .DAT byte %s (previous byte: %s; address %x)', $byte, $prev, $errorByte)); 
			break;
		}
		$prev = $byte;
	}
	extract(unpack('Cwidth/Cheight', fread($dat, 2)));
	$exactSize = 32;
	if($width > 1 or $height > 1)
	{
		$exactSize = min(array_product(unpack('C*', fread($dat, 1))), max($width * 32, $height * 32));
	}
	$layers = array_product(unpack('C*', fread($dat,1)));
	$divx = array_product(unpack('C*', fread($dat,1)));
	$divy = array_product(unpack('C*', fread($dat,1)));
	$divz = array_product(unpack('C*', fread($dat,1)));
	$anims = array_product(unpack('C*', fread($dat, 1)));
	$sprites_count = $layers * $divx * $divy * $divz * $anims * $width * $height;
	$sprites = unpack('l*', fread($dat, 4 * $sprites_count)); // in 9.60 it's stored in 'long' (32 bit, not old 'short' - 16 bit), so we read 4 bytes for each
}
fclose( $dat );
/* CLOSE DAT */

foreach($sprites as $i => $sprite) // let's copy
{
	copy('sprites_unpacked_960/' . $sprite . '.gif', 'items_parts/' . $item_ots_id . '_' . $i . '.gif');
}

$offset = 0;
for($anim = 1; $anim <= $anims; $anim++) // for each animation frame
{
	$sprite = imagecreatetruecolor( 32 * $width, 32 * $height );
	imagecolortransparent( $sprite, imagecolorallocate( $sprite, 0, 0, 0));
	for($layer = 1; $layer <= $layers; $layer++) // for each layer
	{
		// generate items 32x32, 64x32, 32x64 and 64x64
		if($height == 1 && $width == 1)
		{
			if(sha1(file_get_contents('./items_parts/' . $item_ots_id . '_' . ($offset + 1) . '.gif')) != $empty)
			{
				$part = imagecreatefromgif('./items_parts/' . $item_ots_id . '_' . ($offset + 1) . '.gif');
				imagecopy($sprite, $part, 0, 0, 0, 0, 32, 32);
			}
			$offset += 1;
		}
		elseif($height == 1 && $width == 2)
		{
			if(sha1(file_get_contents('./items_parts/' . $item_ots_id . '_' . ($offset + 1) . '.gif')) != $empty)
			{
				$part = imagecreatefromgif('./items_parts/' . $item_ots_id . '_' . ($offset + 1) . '.gif');
				imagecopy($sprite, $part, 32, 0, 0, 0, 32, 32);
			}
			if(sha1(file_get_contents('./items_parts/' . $item_ots_id . '_' . ($offset + 2) . '.gif')) != $empty)
			{
				$part = imagecreatefromgif('./items_parts/' . $item_ots_id . '_' . ($offset + 2) . '.gif');
				imagecopy($sprite, $part, 0, 0, 0, 0, 32, 32);
			}
			$offset += 2;
		}
		elseif($height == 2 && $width == 1)
		{
			if(sha1(file_get_contents('./items_parts/' . $item_ots_id . '_' . ($offset + 1) . '.gif')) != $empty)
			{
				$part = imagecreatefromgif('./items_parts/' . $item_ots_id . '_' . ($offset + 1) . '.gif');
				imagecopy($sprite, $part, 0, 32, 0, 0, 32, 32);
			}
			if(sha1(file_get_contents('./items_parts/' . $item_ots_id . '_' . ($offset + 2) . '.gif')) != $empty)
			{
				$part = imagecreatefromgif('./items_parts/' . $item_ots_id . '_' . ($offset + 2) . '.gif');
				imagecopy($sprite, $part, 0, 0, 0, 0, 32, 32);
			}
			$offset += 2;
		}
		elseif($height == 2 && $width == 2)
		{
			if(sha1(file_get_contents('./items_parts/' . $item_ots_id . '_' . ($offset + 1) . '.gif')) != $empty)
			{
				$part = imagecreatefromgif('./items_parts/' . $item_ots_id . '_' . ($offset + 1) . '.gif');
				imagecopy($sprite, $part, 32, 32, 0, 0, 32, 32);
			}
			if(sha1(file_get_contents('./items_parts/' . $item_ots_id . '_' . ($offset + 2) . '.gif')) != $empty)
			{
				$part = imagecreatefromgif('./items_parts/' . $item_ots_id . '_' . ($offset + 2) . '.gif');
				imagecopy($sprite, $part, 0, 32, 0, 0, 32, 32);
			}
			if(sha1(file_get_contents('./items_parts/' . $item_ots_id . '_' . ($offset + 3) . '.gif')) != $empty)
			{
				$part = imagecreatefromgif('./items_parts/' . $item_ots_id . '_' . ($offset + 3) . '.gif');
				imagecopy($sprite, $part, 32, 0, 0, 0, 32, 32);
			}
			if(sha1(file_get_contents('./items_parts/' . $item_ots_id . '_' . ($offset + 4) . '.gif')) != $empty)
			{
				$part = imagecreatefromgif('./items_parts/' . $item_ots_id . '_' . ($offset + 4) . '.gif');
				imagecopy($sprite, $part, 0, 0, 0, 0, 32, 32);
			}
			$offset += 4;
		}
	}
	// save frame of animation (image) to file
	imagegif( $sprite, './items_animated/' . $item_ots_id . '_' . $anim . '.gif' );
	if($anim == 1)
		imagegif( $sprite, './items_single/' . $item_ots_id . '.gif' );
}
echo '<script>window.location = "getsingle960.php?id=' . ($item_ots_id+1) . '";</script>';
?>
