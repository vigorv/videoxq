<?
if (isset($is_requested) || isset($is_friend)) {
    echo "<h4>" . $user[0]['user']['username'] . "</h4> is on already requested as friend";
} else {
    ?>
    <div class="user">
        <h3>Add Friend</h3>
        <img class="userPhoto" src="#"/>
        <h4><?= $user[0]['user']['username']; ?></h4>
        <span>Хотите добавить в друзья?</span>
        <form id="f_add_friend" method="POST" action="/<?= $controller; ?>/friends/add">    
            <input type="hidden" name="userid" value="<?= $user[0]['user']['userid']; ?>"/>
            <? //<input type="submit" name="friendadd" value="<?= __('Add Friend', true); "/>     ?>
            <input type="submit" value="Добавить в друзья" />
            <a href="<?= $back_link; ?>"><?= __("Don't add", true); ?></a>
        </form>
    </div>
<? } ?>

<script langauge="javascript">
    $("#f_add_friend").submit(function(event){
        event.preventDefault();
        var $form = $( this ),
        url = $form.attr( 'action' );
        $.post( url, $form.serialize(),
        function( data ) {            
            $("block_main").html(data);
        });
    });
    
</script>
