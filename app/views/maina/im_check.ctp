<?php
$n=0;
for ($i=0;$i < sizeof($messages);$i++)
 {
if(!empty($new_msg_id[$i]["pm"]["pmid"]))
    {
    $n++;
    }
 }
 if($n != 0)
 {
echo "У Вас ".$n." новых сообщений.";
}
?>