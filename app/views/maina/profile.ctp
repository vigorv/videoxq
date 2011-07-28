<?
$html->addCrumb(__('Profile', true), '');
?>

<style type="text/css">
    .editable_l span{ width:200px;}
    .editable_l input {display:none; width:200px;}
</style>


<?php if (!empty($authUser)) : ?>
    <h1><?php echo $authUser['username']; ?>,  твой профиль на</h1>
    <form id="editable_f"action="" method="POST">
    <ul class="editable_l">
        <li> 
            <span><?= __('Your Login', true) ?></span>
            <input  name="username" type="text"  onBlur="SaveField(this);" value="<?= htmlentities($authUser['username']); ?>" />
            <span class="e_val"><?= htmlentities($authUser['username']); ?></span>
            <a class="h_edit" href="#" onclick="EditField(this);return false;">edit</a>
        </li>
        <li>
            <span><?=__('Your E-mail',true);?></span>
            <span class="e_val"><?= htmlentities($authUser['email']); ?></span>
        </li>
        
        <li>
            <span><?=__('Your Password',true);?></span>
            <a href="#"><?=__('ChangePassword',true);?></a>
        </li>
    </ul>
    </form>
<? else : ?>

    <h1>...авторизуйся, на</h1>
<? endif; ?>


<script type="text/javascript">
    <!--
        
    function EditField(elem){
        // there elem is a href
        var par=$(elem).parent();
      $(elem).hide();
        par.find('.e_val').hide();
        par.find('input').show();    
        par.find('input').focus();
    }        
    
    function SaveField(elem){
        //elem is input
        var par=$(elem).parent();
        var txt_field = par.find('.e_val');
        $(elem).hide();
        txt_field.show();        
        par.find('a').show();      
        var prev =txt_field.text();
        if (prev==$(elem).value())
            $('#editable_f').submit();        
    }
 
    $('.editable_l input').keypress(function(e){
        if(e.which == 13){
            SaveField(this);
        }
    });
    -->
</script>
