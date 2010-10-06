<?php
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=" . date('Y-m-d_H_i_s') . '.txt');
        header("Content-Type: text/plain");
        header("Content-Transfer-Encoding: text");
        echo $data;
?>