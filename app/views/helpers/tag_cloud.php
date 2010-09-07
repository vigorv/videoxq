<?php
/*
     *  @author: Suhail Doshi
     */

class TagCloudHelper extends Helper
{

    /*
     *  @param array $dataSet Example: array('name' => 100, 'name2' => 200)
     *
     *  returns associative array.
     */
    public function formulateTagCloud($dataSet)
    {
        asort($dataSet); // Sort array accordingly.
        $data = array();

        // Retrieve extreme score values for normalization
        $minimumScore = intval(current($dataSet));
        $maximumScore = intval(end($dataSet));

        // Populate new data array, with score value and size.
        foreach ($dataSet as $tagName => $score)
        {
            $size = $this->getPercentSize($maximumScore, $minimumScore, $score);
            $data[$tagName] = array('score' => $score , 'size' => $size);
        }

        return $data;
    }

    /*
     *  @param int $maxValue Maximum score value in array.
     *  @param int $minValue Minimum score value in array.
     *  @param int $currentValue Current score value for given item.
     *  @param int [$minSize] Minimum font-size.
     *  @param int [$maxSize] Maximum font-size.
     *
     *  returns int percentage for current tag.
     */
    private function getPercentSize($maximumScore, $minimumScore, $currentValue, $minSize = 90, $maxSize = 200)
    {
        if ($minimumScore < 1)
            $minimumScore = 1;

        $spread = $maximumScore - $minimumScore;

        if ($spread == 0)
            $spread = 1;

        // determine the font-size increment, this is the increase per tag quantity (times used)
        $step = ($maxSize - $minSize) / $spread;
        // Determine size based on current value and step-size.
        $size = $minSize + (($currentValue - $minimumScore) * $step);
        return $size;
    }

    /*
     *  @param array $tags An array of tags (takes an associative array)
     *
     *  returns shuffled array of tags for randomness.
     */
    public function shuffleTags($tags)
    {
        if (count($tags) > 1)
        {
            $new = array();
            $keys = array_keys($tags);
            shuffle($keys);
            foreach ($keys as $key)
                $new[$key] = $tags[$key];

            $tags = $new;
        }
        return $tags;
    }

}
?>