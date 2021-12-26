<?php
declare(strict_types=1);

namespace App\Src\Ship\Containers\Helpers;

class Xmlhelper
{
    /**
     * @param string $data
     *
     * @return array
     */
    static public function stringToArray(string $data): array
    {
        $xml_data = simplexml_load_string($data);
        $json = json_encode($xml_data);

        return json_decode($json, true);
    }
}
