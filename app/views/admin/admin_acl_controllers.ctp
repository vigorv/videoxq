<?php
if(isset($controllerList))
{

    foreach ($controllerList as $controller)
    {
        echo '<div class="block">';
        echo "<h3>" . $html->link($controller, array('action' => 'admin_acl_controllers', $controller)) . " Access Control</h3>";
        echo "</div>";

    }
}
if(isset($groups))
{
    echo $form->create(null, array('url' => '/admin/admin/acl_controllers/' . $ctlName));
?>
<table>
<?
    foreach ($groups as $group)
    {
        $trActions = array();

        foreach ($actions as $action)
        {
            $opts = array('label' => $action, 'type' => 'checkbox');
            $checked = $perms[$group['Group']['id']][$action] ? $opts['checked'] = 'checked' : '';

        	$trActions[] = $form->input('' . $group['Group']['id'] .'][' . $action . '', $opts);
        }


        $tr = array($group['Group']['title'], implode(' ', $trActions));
        echo $html->tableCells($tr, array('class' => 'odd'), array('class' => 'even'));
    }
?>
</table>
<?
    echo $form->end('Go!');
}
