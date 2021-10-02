<?php
$imgpath ='/var/www/vhosts/fox-ae.com/dev.fox-ae.com/public/upload_videosearch_3657_org1579909357.jpg';
$imgpath2='/var/www/vhosts/fox-ae.com/dev.fox-ae.com/public/testing.jpg';
// exec("convert -quality 25 -define png:compression-filter=5 -define png:compression-level=9 -define png:compression-strategy=1 -define png:exclude-chunk=all -interlace Plane -colorspace sRGB $imgpath $imgpath2"); 
 
 exec("convert -strip -interlace Plane -gaussian-blur 0.05 -quality 85% $imgpath $imgpath2"); 
