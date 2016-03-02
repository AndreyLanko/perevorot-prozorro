<?php namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Input;
use Cache;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;
use Request;
use Config;
use Session;
use TesseractOCR;

class PageController extends BaseController
{
	public function home()
	{
        if(env('APP_ENV')=='local' && Input::get())
        {
            if(Input::get())
            {
                $switch_to=array_keys(Input::get())[0];
                list($search_type, $new_api)=explode('-', $switch_to);
            }

			foreach(Config::get('api.__switcher.'.$search_type) as $api=>$url)
			{
				if($api==$new_api)
				{
					Session::set('api_'.$search_type, $url);
	
					return redirect('/');
				}
			}
		}
			
		$last=Cache::remember('get_last_homepage', 60, function()
		{
			return app('App\Http\Controllers\FormController')->getSearchResults([
				'procedure=open'
			]);
		});			

		$auctions_items=Cache::remember('get_last_auctions', 60, function()
		{
			$auctions=app('App\Http\Controllers\FormController')->getSearchResults([
				'status=active.auction'
			]);

			$auctions=json_decode($auctions);
			$auctions_items=false;
	
			if(!empty($auctions->items))
			{
        			$active_auctions=[];

        			foreach($auctions->items as $one)
        			{
            			if(!empty($one->auctionPeriod)/*  && strtotime($one->auctionPeriod->startDate)>time() */)
                			$active_auctions[]=$one;
                }

				$auctions_items=array_chunk(array_slice($active_auctions, 0, 9), 3);
            }

			return $auctions_items;
		});
		
		$dataStatus=[];

		foreach(app('App\Http\Controllers\FormController')->get_status_data() as $one)
			$dataStatus[$one['id']]=$one['name'];

		return view('pages/home')
				->with('html', $this->get_html())
                ->with('search_type', 'tender')
				->with('dataStatus', $dataStatus)
				->with('auctions', $auctions_items)
				->with('numbers', $this->parseBiNumbers(Config::get('bi-numbers')))
				->with('last', json_decode($last))->render();
	}

    var $search_type;
    
	public function search($search_type='tender')
	{
    	    $this->search_type=$this->get_search_type($search_type);
    	    
		$preselected_values=[];
		$query_string=trim(Request::server('QUERY_STRING'), '&');

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
            
            $FormController=app('App\Http\Controllers\FormController');
            $FormController->search_type=$this->search_type;
            
			$result=$FormController->getSearchResultsHtml($query_array);
		}

		return view('pages/search')
				->with('html', $this->get_html())
				->with('search_type', $this->search_type)
				->with('preselected_values', json_encode($preselected_values, JSON_UNESCAPED_UNICODE))
				->with('highlight', json_encode($this->getSearchResultsHightlightArray(trim(Request::server('QUERY_STRING'), '&')), JSON_UNESCAPED_UNICODE))
				->with('result', $result);
	}
	
	private function get_search_type($search_type='tender')
	{
        	return in_array($search_type, ['tender', 'plan'])?$search_type:'tender';
    	}
	
	public function plan($id)
	{
        $this->search_type='plan';
		$json=$this->getSearchResults(['pid='.$id]);

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
		
			if(!$item)
				$error='План не найден'.(Config::get('api.__switcher')?', попробуйте другой API':'');

			if($error)
			{
				return view('pages/plan')
					->with('html', $this->get_html())
					->with('item', false)
					->with('error', $error);
			}
		}

		return view('pages/plan')
				->with('item', $item)
				->with('html', $this->get_html())
				->with('error', $error);
    }
    
	public function tender($id)
	{
        $this->search_type='tender';
        
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
		
			if(!$item)
				$error='Тендер не найден'.(Config::get('api.__switcher')?', попробуйте другой API':'');

			if($error)
			{
				return view('pages/tender')
					->with('html', $this->get_html())
					->with('item', false)
					->with('error', $error);
			}
		}

		if(!empty($item->contracts[0]->documents))
		{
			$item->__contracts=new \StdClass();
			$documents=[];

			foreach($item->contracts as $contract)
			{
				foreach($contract->documents as $document)
				{
					if(!empty($contract->dateSigned))
					{
						$document->dateSigned=new \StdClass();
						$document->dateSigned=$contract->dateSigned;
					}

					$document->status=new \StdClass();
					$document->status=$contract->status;
					
					$documents[]=$document;
				}
			}

			usort($documents, function ($a, $b)
			{
				$datea = new \DateTime($a->datePublished);
				$dateb = new \DateTime($b->datePublished);

			    return $datea>$dateb;
			});

			$item->__documents=$documents;
		}

		if(!empty($item->awards))
		{
        		usort($item->awards, function ($a, $b)
        		{
        			$datea = new \DateTime($a->date);
        			$dateb = new \DateTime($b->date);
        
        		    return $datea>$dateb;
        		});
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

		if($features_price<1 && !empty($item->bids))
		{
			foreach($item->bids as $k=>$bid)
			{
				$item->bids[$k]->__featured_coef=new \StdClass();
				$item->bids[$k]->__featured_price=new \StdClass();
				
				$featured_coef=trim(number_format(1+array_sum(array_pluck($bid->parameters, 'value'))/$features_price, 10, '.', ' '), '.0');

				$item->bids[$k]->__featured_coef=$featured_coef;
				$item->bids[$k]->__featured_price=str_replace('.00', '', number_format($bid->value->amount/$featured_coef, 2, '.', ' '));
			}
		}

		if(!empty($item->bids))
		{
        		foreach($item->bids as $k=>$bid)
        		{
        			if(!empty($bid->documents))
        			{
        				$item->bids[$k]->__documents_before=new \StdClass();
        				$item->bids[$k]->__documents_before=[];
    
        				$item->bids[$k]->__documents_after=new \StdClass();
        				$item->bids[$k]->__documents_after=[];
        				
        				foreach($bid->documents as $document)
        				{
            				$what=strtotime($document->datePublished) > strtotime($item->tenderPeriod->endDate) ? '__documents_after' : '__documents_before';
    
                        array_push($item->bids[$k]->$what, $document);
                    }
                }
        		}
        }
    		
		$platforms=Config::get('platforms');
		shuffle($platforms);

		$dataStatus=[];

		foreach(app('App\Http\Controllers\FormController')->get_status_data() as $one)
			$dataStatus[$one['id']]=$one['name'];

		if($features_price<1 && !empty($item->bids))
		{
			usort($item->bids, function ($a, $b) use ($features_price)
			{
			    return floatval($a->__featured_price)>floatval($b->__featured_price);
			});
		}
		elseif(!empty($item->bids))
		{
			usort($item->bids, function ($a, $b) use ($features_price)
			{
			    return floatval($a->value->amount)>floatval($b->value->amount);
			});
		}

		$item->__icon=new \StdClass();
		$item->__icon=starts_with($item->tenderID, 'ocds-random-ua')?'pen':'mouse';

		$item->is_active_proposal=new \stdClass();
		$item->is_active_proposal=in_array($item->status, ['active.enquiries', 'active.tendering']);

        $this->get_initial_bids($item);
        $this->get_documents($item);
        $this->get_awards($item);

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
		$url=Session::get('api_'.$this->search_type, Config::get('api.'.$this->search_type)).'?'.implode('&', $query);

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
		$lang=Config::get('locales.current');

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
	
	private function get_initial_bids(&$item)
	{
        $bid_by_bidders=[];
        
    	    if(!empty($item->bids))
    	    {
        	    foreach($item->bids as $bid)
            	    $bid_by_bidders[$bid->id]=0;
        }

    	    if(!empty($item->documents))
    	    {
        	    $already_found=false;
        	    
        	    foreach($item->documents as $document)
        	    {
            	    if(pathinfo($document->title, PATHINFO_EXTENSION)=='yaml' && !empty($document->url))
            	    {
                	    if(!$already_found)
                	    {
                    	    try
                    	    {
                        	    $yaml=Cache::remember('yaml_'.md5($document->url), 60, function() use ($document){
                            	    $yaml_file=@file_get_contents($document->url);

                            	    return !empty($yaml_file) ? Yaml::parse($yaml_file) : [];
                            });
                        	    
                        	    if(!empty($yaml['timeline']['auction_start']['initial_bids']))
                        	    {
                            	    $already_found=true;
                            	    
                            	    foreach($yaml['timeline']['auction_start']['initial_bids'] as $bid)
                                	    $bid_by_bidders[$bid['bidder']]=$bid['amount'];
                            }
                        }
                        catch (ParseException $e) {}
                    }
            	    }
            }
        }

        $item->__initial_bids=new \StdClass();
        $item->__initial_bids=$bid_by_bidders;
    	}
    	
    	private function get_awards(&$item)
    	{
        $item->__active_award=new \StdClass();
        $item->__active_award=null;
        
        	if(!empty($item->awards))
        	{
            	foreach($item->awards as $award)
            	{
                	if($award->status=='active')
                	{
                    	$item->__active_award=$award;
                    	
                    	$item->__bid_price=new \StdClass();
                    	
                    	foreach($item->bids as $bid)
                    	{
                        	if($bid->id==$item->__active_award->bid_id)
                        	{
                            	$item->__active_award->value=new \StdClass();
                            	$item->__active_award->value=$bid->value;
                        }
                    }
                }
            }
        }
    }
    
    	private function get_documents(&$item)
    	{
        	if(!empty($item->documents))
        	{
            	$yaml_files=[];

            	foreach($item->documents as $k=>$document)
            	{
                	if(pathinfo($document->title, PATHINFO_EXTENSION)=='yaml')
                	{
                    	array_push($yaml_files, $document);
                    	unset($item->documents[$k]);
                }
            }
            
            if(!sizeof($item->documents))
                $item->documents=null;
                
            $item->__yaml_documents=new \StdClass();
            $item->__yaml_documents=$yaml_files;
        }
    }
	
	private function get_html()
	{
		$html=Cache::remember('get_html_'.Config::get('locales.current'), 60, function()
		{
			$html=file_get_contents(Request::root().href('postachalniku'));
	
			$header=substr($html, strpos($html, '<nav class="navbar navbar-default top-menu">'));
			$header=substr($header, 0, strpos($header, '<div class="container switcher">'));
			$header=str_replace('current-menu-item', '', $header);

            $from_text='<ul class="language-chooser language-chooser-text qtranxs_language_chooser" id="qtranslate-chooser">';
            $to_text='<div class="qtranxs_widget_end"></div>';

            $from_pos=strpos($header, $from_text);
            $to_pos=strpos($header, $to_text);

			$header_nolangs=substr($header, 0, $from_pos).substr($header, $to_pos);
            $header=$header_nolangs;

			$footer=substr($html, strpos($html, '<nav class="navbar navbar-default footer">'));
			$footer=substr($footer, 0, strpos($footer, '</body>'));
			$footer=str_replace('current-menu-item', '', $footer);			 

			return [
				'header'=>$this->sanitize_html($header),
				'footer'=>$this->sanitize_html($footer)
			];
		});
		
		return $html;
	}

    private function sanitize_html($html)
    {
        $search = array('/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s');        
        $replace = array('>', '<', '\\1');
    
        $html = preg_replace($search, $replace, $html);
    
        return $html;
    }
}
