<?php


namespace Database\Seeders\Live\Countries;


trait Cities
{
    public function getCityId($name)
    {
        $normal = iconv('utf8', 'ASCII//TRANSLIT', $name);


        if (in_array($normal, ['αθηνα'])) {
            return 1;
        }

        if (in_array($normal, ['θεσσαλονίκη'])) {
            return 34;
        }
    }
}
