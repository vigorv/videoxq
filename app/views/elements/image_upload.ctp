<?php
$assocAlias = $this->model;// . 'Attachment';
//$id = uniqid();
?>
<table>
    <tr>
        <th>Добавить/заменить файл</th>
        <th>Удалить</th>
    </tr>
    <?php
    if(!empty($this->data['Attachment']['id'])) {
       $item = $this->data['Attachment'];
    ?>
    <tr>
        <td>
            <?php echo $form->input($assocAlias.'.'.$item['id'].'.id',array('value' => $item['id'])) ?>
            <?php echo $item['basename'].' ('.$item['mediatype'].'/'.$number->toReadableSize($item['size']).')'; ?>
            <?php echo $form->input($assocAlias.'.'.$item['id'].'.file',array('label' => false,'type' => 'file'));?>
        </td>
        <td><?php echo $form->input($assocAlias.'.'.$item['id'].'.delete',array('label' => false,'type' => 'checkbox')); ?></td>
    </tr>
    <?php
    }
    else
    {
        ?>
    <tr class="altrow">
        <td>
            <?php echo $form->input($assocAlias.'.filename',array('label' => false,'type' => 'file')); ?>
        </td>
        <td />
    </tr>

        <?php
    }
    ?>
</table>