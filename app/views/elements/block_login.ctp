<?php
if (empty($authUser['userid'])):
 echo $form->create('User', array('action' => 'login', 'controller' => 'users'));?>
    <fieldset>
         <legend>Login</legend>
    <?php
        echo $form->input('username');
        echo $form->input('password', array('type' => 'password'));
        echo $form->input('remember_me', array('label' => __('Remember me', true), 'type' => 'checkbox'));
        echo $form->end(__('Enter', true));
        echo $html->link(__('Registration', true), array('action'=>'register', 'controller' => 'users')) . '<br>';
        echo $html->link(__('Lost password', true), array('action'=>'restore', 'controller' => 'users'));
    ?>
    </fieldset>
<?php
else:
echo 'Вы вошли как ' . $authUser['username'] . '<br>';
echo $html->link(__('Exit', true), array('action'=>'logout', 'controller' => 'users')) . '<br>';
endif;
?>
<?if (!empty($curLottery))
{?>
<!--<div class="attention"><b>Внимание! Розыгрыш призов!<a href=http://videoxq.com/users/lottery>
"Ищите и найдете"</a></b></div>--->
<?}?>