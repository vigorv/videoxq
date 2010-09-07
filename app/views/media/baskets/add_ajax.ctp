<?php
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
if ($this->data['Basket']['type'] == 'variant')
    $id = 'variant_' . $this->data['Basket']['object_id'];
else
    $id = 'file_' . $this->data['Basket']['film_variant_id'] . '_' . $this->data['Basket']['object_id'];
if (!empty($this->data['Basket']['saved'])):
$delAction = "basket(" . $this->data['Basket']['object_id'] . ", '" . $this->data['Basket']['type'] . "', this);return false;";
echo $html->link(__('RemoveFromBasket', true), array('action' => 'delete', $this->data['Basket']['object_id'], $this->data['Basket']['type']), array('onclick' => $delAction, 'id' => $id), false, false);
else:
$delAction = "basket(" . $this->data['Basket']['object_id'] . ", '" . $this->data['Basket']['type'] . "', this);return false;";
echo $html->link(__('AddToBasket', true), array('action' => 'add', $this->data['Basket']['object_id'], $this->data['Basket']['type']), array('onclick' => $delAction, 'id' => $id), false, false);
endif;
?>
