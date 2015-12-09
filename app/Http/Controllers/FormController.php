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
		'proceduretype',
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

	private function get_cpv_data($lang='uk')
	{		
		$data = Cache::remember('cpv_data', 60, function() use ($lang)
		{
			//$raw=json_decode(file_get_contents('http://standards.openprocurement.org/classifiers/cpv/'.$lang.'.json'), TRUE);
			$raw=json_decode(file_get_contents('./sources/'.$lang.'.json'), TRUE);
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

	private function get_dkpp_data()
	{
		return [
			['id'=>'1', 'name'=>'Продукція сільського господарства, мисливства та пов\'язані з цим послуги'],
			['id'=>'2', 'name'=>'Вугілля кам\'яне та буре вугілля (лігніт)'],
			['id'=>'3', 'name'=>'Продукти харчові'],
			['id'=>'4', 'name'=>'Енергія електрична, газ, пара та кондиційоване повітря'],
			['id'=>'5', 'name'=>'Вода природна; послуги щодо обробляння та постачання води'],
			['id'=>'6', 'name'=>'Будівлі та будування будівель'],
			['id'=>'7', 'name'=>'Послуги водного транспорту'],
			['id'=>'8', 'name'=>'Послуги повітряного транспорту'],
			['id'=>'9', 'name'=>'Спирт спирт спирт']
		];
	}

	private function get_region_data()
	{
		return [
			['id'=>'1', 'name'=>'Вінницька'],
			['id'=>'2', 'name'=>'Волинська'],
			['id'=>'3', 'name'=>'Дніпропетровська'],
			['id'=>'4', 'name'=>'Донецька'],
			['id'=>'5', 'name'=>'Житомирська'],
			['id'=>'6', 'name'=>'Закарпатська'],
			['id'=>'7', 'name'=>'Запорізька'],
			['id'=>'8', 'name'=>'Івано-Франківська'],
			['id'=>'9', 'name'=>'Київська'],
			['id'=>'10', 'name'=>'Кіровоградська'],
			['id'=>'11', 'name'=>'Луганська'],
			['id'=>'12', 'name'=>'Львівська'],
			['id'=>'13', 'name'=>'Миколаївська'],
			['id'=>'14', 'name'=>'Одеська'],
			['id'=>'15', 'name'=>'Полтавська'],
			['id'=>'16', 'name'=>'Рівненська'],
			['id'=>'17', 'name'=>'Сумська'],
			['id'=>'18', 'name'=>'Тернопільська'],
			['id'=>'20', 'name'=>'Харківська'],
			['id'=>'21', 'name'=>'Херсонська'],
			['id'=>'22', 'name'=>'Хмельницька'],
			['id'=>'23', 'name'=>'Черкаська'],
			['id'=>'24', 'name'=>'Чернівецька'],
			['id'=>'25', 'name'=>'Чернігівська'],
			['id'=>'26', 'name'=>'Київ']
		];
	}

	private function get_proceduretype_data()
	{
		return [
			['id'=>'1', 'name'=>'Procedure type 1'],
			['id'=>'2', 'name'=>'Procedure type 2'],
			['id'=>'3', 'name'=>'Procedure type 3'],
		];
	}

	private function get_status_data()
	{
		return [
			['id'=>'1', 'name'=>'Статус 1'],
			['id'=>'2', 'name'=>'Статус 2'],
			['id'=>'3', 'name'=>'Статус 3'],
		];
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
		return [
			['id'=>'11111', 'name'=>'Company name 1'],
			['id'=>'22222', 'name'=>'Company name 2'],
			['id'=>'33333', 'name'=>'Company name 3'],
		];
	}

}
