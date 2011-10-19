<form action="<?=$page_link;?>" method="POST">
    <input type="hidden" name="userid" value="<?=$user_id;?>"/>
    <span>Message to <b><?=$user[0]['user']['username'];?></b></span><br/>
    <textarea name="txt"></textarea><br/>
    <input type="submit"  name="send_msg" value="SendMessage"/>

</form>