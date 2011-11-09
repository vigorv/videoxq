<span id="siteTitle">Добро пожаловать в Личный Кабинет, 
<?php if (isset($authUser['username'])) 
{
print $authUser['username'].'</span> 
<span id="vixod">
<a href="/users/logout" id="vixod_b">Выйти</a>
</span>';
} else {echo "Гость</span>";};
?>
<a style="float:right; color:white; margin-top: 40px; text-decoration: none; padding: 2px; cursor: pointer;" onClick="return DivToggle('#block_right');" >
     &darr;&nbsp;Новости&nbsp;&darr;</a>
<script language="javascript">
    function DivToggle(name){
        $(name).toggle();
    }
</script>