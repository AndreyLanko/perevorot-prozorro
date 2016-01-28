<?php namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Input;
use Cache;
use Request;
use Config;
use Session;
use TesseractOCR;

class PageController extends BaseController
{
	public function home()
	{
		/*
		if(!empty(Input::get('api')))
		{
			switch((int) Input::get('api'))
			{
				case 1:	Session::set('api', 'http://prozorro.aws3.tk/search'); break;
				case 2:	Session::set('api', 'http://ocds-test.aws3.tk/search'); break;
			}

			return redirect('/');			
		}
		*/

		$last=app('App\Http\Controllers\FormController')->getSearchResults([
			'procedure=open'
		]);

		$auctions=app('App\Http\Controllers\FormController')->getSearchResults([
			'status=active.auction'
		]);

		$auctions=json_decode($auctions);
		$auctions_items=false;

		if(!empty($auctions->items))
			$auctions_items=array_chunk(array_slice($auctions->items, 0, 9), 3);
		
		$dataStatus=[];

		foreach(app('App\Http\Controllers\FormController')->get_status_data() as $one)
			$dataStatus[$one['id']]=$one['name'];

		return view('pages/home')
				->with('html', $this->get_html())
				->with('dataStatus', $dataStatus)
				->with('auctions', $auctions_items)
				->with('numbers', $this->parseBiNumbers(Config::get('bi-numbers')))
				->with('last', json_decode($last));
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
				->with('html', $this->get_html())
				->with('preselected_values', json_encode($preselected_values, JSON_UNESCAPED_UNICODE))
				->with('highlight', json_encode($this->getSearchResultsHightlightArray(Request::server('QUERY_STRING')), JSON_UNESCAPED_UNICODE))
				->with('result', $result);
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

		$features_price=1;

		if(!empty($item->features))
		{
			foreach($item->features as $k=>$feature)
			{
				$max=0;
				
				foreach($feature->enum as $one)
					$max=max($max, floatval($one->value));

				$item->features[$k]->max = new \stdClass();
				$item->features[$k]->max=$max;

				$features_price-=$max;

				usort($feature->enum, function ($a, $b)
				{
				    return strcmp($b->value, $a->value);
				});

				$item->features[$k]->enum=$feature->enum;
			}
		}
		
		$platforms=Config::get('platforms');
		shuffle($platforms);

		$dataStatus=[];

		foreach(app('App\Http\Controllers\FormController')->get_status_data() as $one)
			$dataStatus[$one['id']]=$one['name'];

		if(!empty($item->bids))
		{
			usort($item->bids, function ($a, $b)
			{
			    return floatval($a->value->amount)>floatval($b->value->amount);
			});
		}
		
		$item->__icon=new \StdClass();
		$item->__icon=starts_with($item->tenderID, 'ocds-random-ua')?'pen':'mouse';

		$item->is_active_proposal=new \stdClass();
		$item->is_active_proposal=in_array($item->status, ['active.enquiries', 'active.tendering']);

		return view('pages/tender')
				->with('item', $item)
				->with('html', $this->get_html())
				->with('features_price', $features_price)
				->with('back', starts_with(Request::server('HTTP_REFERER'), Request::root().'/search') ? Request::server('HTTP_REFERER') : false)
				->with('dataStatus', $dataStatus)
				->with('platforms', $platforms)
				->with('error', $error);
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
	
	public function getSearchResults($query)
	{
		//$url=Session::get('api', Config::get('prozorro.API')).'?'.implode('&', $query);
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
	
	private function parseBiNumbers($numbers)
	{
		$out=Cache::remember('bi_numbers', 60, function() use ($numbers)
		{
			foreach($numbers as $name=>$number)
			{
				$data=file_get_contents($number);
	
				if(!empty($data))
				{
					$data=explode("\n", trim($data));

					$out[$name]=$data;
				}
				else
					$out[$name]=[0, ''];
			}

			return $out;
		});

		return $out;
	}
	
	private function get_html()
	{
		$html=file_get_contents(Request::root().'/postachalniku/');

		$header=substr($html, strpos($html, '<nav class="navbar navbar-default top-menu">'));
		$header=substr($header, 0, strpos($header, '<div class="container switcher">'));
		$header=str_replace('current-menu-item', '', $header);

		$footer=substr($html, strpos($html, '<nav class="navbar navbar-default footer">'));
		$footer=substr($footer, 0, strpos($footer, '</body>'));

		$html=Cache::remember('get_html', 1, function() use ($header, $footer)
		{
			return [
				'header'=>$header,
				'footer'=>$footer
			];
		});
		
		return $html;
	}
}
