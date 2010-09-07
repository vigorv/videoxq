<div id="alphFaces">
    <ul>
    <?php
    //pr($this->params);
    foreach ($alphabet as $letter)
    {
        if (strtolower($this->params['pass'][0]) == strtolower($letter))
            echo '<li><strong>' . $letter . '</strong></li>';
        else
            echo '<li><a href="/people/letter/' . $letter . '">' . $letter . '</a></li>';
        if (strtolower($letter) == 'z')
            echo '</ul><ul>';
    }
    ?>
    </ul>
</div>

<div id="faces">
    <form class="searchName" action="/people/index" method="post">
        <input type="text" class="textInput" name="data[Person][search]">
        <input type="submit" class="button" value="Найти лицедея">
    </form>
    <div class="column">
            <h2><?= $this->params['pass'][0] ?></h2>

            <?php
            for ($col = 0; $col < 3; $col++ ):
            ?>
            <div class="letterColumn">
                <ul>
                <?php
                for ($row = 0; $row < ceil(count($people)/3); $row++):
                    $idx = $col + $row * 3;
                    if (isset($people[$idx])):
                    $name = $people[$idx]['Person']['name'] ? $people[$idx]['Person']['name'] : $people[$idx]['Person']['name_en'];
                ?>
                    <li><a href="/people/view/<?= $people[$idx]['Person']['id'] ?>"><?= $name ?></a></li>
                <?php
                    endif;
                endfor; ?>
                </ul>
            </div>
            <?php
            endfor;
            ?>
    </div>
    <div class="spacer"></div>
<div class="pages">
<?php echo $this->element('paging'); ?>
</div>
</div>
