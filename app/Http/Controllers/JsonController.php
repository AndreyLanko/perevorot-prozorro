<?php namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class JsonController extends BaseController
{
	public function platforms($type='all')
	{
		if(!in_array($type, ['all', 'contractors', 'type1', 'type2']))
			abort(404);

		$platforms=\Config::get('platforms');

		foreach($platforms as $k=>$item)
		{
			$platforms[$k]['logo']=env('ROOT_URL').'/assets/images/platforms/'.$item['slug'].'.png';

            if($item[$type])
            {
                $url=parse_url(!empty($item['public']) ? $item['public'] : $item['href']);
    
                $platforms[$k]['href']=$url['scheme'].'://'.$url['host'].$url['path'];
            }
            else
                unset($platforms[$k]);

            /*
			if($type=='contractors' && !$item['contractor'])
				unset($platforms[$k]);
			else
				unset($platforms[$k]['contractor']);
            */
		};

		return response()->json($platforms, 200, [
            'Content-Type' => 'application/json; charset=UTF-8',
            'charset' => 'UTF-8'
        ], JSON_UNESCAPED_UNICODE);
	}
}
