<?php namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Input;
use Cache;
use Request;
use Config;
use TesseractOCR;

class PageController extends BaseController
{
	public function home()
	{
		//apt-get install tesseract-ocr
		/*
		$tesseract = new TesseractOCR(public_path('000001_qrrxD.png'));
		$tesseract->setLanguage('eng');
		dd($tesseract->recognize());
		*/
		
		return view('pages/home');
	}

	public function search()
	{
		$preselected_values=[];
		$query_string=Request::server('QUERY_STRING');
		$result='';

		if($query_string)
		{
			$query_array=explode('&', urldecode($query_string));

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

			$result=app('App\Http\Controllers\FormController')->getSearchResultsHtml($query_array);
		}

		return view('pages/search')
				->with('preselected_values', json_encode($preselected_values, JSON_UNESCAPED_UNICODE))
				->with('highlight', json_encode($this->getSearchResultsHightlightArray(Request::server('QUERY_STRING')), JSON_UNESCAPED_UNICODE))
				->with('result', $result);
	}
	
	public function getSearchResultsHightlightArray($query)
	{
		$query_string=$query;
		$highlight=[];

		if($query_string)
		{
			$query_array=explode('&', urldecode($query_string));

			if(sizeof($query_array))
			{
				foreach($query_array as $item)
				{
					$item=explode('=', $item);

					$source=$item[0];

					if($source=='query')
					{
						$search_value=$item[1];
						$highlight[]=$item[1];
						
						$value=$this->get_value($source, $search_value);					
						$highlight[]=$value;
					}
				}
			}

			$highlight=array_unique(array_filter($highlight));
		}		

		return $highlight;
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

		$platforms=Config::get('platforms');
		shuffle($platforms);

		$dataStatus=[];

		foreach(app('App\Http\Controllers\FormController')->get_status_data() as $one)
			$dataStatus[$one['id']]=$one['name'];

		return view('pages/tender')
				->with('item', $item)
				->with('back', starts_with(Request::server('HTTP_REFERER'), Request::root().'/search') ? Request::server('HTTP_REFERER') : false)
				->with('dataStatus', $dataStatus)
				->with('platforms', $platforms)
				->with('error', $error);
	}

	public function getSearchResults($query)
	{
		//file_get_contents($this->api.'?'.implode('&', $query))
		$url=Config::get('prozorro.API').'?'.implode('&', $query);
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
