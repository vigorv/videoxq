<?php
if(isset($groups))
{

    foreach ($groups as $group)
    {
        echo "<div class=\"block\">";
        echo "<h3>" . $group['Group']['title'] . "</h3>";
        echo "<h1>" . $html->link('View', array('action' => 'acl', $group['Group']['id'])). "</h1>";
        echo "</div>";
    }
}
if(isset($controllerList))
{
    ksort($controllerPerms);
    echo '<div class="block">';
    echo "<h3>" . $group['Group']['title'] . " Access Control</h3>";
    foreach ( $controllerPerms as $controller => $actions )
    {
        echo '<h3>' . $controller . ' ' . $html->link('Deny', array('action' => 'acl', $group['Group']['id'] . "/" . $controller . "/all/deny")) .
                                      ' / ' . $html->link(' Allow', array('action' => 'acl', $group['Group']['id'] . "/" . $controller . "/all/allow")) .  "</h3><ul>";
        foreach ( $actions as $key => $action )
        {
            if ($action == 1)
            {
                echo "<li>" . $key . " is <span class=\"success\">allowed</span> &nbsp;" . $html->link(' Deny', array('action' => 'acl',  $group['Group']['id'] . "/" . $controller . "/" . $key . "/deny")) . "</li>\n";
            }
            else
            {
                echo "<li>" . $key . " is denied &nbsp;" . $html->link(' Allow', array('action' => 'acl', $group['Group']['id'] . "/" . $controller . "/" . $key . "/allow")) . "</li>\n";
            }
        }
        echo "</ul>";
    }
    echo "</div>";
}
?>