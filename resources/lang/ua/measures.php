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
        
        if(empty($out['KWT'])){
            $out['KWT']=[
                'symbol' => 'кВт',
                'name' => 'кВт'
            ];
        }

        if(empty($out['K3'])){
            $out['K3']=[
                'symbol' => 'кВАр/год',
                'name' => 'кВАр/год'
            ];
        }
        

        return $out;
    });

    return $unit_codes;
