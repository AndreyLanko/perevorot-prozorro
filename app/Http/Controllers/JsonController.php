<?php namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use Config;

class JsonController extends BaseController
{
    var $types=['all', 'tender', 'contractor', 'type1', 'type2', 'level1', 'level2', 'level3', 'level4'];
    
	public function platforms($type='all')
	{
		if(!in_array($type, $this->types))
			abort(404);

		$platforms=Config::get('platforms');

		foreach($platforms as $k=>$item)
		{
			$platforms[$k]['logo']=env('ROOT_URL').'/assets/images/platforms/'.$item['slug'].'.png';

            if($item[$type])
            {
                $url=parse_url(!empty($item['public']) ? $item['public'] : $item['href']);
    
                $platforms[$k]['href']=$url['scheme'].'://'.$url['host'].$url['path'];

                foreach($this->types as $t)
                    unset($platforms[$k][$t]);
            }
            else
                unset($platforms[$k]);
		};

		return response()->json($platforms, 200, [
            'Content-Type' => 'application/json; charset=UTF-8',
            'charset' => 'UTF-8'
        ], JSON_UNESCAPED_UNICODE);
	}
}
