                <table border="0" cellspacing="0" cellpadding="0" width="260">
                  <tbody>
                    <tr>
                      <td class="corner1" width="25"> </td>
                      <td class="border3"> </td>
                      <td class="corner2" id="c29" width="25"> </td>
                    </tr>
                    <tr>
                      <td class="border1"> </td>
                      <td>
<h3><?php __("Top"); ?> 10</h3>
<?php
$lang = Configure::read('Config.language');
$langFix = '';
if ($lang == _ENG_) $langFix = '_en';
foreach ($block_top_films as $post)
{
    echo '<p><a href="/media/view/' . $post['Film']['id'] . '">'
         . h($post['Film']['title' . $langFix]) . '</a></p>';
}
?>
                      </td>
                      <td class="border2"> </td>
                    </tr>
                    <tr>
                      <td class="corner3" width="25"> </td>
                      <td width="*" class="border4"> </td>
                      <td class="corner4" id="c210" width="25"> </td>
                    </tr>
                  </tbody>
                </table>
                <br />
<?php
$placeNamePrefix = '';
if ($isWS)
	$placeNamePrefix = 'WS';

$placeName = $placeNamePrefix . 'right2';
echo $BlockBanner->getBanner($placeName);
