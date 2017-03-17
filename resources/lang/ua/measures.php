<?php
    //\Cache::forget('unit_codes');
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

        if(empty($out['D03'])){
            $out['D03']=[
                'symbol' => 'кіловат/годин',
                'name' => 'кіловат/годин'
            ];
        }
        
        if(empty($out['JR'])){
            $out['JR']=[
                'symbol' => 'шт.',
                'name' => 'шт.'
            ];
        }        

        if(empty($out['IE']['symbol'])){
            $out['IE']=[
                'symbol' => 'людей',
                'name' => 'люд.'
            ];
        }
        
        if(empty($out['AM']['symbol'])){
            $out['AM']=[
                'symbol' => 'ампул',
                'name' => 'ампул'
            ];
        }

        if(empty($out['K51']['symbol'])){
            $out['K51']=[
                'symbol' => 'ККал',
                'name' => 'ККал'
            ];
        }

        if(empty($out['RO']['symbol'])){
            $out['RO']=[
                'symbol' => 'рул.',
                'name' => 'рул.'
            ];
        }
        
        return $out;
    });

    return $unit_codes;
