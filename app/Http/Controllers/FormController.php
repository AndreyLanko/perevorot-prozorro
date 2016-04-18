<?php namespace App\Http\Controllers;

use Cache;
use Input;
use View;
use Config;
use Session;
use Illuminate\Routing\Controller as BaseController;

class FormController extends BaseController
{
	public $blocks=[
		'cpv',
		'date',
		'dkpp',
		'edrpou',
		'region',
		'procedure',
		'status',
		'tid'
	];
	
	public $search_type='tender';

	public function search($search_type='tender')
	{
        	$this->search_type=$search_type;

		$html=$this->getSearchResultsHtml(Input::get('query'));

		return response()->json(($html ? [
			'html'=>$html,
			'highlight'=>app('App\Http\Controllers\PageController')->getSearchResultsHightlightArray(implode('&', Input::get('query')))
		] : []), 200, [
            'Content-Type' => 'application/json; charset=UTF-8',
            'charset' => 'UTF-8'
        ], JSON_UNESCAPED_UNICODE);
	}
	
	public function getSearchResultsHtml($query)
	{
		$out=false;

		if($query)
		{
			$json=$this->getSearchResults($query);
			$data=json_decode($json);

			if(!empty($data->items))
			{
        			$callback=camel_case('prepare_'.$this->search_type);

                return $this->$callback($data);
			}
			elseif(empty($data) || (property_exists($data, 'items') && is_array($data->items) && !sizeof($data->items)))
			{
				$out=View::make('pages.results')
					->with('error', trans('form.no_results'))->render();
			}
			elseif(!empty($data->error))
			{
				$out=View::make('pages.results')
					->with('error', !empty($data->error) ? $data->error : false)->render();
			}
		}

		return $out;
	}

    private function prepareTender($data)
    {
        $dataStatus=[];

		foreach($data->items as $k=>$item)
		{
			$item->__icon=new \StdClass();
			$item->__icon=starts_with($item->tenderID, 'ocds-random-ua')?'pen':'mouse';
			
			$data->items[$k]=$item;
		}
		
		foreach($this->get_status_data() as $one)
			$dataStatus[$one['id']]=$one['name'];

		$out=View::make('pages.results')
			->with('total', $data->total)
			->with('search_type', $this->search_type)
			->with('error', false)
			->with('dataStatus', $dataStatus)
			->with('start', ((int) Input::get('start') + Config::get('prozorro.page_limit')))
			->with('items', $data->items)->render();
        
        return $out;
    }

    private function preparePlan($data)
    {
		$out=View::make('pages.results')
			->with('total', $data->total)
			->with('search_type', $this->search_type)
			->with('error', false)
			->with('start', ((int) Input::get('start') + Config::get('prozorro.page_limit')))
			->with('items', $data->items)->render();
        
        return $out;
    }

	public function getSearchResults($query)
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		foreach($query as $k=>$q)
		{
			if(substr($q, 0, 4)=='cpv=')
			{
				$url=explode('=', $q, 2);
				$cpv=explode('-', $url[1]);

				$query[$k]=$url[0].'='.rtrim($cpv[0], '0');
			}
			elseif(substr($q, 0, 5)=='date[' || substr($q, 0, 9)=='dateplan[')
			{
        			if(strpos($q, 'dateplan')!==false)
                    $one_date=str_replace(['dateplan[', ']='], ['', '='], $q);
                else
        				$one_date=str_replace(['date[', ']='], ['', '='], $q);

				$one_date=preg_split('/(=|—)/', $one_date);

				if(sizeof($one_date)==3)
					$query[$k]=$one_date[0].'_start='.$this->convert_date($one_date[1]).'&'.$one_date[0].'_end='.$this->convert_date($one_date[2], new \DateInterval('P1D'));
				else
					unset($query[$k]);
			}
			else
			{
				$url=explode('=', $q, 2);

				if(!empty($url[1]))
    				    $query[$k]=$url[0].'='.str_replace([' '], ['+'], $url[1]);
    				else
    				    unset($query[$k]);
			}
		}

		$query[]='start='.Input::get('start');

		if(!empty(Session::get('api_pmtype')))
        		$query[]='procurementMethodType='.Session::get('api_pmtype');

		$path=Session::get('api_'.$this->search_type, Config::get('api.'.$this->search_type)).'?'.implode('&', $query);

        if(isset($_GET['api']) && getenv('APP_ENV')=='local')
            dd($path);

		curl_setopt($ch, CURLOPT_URL, $path);

		$result=curl_exec($ch);

		curl_close($ch);

		return $result;
	}

	private function convert_date($date, $add=false)
	{
		$out=new \DateTime($date);
		
		if($add)
			$out->add($add);
		
		return $out->format('Y-m-d');
	}

	public function autocomplete($type=false)
	{
		$out=0;

		if(Input::get('query') && in_array($type, $this->blocks))
		{
			$data_function='get_'.$type.'_data';
			$data=$this->$data_function();

			$query=mb_strtolower(Input::get('query'));
			$out=[];

			foreach($data as $one)
			{
				if(strpos(mb_strtolower($one['id']), $query)!==false || strpos(mb_strtolower($one['name']), $query)!==false)
				{
					array_push($out, [
						'id'=>$one['id'],
						'name'=>$one['name']
					]);
				}
			}
		}

		return response()->json($out, 200, [
            'Content-Type' => 'application/json; charset=UTF-8',
            'charset' => 'UTF-8'
        ], JSON_UNESCAPED_UNICODE);
	}

	public function check($search_type='tender', $type=false)
	{
		$out=0;

		if(Input::get('query') && in_array($type, $this->blocks))
		{
			$data_function='get_'.$type.'_data';
			$data=$this->$data_function();
			
			$query=mb_strtolower(Input::get('query'));

			foreach($data as $one)
			{
				if(strpos(mb_strtolower($one['id']), $query)!==false || strpos(mb_strtolower($one['name']), $query)!==false)
				{
					$out=1;
					break;
				}
			}
		}

		return response()->json($out, 200, [
            'Content-Type' => 'application/json; charset=UTF-8',
            'charset' => 'UTF-8'
        ], JSON_UNESCAPED_UNICODE);
	}

	public function data($type=false)
	{
		$out=false;

		if(in_array($type, $this->blocks))
		{
			$data_function='get_'.$type.'_data';

			$out=$this->$data_function();
		}

		return response()->json($out, 200, [
            'Content-Type' => 'application/json; charset=UTF-8',
            'charset' => 'UTF-8'
        ], JSON_UNESCAPED_UNICODE);			
	}

	private function json($source)
	{
        	$lang=Config::get('locales.current');
        	
		$data = Cache::remember('data_'.$source.'_'.$lang, 60, function() use ($lang, $source)
		{
			$raw=json_decode(file_get_contents('./sources/'.$lang.'/'.$source.'.json'), TRUE);
			$data=[];
			
			foreach($raw as $id=>$name)
			{
				array_push($data, [
					'id'=>$id,
					'name'=>$name
				]);
			}
			
		    return $data;
		});

		return $data;
	}
	
	private function get_cpv_data()
	{
		return $this->json('cpv');
	}

	private function get_dkpp_data()
	{
		return $this->json('dkpp');
	}

	private function get_region_data()
	{
		return $this->json('region');
	}

	private function get_procedure_data()
	{
		return $this->json('procedure');
	}

	public function get_status_data()
	{
		return $this->json('status');
	}

	private function get_tid_data()
	{
		return [
			['id'=>'1', 'name'=>'1'],
			['id'=>'2', 'name'=>'2'],
			['id'=>'3', 'name'=>'3'],
		];
	}

	private function get_edrpou_data()
	{
		return $this->json('edrpou');
	}
}