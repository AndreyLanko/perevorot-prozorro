<?php namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class JsonController extends BaseController
{
	public function platforms($type='all')
	{
		if(!in_array($type, ['all', 'contractors']))
			abort(404);

		$platforms=\Config::get('platforms');

		foreach($platforms as $k=>$item)
		{
			$platforms[$k]['logo']=\Request::root().'/assets/images/platforms/'.$item['slug'].'.png';

			if($type=='contractors' && !$item['contractor'])
				unset($platforms[$k]);
			else
				unset($platforms[$k]['contractor']);
		};

		return response()->json($platforms, 200, [
            'Content-Type' => 'application/json; charset=UTF-8',
            'charset' => 'UTF-8'
        ], JSON_UNESCAPED_UNICODE);
	}
}
