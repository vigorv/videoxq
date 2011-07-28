<?
$html->addCrumb(__('Profile', true), '');
?>

<style type="text/css">
    .editable_l span{ width:200px; }
    .h_save{ display:none;}
    .editable_l input {display:none; width:200px;}
    .adition_ch {display:none;}
    .adition_ch input {display:inline;}
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
                <a class="h_save" href="#" onclick="return false;">save</a>
            </li>
            <li>
                <span><?= __('Your E-mail', true); ?></span>
                <span class="e_val"><?= htmlentities($authUser['email']); ?></span>
            </li>

            <li>
                <span><?= __('Your Password', true); ?></span>
                <a href="#" onClick="ShowDiv(this)"><?= __('ChangePassword', true); ?></a>
                <div class="adition_ch">
                    <input type="password" name="pass_previos" value=""/><br/>
                    <input type="password" name="pass_new" value=""/><br/>
                    <input type="password" name="pass_check"/><br/>
                    <input type="button" onClick="SaveDiv(this)" value="<?= __('Change Password', true); ?>"/>
                </div>
            </li>
        </ul>
        <input type="submit" style="display:none"/>
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
        par.find('.h_save').show();
        par.find('input').focus();
            
    }        
    
    function ShowDiv(elem){
        var par=$(elem).parent();
        $(elem).hide();
        par.find('div').show();        
        
    }
    
    function SaveDiv(elem){
        var par_d=$(elem).parent();
        par_d.hide();

        var par = par_d.parent();
        par.find('.h_edit').show();        
        $.post(  
        "",  
        $('#editable_f').serialize(),         
        function(data){  
            //alert(data);  
        }); 
    }
    
    function SaveField(elem){
        //elem is input
        var par=$(elem).parent();
        var txt_field = par.find('.e_val');
        $(elem).hide();
        par.find('.h_save').hide();
        txt_field.show();        
        par.find('.h_edit').show();      
        var prev_v = txt_field.text();
        var next_v = elem.value; // this is js, no jquery
        if (prev_v!=next_v){
            $.post(  
            "",  
            $('#editable_f').serialize(),         
            function(data){  
                //alert(data);  
            }); 
        }
    }
    function CancelField(elem){
        //elem is input
        var par=$(elem).parent();
        var txt_field = par.find('.e_val');
        $(elem).hide();
        par.find('.h_save').hide();
        txt_field.show();        
        par.find('.h_edit').show();      
        elem.value = txt_field.text();
    }
 
 
    $('.editable_l input').keyup(function(e){
        if(e.which == 13){
            SaveField(this);
        }
        if (e.which == 27){
            CancelField(this);
        }
            
    });
    -->
</script>
