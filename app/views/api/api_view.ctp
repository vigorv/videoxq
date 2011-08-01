<vxq>
    <? foreach ($xml_data as $head => $xml): ?>
    <<?=$head;?>>
        <? foreach ($xml as $listed): ?>
            <item 
                <?foreach ($listed as $values):?>
                    <?foreach ($values as $key=>$value):?>
                    <?=$key;?>="<?=$value;?>" 
                <?endforeach;?>
                <?endforeach;?>>
            </item>
        <? endforeach; ?>   
            </<?=$head;?>>
    <? endforeach; ?>
</vxq>