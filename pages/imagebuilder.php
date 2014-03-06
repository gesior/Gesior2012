<?php
if(!defined('INITIALIZED'))
	exit;

   $pool = '123456789HPXTWQM'; 
   $img_width = 80; 
   $img_height = 30; 
   $str = ''; 
   for ($i = 0; $i < 4; $i++){ 
       $str .= substr($pool, mt_rand(0, strlen($pool) -1), 1); 
   } 
   $string = $str;
   $im = imagecreate($img_width, $img_height); 
   $bg_color     = imagecolorallocate($im,103,103,103); 
   $font_color   = imagecolorallocate($im,252,252,252); 
   $grid_color   = imagecolorallocate($im,31,0,0); 
   $border_color = imagecolorallocate ($im, 174, 174, 174); 
   imagefill($im,1,1,$bg_color); 
   for($i=0; $i<500; $i++){ 

       $rand1 = rand(0,$img_width); 
       $rand2 = rand(0,$img_height); 
       imageline($im, $rand1, $rand2, $rand1, $rand2, $grid_color); 

   }
   $x = rand(5, $img_width/(7/2)); 
   imagerectangle($im, 0, 0, $img_width-1, $img_height-1, $border_color); 
   for($a=0; $a < 4; $a++){ 

       imagestring($im, 5, $x, rand(6 , $img_height/5), substr($string, $a, 1), $font_color); 
       $x += (5*2);
   }
   $_SESSION['string'] = $string; 
   header("Content-type: image/gif"); 
   imagegif($im); 
   imagedestroy($im); 
   exit;