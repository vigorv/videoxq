<div class="MainPage">
<?php
if (!empty($favorites_data) && $favorites_data){
     if (!empty($userOptions['Profile.itemsView'])){
        switch ($userOptions['Profile.itemsView']){
        case 'list':
            $tvvision->list_view($favorites_data);
            break;
        default:
            $tvvision->Eskiz($favorites_data);
        }
     }
     else
        $tvvision->Eskiz($favorites_data);
}
?>
</div>