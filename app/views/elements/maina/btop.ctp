<span id="siteTitle">Добро пожаловать в Личный Кабинет, <?php if (isset($authUser['username'])) {echo $authUser['username'];} else {echo "Гость";}; ?></span>
<a style="float:right; color:white;" onClick="return DivToggle('#block_right');" >
    <img width="30px" height="30px" src="" alt="Toggle"/></a>
<span>
<form name="vixod" method="POST" action="/maina/">
<input type="submit" name="vixod_b" value="Выйти" />
</form>
</span>
<script language="javascript">
    function DivToggle(name){
        $(name).toggle();
    }
</script>