<?php

namespace App\modules;

class CSVCreator
{
    public function create($data)
    {
        $file = fopen("c:\data.csv", 'w');
        fwrite($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

        foreach ($data as $datum)
        {
            fputcsv($file, $datum, ';',',');
        }

        fclose($file);
    }
}