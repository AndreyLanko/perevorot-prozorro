<?php
    $unit_codes=\Cache::remember('unit_codes', 60*24, function(){
        $u=file_get_contents('http://standards.openprocurement.org/unit_codes/recommended/uk.json');
        $out=[];

        if($u){
            $array=json_decode($u, true);
            
            foreach($array as $key=>$one)
            {
                $out[$key]=[
                    'symbol' => $one['symbol_uk'],
                    'name' => $one['name_uk']
                ];
            }
        }

        return $out;
    });

    return $unit_codes;
