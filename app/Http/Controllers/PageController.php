<?php namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Input;

class PageController extends BaseController
{
	var $api='http://aws3.tk/search';

	public function home()
	{
		return view('pages/search');
	}

	public function search()
	{
		return view('pages/search');
	}

	public function tender($id)
	{
		$json=$this->getSearchResults(['tid='.$id]);
		$item=false;

		if($json)
		{
			$data=json_decode($json);

			if(!empty($data->res->hits[0]))
			{
				$item=$data->res->hits[0];
			}
		}

		return view('pages/tender')->with('item', $item);
	}

	public function getSearchResults($query)
	{
		//file_get_contents($this->api.'?'.implode('&', $query))
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, $this->api.'?'.implode('&', $query));

		$result=curl_exec($ch);

		curl_close($ch);

		return $result;
	}	
}
