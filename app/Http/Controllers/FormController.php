<?php namespace App\Http\Controllers;

use Cache;
use Input;

class FormController extends Controller
{
	var $blocks=[
		'cpv',
		'dkpp',
		'edrpou',
		'region',
		'procedure',
		'status',
		'tid'
	];

	public function search($type=false)
	{
		$out=0;

		if(Input::get('query') && in_array($type, $this->blocks))
		{
			$data_function='get_'.$type.'_data';
			$data=$this->$data_function();

			$query=strtolower(Input::get('query'));
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

	public function check($type=false)
	{
		$out=0;

		if(Input::get('query') && in_array($type, $this->blocks))
		{
			$data_function='get_'.$type.'_data';
			$data=$this->$data_function();
			
			$query=Input::get('query');

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

	private function json($source, $lang)
	{
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
	
	private function get_cpv_data($lang='uk')
	{
		return $this->json('cpv', $lang);
	}

	private function get_dkpp_data($lang='uk')
	{
		return $this->json('dkpp', $lang);
	}

	private function get_region_data($lang='uk')
	{
		return $this->json('region', $lang);
	}

	private function get_procedure_data($lang='uk')
	{
		return $this->json('procedure', $lang);
	}

	private function get_status_data($lang='uk')
	{
		return [
			['id'=>'1', 'name'=>'Статус 1'],
			['id'=>'2', 'name'=>'Статус 2'],
			['id'=>'3', 'name'=>'Статус 3'],
		];
	}

	private function get_tid_data($lang='uk')
	{
		return [
			['id'=>'1', 'name'=>'1'],
			['id'=>'2', 'name'=>'2'],
			['id'=>'3', 'name'=>'3'],
		];
	}

	private function get_edrpou_data($lang='uk')
	{
		return [
			['id'=>'11111', 'name'=>'Company name 1'],
			['id'=>'22222', 'name'=>'Company name 2'],
			['id'=>'33333', 'name'=>'Company name 3'],
		];
	}

}
