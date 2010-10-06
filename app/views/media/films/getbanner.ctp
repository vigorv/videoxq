<?php
if (!empty($place))
{
	$BlockBanner->setIsWS($isWS);
	echo $BlockBanner->getBanner($place);
	echo $BlockBanner->getTail($dec);

}