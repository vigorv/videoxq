<?php
$html->css('excel','',array(),false);
?>
<?php
$outstr = '';
$outstr.='<div class="excel_table"><table class="" cellspacing="0">';
foreach($data as $k=>$row){
    $str='';
    if ($row && trim($row[1])!='' && $row[1]<20) {

//                foreach($row as $col){
//                    if (!$col) $col='&nbsp;';
//                    $str .= '<td>' . $col .'</td>';
//                }
        $str =  '<td>'.$row[1].'</td>'.
                '<td>'.$row[2].'</td>'.
                '<td>'.$row[11].'</td>'.
                '<td>'.$row[15].'</td>'.
                '<td>'.$row[16].'</td>';

        $str = '<tr><td style="color: #bbb">'.$k . '</td>' . $str.'</tr>';
        $outstr.=$str;
    }
    else break;
}
$outstr.='</table></div>';
echo $outstr;
?>
