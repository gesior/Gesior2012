<?PHP
$spr = fopen( './data860/Tibia.spr', 'rb' );
fseek( $spr, 6 );
for($value = 0; $value <= 70000; $value++)
{
	$sprite = imagecreatetruecolor(32, 32);
	imagecolortransparent( $sprite, imagecolorallocate( $sprite, 0, 0, 0 ) );
	$key = 0;
	fseek( $spr, 6 + ( $value - 1 ) * 4 );
	extract( unpack( 'Laddress', fread( $spr, 4 ) ) );
	fseek( $spr, $address + 3 );
	extract( unpack( 'Ssize', fread( $spr, 2 ) ) );
	list( $num, $bit ) = array( 0, 0 );
	while( $bit < $size )
	{
		$pixels = unpack( 'Strans/Scolored', fread( $spr, 4 ) );
		$num += $pixels['trans'];
		for( $i = 0; $i < $pixels['colored']; $i++ )
		{
			extract( unpack( 'Cred/Cgreen/Cblue', fread( $spr, 3 ) ) );
			$red = ( $red == 0 ? ( $green == 0 ? ( $blue == 0 ? 1 : $red ) : $red ) : $red );
			imagesetpixel( $sprite, 
							$num % 32 + ( $key % 2 == 1 ? 32 : 0 ), 
							$num / 32 + ( $key % 4 != 1 and $key % 4 != 0 ? 32 : 0 ), 
							imagecolorallocate( $sprite, $red, $green, $blue ) );
			$num++;
		}
		$bit += 4 + 3 * $pixels['colored'];
	}
	echo '<br />Generated: <b>' . $value . '</b>';
	imagegif( $sprite, './sprites_unpacked_860/' . $value .'.gif' );
	imagedestroy($sprite);
	flush();
	usleep(50);
}
fclose( $spr );
?>