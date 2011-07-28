<?
$html->addCrumb(__('Profile', true), '');
?>

<script type="text/javascript">
    <!--
    function doEdit(id, nm, val)
    {
        $('#' + id).html('');
    }
        
    function EditField(elem){
          var par=$(elem).parent();
          par.find('a').hide();
          par.find('.e_val').hide();
          par.find('input').show();          
    }        
    -->
</script>

<style type="text/css">
.editable_l span{ width:200px;}
.editable_l input {display:none; width:200px;}

</style>


<?php if (!empty($authUser)) : ?>
    <h1><?php echo $authUser['username']; ?>,  твой профиль на</h1>
    <ul class="editable_l">
        <li> 
            <span><?= __('Your Login', true) ?></span>
            <input  type="text" value="<?= htmlentities($authUser['username']); ?>" />
            <span class="e_val"><?= htmlentities($authUser['username']); ?></span>
            <a class="h_edit" href="#" onclick="EditField(this);return false;">edit</a>
        </li>
    </ul>

                        		';
<? else : ?>

    <h1>...авторизуйся, на</h1>
<? endif; ?>