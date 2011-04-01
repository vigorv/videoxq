<?php
/**
 * Attachment Element
 *
 * Element listing associated Attachments of the View's Model
 * Add, delete (detach) an Attachment or substitute it's current file
 *
 * Copyright (c) 2007-2008 David Persson
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * PHP version 5
 * CakePHP version 1.2
 *
 * Requires Number Helper from the Cake Core Library to be loaded
 * Plus `$this->data` must be set
 *
 * Embed by inserting `echo $this->element('attachment')` into a form
 *
 * @author 		David Persson <davidpersson at qeweurope dot org>
 * @copyright 	David Persson <davidpersson at qeweurope dot org>
 * @package 	attm
 * @version 	0.41
 * @since 		0.40
 * @license 	http://www.opensource.org/licenses/mit-license.php The MIT License
 */
?>

<?php
$assocAlias = $this->model.'Attachment';
$id = uniqid();
?>

<h2>Присоединен к <?php echo $this->model; ?></h2>
<table>
    <tr>
        <th>Добавить/заменить файл</th>
        <th>Удалить</th>
    </tr>

    <tr class="altrow">
        <td>
            <?php echo $form->input($assocAlias.'.'.$id.'.file',array('label' => false,'type' => 'file')); ?>
        </td>
        <td />
    </tr>
    <?php
    if(!empty($this->data[$assocAlias])) {
        pr($this->data[$assocAlias]);
        foreach($this->data[$assocAlias] as $item) {
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
    }
    ?>
</table>