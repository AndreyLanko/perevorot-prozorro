<?php namespace App\Http\Controllers;

use Cache;
use Input;

class OldJsonController extends Controller
{
	var $max_word_length=3; //минимальная длина слова в классификаторе для поиска
	var $max_results_by_letter=30; //максимальная количество кодов в выдаче по одному сегменту
	var $max_cache_word_length=FALSE; //максимальное длина слова для кеша. FALSE для всех слов

	public function combined()
	{
		return response()->json([
			'raw'=>$this->raw(),
			'index'=>$this->parsed(),
		], 200, [
            'Content-Type' => 'application/json; charset=UTF-8',
            'charset' => 'UTF-8'
        ], JSON_UNESCAPED_UNICODE);
	}
	
	public function raw()
	{
		$json=$this->getClassifiersJson('uk');
		$out=[];
		
		foreach($json as $id=>$name)
		{
			$out[]=[
				'id'=>$id,
				'name'=>$name
			];
		}

		return response()->json($out, 200, [
            'Content-Type' => 'application/json; charset=UTF-8',
            'charset' => 'UTF-8'
        ], JSON_UNESCAPED_UNICODE);
	}

	public function parsed()
	{
		$json=$this->getClassifiersJson('uk');

		$index_by_code=[];
		$index=[];
		$max=0;
		$longest_word='';

		foreach($json as $code=>$one)
		{			
			$words=explode(' ', preg_replace('/\P{L}+/u', ' ', $one));

			foreach($words as $i=>$word)
			{
				if(mb_strlen(trim($word))<=$this->max_word_length)
					unset($words[$i]);
			}

			$words=array_values($words);

			foreach($words as $i=>$word)
			{
				if($max<mb_strlen($word))
				{
					$max_len=mb_strlen($word);
					$max=$max_len;
					$longest_word=$word;
				}
				
				if(empty($index[$i]))
					$index[$i]=[];

				$words_by_code[$i][$code]=mb_strtolower(trim($word));
			}
		}

		$out=[];

		for($i=1;$i<=$this->getCacheWordLength($max_len);$i++)
			$out[]=$this->parseByChars($words_by_code, $i);

		//dd($out);
		//dd(mb_strlen(json_encode($out, JSON_UNESCAPED_UNICODE)));

		return $out;
	}
	
	private function parseByChars($words_by_code, $depth)
	{
		$array=[];
		
		foreach($words_by_code as $position=>$words)
		{
			foreach($words as $code=>$word)
			{
				if(mb_strlen($word)>=$depth)
				{
					$part=mb_substr($word, 0, $depth);
	
					if(empty($array[$part]))
						$array[$part]=[];
				
					if(sizeof($array[$part])<$this->max_results_by_letter)
						$array[$part][]=$code;
				}
			}
		}

		ksort($array);

		return $array;
	}
	
	private function getCacheWordLength($max_len)
	{
		return $this->max_cache_word_length?$this->max_cache_word_length:$max_len;
	}
	
	private function getClassifiersJson($lang)
	{
		$json = Cache::remember('standarts_raw', 60, function() use ($lang)
		{
		    return json_decode(file_get_contents('http://standards.openprocurement.org/classifiers/cpv/'.$lang.'.json'), TRUE);
		});

		return $json;	
	}
}
