<?php

print exec("whereis php");
echo ">>>";
print exec("which php");
phpinfo();

$to = "rohit@netfrux.com, netfruxphp@gmail.com, sudhanshu@netfrux.com";
$subject = "My subject";
$txt = "Hello world!";
$headers = "From: webmaster@example.com" . "\r\n" .
"CC: somebodyelse@example.com";

$content = "some text here";
$fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/myText.txt","wb");
fwrite($fp,$content);
fclose($fp);

 if(mail($to,$subject,$message, $headers))
    {
        echo "Test email send.";
    } 
    else 
    {
        echo "Failed to send.";
    }
?>