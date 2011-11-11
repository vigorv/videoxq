<?
$html->addCrumb(__('Profile', true), '');
?>
<script>
$(document).ready(function() {Visibility();});
</script>
<?php if (!empty($authUser) && (!empty($authUser['username']))) : ?>
    <h1 style="padding-left:17px;">
<?php if (isset($authUser['username'])) 
{
print $authUser['username'].'</span> 
<span id="vixod">
<a href="/users/logout" id="vixod_b">Выйти</a>
</span>';
} else {echo "Гость";};
?>
    </h1>
    <form id="editable_f"action="" method="POST">
        <ul class="editable_l">
            <li>
                <span><?= __('Ваш логин', true) ?> :</span>
                <input  name="username" type="text"  onBlur="SaveField(this);" value="<?= htmlentities($authUser['username']); ?>" />
                <span class="e_val"><?= htmlentities($authUser['username']); ?></span>
            </li>
            <li>
                <span><?= __('Ваш email', true); ?> : </span>
                <span class="e_val"><?= htmlentities($authUser['email']); ?></span>
            </li>
            <li>
            <br />            
                <a class="h_edit" href="#" onClick="ShowDiv(this)"><?= __('Изменить пароль', true); ?></a>
                <div class="adition_ch">
                    <input type="password" name="pass_previos" value=""/><br/>
                    <input type="password" name="pass_new" value=""/><br/>
                    <input type="password" name="pass_check"/><br/>
                    <input type="button" onClick="SaveDiv(this)" value="<?= __('Изменить пароль', true); ?>"/>
                </div>
            </li>
        </ul>
        <input type="submit" style="display:none"/>
    </form>
            <div class="clearfix"></div>
        <? else : ?>

            <h1>Пожалуйста авторизуйтесь</h1>
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
