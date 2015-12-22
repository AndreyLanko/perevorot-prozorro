<?php namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Input;
use Cache;
use Request;

class PageController extends BaseController
{
	var $api='http://prozorro.aws3.tk/search';

	public function home()
	{
		return view('pages/search');
	}

	public function search()
	{
		$preselected_values=[];
		$query_string=Request::server('QUERY_STRING');

		if($query_string)
		{
			$query_array=explode('&', $query_string);

			if(sizeof($query_array))
			{
				foreach($query_array as $item)
				{
					$item=explode('=', $item);

					$source=$item[0];
					$search_value=$item[1];
					
					$value=$this->get_value($source, $search_value);
					
					if($value)
						$preselected_values[$source][$search_value]=$value;
					else
						$preselected_values[$source][]=$search_value;
				}
			}
		}

		return view('pages/search')->with('preselected_values', json_encode($preselected_values, JSON_UNESCAPED_UNICODE));
	}
	
	private function get_value($source, $search_value)
	{
		$lang='uk';

		$data=[];

		if(!file_exists('./sources/'.$lang.'/'.$source.'.json'))
			return $data;

		$raw=json_decode(file_get_contents('./sources/'.$lang.'/'.$source.'.json'), TRUE);

		foreach($raw as $id=>$name)
		{
			array_push($data, [
				'id'=>$id,
				'name'=>$name
			]);
		}			

		foreach($data as $item)
		{
			if($item['id']==$search_value)
				return $item['name'];
		}
			
		
		return FALSE;
	}
	
	public function tender($id)
	{
		$json=$this->getSearchResults(['tid='.$id]);
		$item=false;
		$error=false;

		if($json)
		{
			$data=json_decode($json);

			if(empty($data->error))
			{
				if(!empty($data->items[0]))
				{
					$item=$data->items[0];
				}
			}
			else
				$error=$data->error;
		}

		return view('pages/tender')->with('item', $item)->with('error', $error);
	}

	public function getSearchResults($query)
	{
		//file_get_contents($this->api.'?'.implode('&', $query))
		$url=$this->api.'?'.implode('&', $query);
		$header=get_headers($url)[0];

		if(strpos($header, '200 OK')!==false)
		{
			$ch=curl_init();
	
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_URL, $url);
	
			$result=curl_exec($ch);
	
			curl_close($ch);
		}
		else
		{
			$result=json_encode([
				'error'=>$header
			], JSON_UNESCAPED_UNICODE);
		}

		return $result;
	}	
}
