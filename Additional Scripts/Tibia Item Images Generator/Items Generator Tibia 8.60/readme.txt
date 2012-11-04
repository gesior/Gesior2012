THIS SCRIPT IS FOR EXPERIENCED USERS/PROGRAMMERS.
ALL FOLDERS [INCLUDING FOLDER WITH .PHP SCRIPT] MUST BE WRITEABLE FOR PHP.
1. Put files [TIBIA CLIENT FILES] Tibia.dat, Tibia.spr and [OTS FILE] items.otb in folder 'data860'.
2. Open in web browser script: unpack_sprites.php (it can take soome time)
- this script requires long maximum execution time limit in PHP [config in php.ini]
3. Open in web browser script: getsingle860.php with parameter id=100, example:
http://127.0.0.1/getsingle860.php?id=100
It will generate all items from 100 to 'unlimited' one by one.

Genarated item images will be in folder 'items_single'.