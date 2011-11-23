<div class="MainPage">
<?php
echo $this->element('maina/paginate');
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
<script>
$(document).ready(function() {Visibility(["number_6", "number_9", "number_12", "number_24", "left", "right", "vid_eskiz", "vid_list"]);});
</script>
</div>