<?php

namespace App\Service;

use App\Exception\ApplicationException;

class FileServiceImpl implements FileService
{
    /**
     * @throws ApplicationException
     */
    public function batchProcessCsv(
        int $batchCount,
        string $path,
        \Closure $closure
    ): void {
        $row = 0;
        $results = [];
        $headers = [];

        try{
            // Get file resource
            if (($handle = fopen($path, "r")) !== false) {
                // read csv file line by line
                while (($data = fgetcsv($handle, 0)) != false) {
                    if (++$row == 1) {
                        // set first line as header
                        $headers = $data;
                        continue;
                    }
                    //map rows as header key to value pair i.e [['row' => 'http://localhost:1']]
                    $results = array_merge($results, array_map( function(string $rowItem, int $index) use ($headers) {
                        return [ $headers[$index] => $rowItem];
                    }, $data, array_keys($data)));

                    // send csv rows based on batch size
                    if ($row % $batchCount == 0) {
                        $closure($results);
                    }
                }

                if( $results ) {
                    $closure($results);
                }

                fclose($handle);
            }
        }catch(\Exception $e){
            throw
            new ApplicationException(sprintf("Error :: An error occurred while batch processing csv. Hint %s", $e->getMessage()));
        }
    }
}
