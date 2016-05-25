<?php namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Input;
use App;
use Cache;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;
use Request;
use Redirect;
use Config;
use Session;

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

                    return redirect(!empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/');
                }
            }
        }
        
        $last=null;
        $auctions_items=null;
        
        /*   
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
                        if(!empty($one->auctionPeriod))
                            $active_auctions[]=$one;
                }

                $auctions_items=array_chunk(array_slice($active_auctions, 0, 9), 3);
            }

            return $auctions_items;
        });
        */
        
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
    
    function search_redirect()
    {
        return Redirect::to(str_replace('/search', '/tender/search', Request::fullUrl()), 301);
    }
    
    var $search_type;
    
    public function search($search_type='tender')
    {
        $this->search_type=$this->get_search_type($search_type);
        list($query_array, $preselected_values)=$this->parse_search_query();

        $result='';

        if(!empty($query_array))
        {
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
    
    public function parse_search_query()
    {
        $preselected_values=[];
        $query_array=[];
        $query_string=trim(Request::server('QUERY_STRING'), '&');

        $result='';

        if($query_string)
        {
            $query_array=explode('&', urldecode($query_string));

            $FormController=app('App\Http\Controllers\FormController');

            if(sizeof($query_array))
            {
                foreach($query_array as $item)
                {
                    $item=explode('=', $item);

                    if(empty($item[1]))
                       continue;
 
                    $source=$item[0];
                    $search_value=!empty($item[1]) ? $item[1] : null;

                    $value=$this->get_value($source, $search_value);
                    
                    if($value)
                        $preselected_values[$source][$search_value]=$value;
                    else
                        $preselected_values[$source][]=$search_value;
                }
            }
        }

        return [$query_array, $preselected_values];
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

        $item->__items=new \StdClass();
        $item->__items=array_where($item->additionalClassifications, function($key, $one){
            return $one->scheme!='КЕКВ';
        });

        $item->__items_kekv=new \StdClass();
        $item->__items_kekv=array_where($item->additionalClassifications, function($key, $one){
            return $one->scheme=='КЕКВ';
        });
        
        $this->get_procedure($item->tender);

        $this->parse_is_sign($item);

        return view('pages/plan')
                ->with('item', $item)
                ->with('html', $this->get_html())
                ->with('error', $error);
    }
    
    public function tender($id)
    {
        $dataStatus=[];

        foreach(app('App\Http\Controllers\FormController')->get_status_data() as $one)
            $dataStatus[$one['id']]=$one['name'];

        $item=$this->tender_parse($id);

        return view('pages/tender')
                ->with('item', $item)
                ->with('html', $this->get_html())
                ->with('back', starts_with(Request::server('HTTP_REFERER'), env('ROOT_URL').'/search') ? Request::server('HTTP_REFERER') : false)
                ->with('dataStatus', $dataStatus)
                ->with('error', $this->error);
    }

    var $error;

    public function tender_parse($id)
    {
        $this->search_type='tender';
        $this->error=false;

        $json=$this->getSearchResults(['tid='.$id]);
        $item=false;

        if($json)
        {
            $data=json_decode($json);

            if(empty($data->error))
            {
                if(!empty($data->items[0]))
                    $item=$data->items[0];
            }
            else
                $this->error=$data->error;

            if(!$item)
                $this->error='Тендер не найден'.(Config::get('api.__switcher')?', попробуйте другой API':'');

            if($this->error)
            {
                return view('pages/tender')
                    ->with('html', $this->get_html())
                    ->with('item', false)
                    ->with('error', $this->error);
            }
        }

        if(empty($item->procurementMethodType))
        {
            $item->procurementMethodType=new \StdClass();
            $item->procurementMethodType='';
        }
        
        $this->get_print_href($item);
        $this->get_multi_lot($item);
        $this->get_eu_lots($item);

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
            $tender_features=array_where($item->features, function($key, $feature) use ($item){
                return $feature->featureOf=='item' || $feature->featureOf=='tenderer' || (!empty($item->lots) && sizeof($item->lots)==1 && $feature->featureOf=='lot');
            });
        }

        if(!empty($tender_features))
        {
            foreach($tender_features as $k=>$feature)
            {
                $max=0;

                foreach($feature->enum as $one)
                    $max=max($max, floatval($one->value));

                $tender_features[$k]->max = new \stdClass();
                $tender_features[$k]->max=$max;

                $features_price-=$max;

                usort($feature->enum, function ($a, $b)
                {
                    return strcmp($b->value, $a->value);
                });

                $tender_features[$k]->enum=$feature->enum;
            }

            $item->__features=$tender_features;
        }
        
        if(!empty($item->lots) && sizeof($item->lots)==1 && !empty($item->bids))
        {
            foreach($item->bids as $k=>$one)
            {
                $item->bids[$k]->value=new \StdClass();
                $item->bids[$k]->value=!empty($one->lotValues) ? head($one->lotValues)->value : 0;
            }
        }

        if(!$item->__isMultiLot)
        {
            if($features_price<1 && !empty($item->bids))
            {
                foreach($item->bids as $k=>$bid)
                {
                    $item->bids[$k]->__featured_coef=new \StdClass();
                    $item->bids[$k]->__featured_coef=null;
    
                    $item->bids[$k]->__featured_price=new \StdClass();
                    $item->bids[$k]->__featured_price=null;
    
                    if(!empty($bid->parameters))
                    {
                        $featured_coef=trim(number_format(1+array_sum(array_pluck($bid->parameters, 'value'))/$features_price, 10, '.', ' '), '.0');
     
                        $item->bids[$k]->__featured_coef=$featured_coef;
                        $item->bids[$k]->__featured_price=str_replace('.00', '', number_format($bid->value->amount/$featured_coef, 2, '.', ' '));
                    }
                }
            }
    
            if($features_price<1 && !empty($item->bids))
            {
                usort($item->bids, function ($a, $b)
                {
                    return floatval($a->__featured_price)>floatval($b->__featured_price);
                });
            }
            elseif(!empty($item->bids))
            {
                usort($item->bids, function ($a, $b) use ($features_price)
                {
                    return empty($a->value) || empty($b->value) || (floatval($a->value->amount)>floatval($b->value->amount));
                });
            }
        }

        $item->__features_price=new \StdClass();
        $item->__features_price=$features_price;        

        $this->parse_eu($item);

        $item->__icon=new \StdClass();
        $item->__icon=starts_with($item->tenderID, 'ocds-random-ua')?'pen':'mouse';

        $this->get_active_apply($item);
        $this->get_contracts($item);
        $this->get_signed_contracts($item);
        $this->get_initial_bids($item);
        $this->get_yaml_documents($item);
        $this->get_tender_documents($item);        
        $this->get_bids($item);        
        $this->get_awards($item);
        $this->get_uniqie_awards($item);
        $this->get_uniqie_bids($item);
        $this->get_claims($item);
        $this->get_complaints($item);
        $this->get_opened_questions($item);
        $this->get_opened_claims($item);
        $this->get_questions($item);
        $this->get_qualifications($item);
        $this->get_lots($item);
        $this->get_procedure($item);
        $this->get_open_title($item);
        $this->parse_is_sign($item);
        $this->get_cancellations($item);
        $this->get_action_url_singlelot($item);
        $this->get_auction_period($item);
        
        if(isset($_GET['dump']) && getenv('APP_ENV')=='local')
            dd($item);

        return $item;
    }
    
    public function get_auction_period(&$item)
    {
        if(!empty($item->lots) && sizeof($item->lots)==1 && !empty($item->lots[0]->auctionUrl) && empty($item->auctionPeriod))
        {
            $item->auctionPeriod=new \StdClass();
            $item->auctionPeriod=$item->lots[0]->auctionPeriod;
        }        
    }

    public function get_print_href(&$item)
    {
        $item->__print_href=new \StdClass();
        $item->__print_href=false;
        
        if(!empty($item->procurementMethodType))
        {
            if($item->procurementMethod=='open' && $item->procurementMethodType!='belowThreshold')
                $item->__print_href='open';
    
            if($item->procurementMethod=='limited' && $item->procurementMethodType!='reporting')
                $item->__print_href='limited';        
    
            if($item->procurementMethod=='limited' && $item->procurementMethodType=='reporting')
                $item->__print_href='limited-reporting';
        }
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
		if(!empty(Session::get('api_pmtype')))
        		$query[]='proc_type='.Session::get('api_pmtype');

        $url=Session::get('api_'.$this->search_type, Config::get('api.'.$this->search_type)).'?'.implode('&', $query);
        $url=!empty($url) ? $url : env('PROZORRO_API');

        if(isset($_GET['api']) && getenv('APP_ENV')=='local')
            dd($url);

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
    
    private function get_active_apply(&$item)
    {        
        $item->__is_apply=new \stdClass();
        $item->__is_apply=in_array($item->status, ['active.enquiries', 'active.tendering']);

        $platforms=[];

        if($item->procurementMethodType=='belowThreshold')
        {
            $platforms=array_where(Config::get('platforms'), function($key, $platform){
                return $platform['level2']==true;
            });
        }
        elseif($item->procurementMethodType!='belowThreshold' && $item->procurementMethodType!='reporting')
        {
            $platforms=array_where(Config::get('platforms'), function($key, $platform){
                return $platform['level4']==true;
            });
        }

        shuffle($platforms);

        $item->__is_apply_platforms=$platforms;
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
        $bidders_by_lot=[];
        $bid_by_bidders=[];

        if(!empty($item->bids))
        {
            foreach($item->bids as $bid)
                $bid_by_bidders[$bid->id]=0;
        }

        if(!empty($item->documents))
        {
            foreach($item->documents as $document)
            {
                if(pathinfo($document->title, PATHINFO_EXTENSION)=='yaml' && !empty($document->url))
                {
                    try
                    {
                        $yaml=Cache::remember('yaml_'.md5($document->url), 60, function() use ($document){
                            $yaml_file=file_get_contents($document->url);

                            return !empty($yaml_file) ? Yaml::parse($yaml_file) : [];
                        });
                        
                        if(!empty($yaml['timeline']['auction_start']['initial_bids']))
                        {
                            foreach($yaml['timeline']['auction_start']['initial_bids'] as $bid)
                            {
                                if(!empty($yaml['lot_id']))
                                    $bidders_by_lot[$yaml['lot_id']][$bid['bidder']]=$bid['amount'];

                                $bid_by_bidders[$bid['bidder']]=$bid['amount'];
                            }
                        }
                    }
                    catch (ParseException $e) {}
                }
            }
        }

        $item->__initial_bids=new \StdClass();
        $item->__initial_bids=$bid_by_bidders;

        $item->__initial_bids_by_lot=new \StdClass();
        $item->__initial_bids_by_lot=$bidders_by_lot;
    }
    
    private function get_action_url_singlelot(&$item)
    {
        if(!empty($item->lots) && sizeof($item->lots)==1 && !empty($item->lots[0]->auctionUrl))
        {
            $item->auctionUrl=new \StdClass();
            $item->auctionUrl=$item->lots[0]->auctionUrl;
        }
    }

    private function get_multi_lot(&$item)
    {
        $item->__isMultiLot=new \StdClass();
        $item->__isMultiLot=(!empty($item->lots) && sizeof($item->lots)>1);
    }
    
    private function get_opened_questions(&$item)
    {
        $item->__isOpenedQuestions=new \StdClass();
        $item->__isOpenedQuestions=false;

        if(!empty($item->__complaints_claims))
        {
            $claims=array_unique(array_pluck($item->__complaints_claims, 'status'));

            if(sizeof(array_intersect(['claim', 'ignored'], $claims)) > 0)
                $item->__isOpenedQuestions=true;
        }

        if(!$item->__isOpenedQuestions && !empty($item->questions))
        {
            $questions=array_where($item->questions, function($key, $question){
                return empty($question->answer);
            });

            if(sizeof($questions))
                $item->__isOpenedQuestions=true;
        }
    }

    private function get_opened_claims(&$item)
    {
        $item->__isOpenedClaims=new \StdClass();
        $item->__isOpenedClaims=false;

        if(!empty($item->__complaints_complaints))
        {
            $complaints=array_pluck($item->__complaints_complaints, 'status');

            if(sizeof(array_intersect(['pending', 'accepted', 'stopping'], $complaints)) > 0)
                $item->__isOpenedClaims=true;
        }
    }
    
    private function get_uniqie_awards(&$item)
    {
        $item->__unique_awards=new \StdClass();
        $item->__unique_awards=null;
    
        if(!empty($item->awards))
        {
            $ids=[];

            foreach($item->awards as $award)
            {
                foreach($award->suppliers as $supplier)
                {
                    array_push($ids, $supplier->identifier->id);
                }
            }

            $ids=array_unique($ids);
            $item->__unique_awards=sizeof($ids);
        }
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
                }
            }
        }

        if(!empty($item->__isMultiLot))
            $item->__active_award=null;
    }

    private function get_claims(&$item, $__type='tender', $return=false)
    {
        $__complaints_claims=[];
        
        foreach(['complaints', 'qualifications/complaints', 'awards/complaints'] as $type)
        {
            $path=explode('/', $type);

            if(!empty($item->$path[0]))
            {
                $array=$item->$path[0];
                $found_claims=[];

                if(sizeof($path)>1)
                {
                    foreach($array as $item_claim)
                    {
                        if(!empty($item_claim->$path[1]))
                            $found_claims=array_merge($found_claims, $item_claim->complaints);
                    }
                }
                else
                    $found_claims=$array;

                $data=array_where($found_claims, function($key, $claim) use($path, $item, $__type){
                    return $claim->type=='claim' && (empty($claim->questionOf) || ($claim->questionOf==$__type || ($claim->questionOf=='lot' && !$item->__isMultiLot)));
                });

                if($data)
                    $__complaints_claims=array_merge($__complaints_claims, $data);
            }

            if($__complaints_claims)
                $__complaints_claims=array_values($__complaints_claims);

            $__complaints_claims=array_where($__complaints_claims, function($key, $claim){
                return $claim->status!='draft';
            });        

            foreach($__complaints_claims as $k=>$claim)
            {
                $__complaints_claims[$k]->__documents_owner=empty($claim->documents)?false:array_where($claim->documents, function($key, $document){
                    return in_array($document->author, ['complaint_owner']);
                });

                $__complaints_claims[$k]->__documents_tender_owner=empty($claim->documents)?false:array_where($claim->documents, function($key, $document){
                    return in_array($document->author, ['tender_owner']);
                });
    
                $__complaints_claims[$k]->__status_name=trans('tender.complain_statuses.'.$claim->status);
            }

            if(!$return)
            {
                $item->__complaints_claims=new \StdClass();
                $item->__complaints_claims=$__complaints_claims;
            }
            else
                return $__complaints_claims;            
        }
    }

    private function get_complaints(&$item, $__type='tender', $return=false)
    {
        $__complaints_complaints=[];

        foreach(['complaints', 'qualifications/complaints', 'awards/complaints'] as $type)
        {
            $path=explode('/', $type);

            if(!empty($item->$path[0]))
            {
                $array=$item->$path[0];
                $found_complaints=[];

                if(sizeof($path)>1)
                {
                    foreach($array as $item_complaint)
                    {
                        if(!empty($item_complaint->$path[1]))
                            $found_complaints=array_merge($found_complaints, $item_complaint->complaints);
                    }
                }
                else
                    $found_complaints=$array;

                $data=array_where($found_complaints, function($key, $complaint) use($path, $item, $__type){
                    return $complaint->type=='complaint'&& (empty($complaint->questionOf) || ($complaint->questionOf==$__type || ($complaint->questionOf=='lot' && !$item->__isMultiLot)));
                });

                if($data)
                    $__complaints_complaints=array_merge($__complaints_complaints, $data);
            }
        }

        if(sizeof($__complaints_complaints))
        {
            foreach($__complaints_complaints as $k=>$complaint)
            {
                if(!empty($complaint->documents))
                {
                    $__complaints_complaints[$k]->__documents_owner=new \StdClass();
                    $__complaints_complaints[$k]->__documents_owner=array_where($complaint->documents, function($key, $document){
                        return $document->author=='complaint_owner';
                    });

                    $__complaints_complaints[$k]->__documents_reviewer=new \StdClass();
                    $__complaints_complaints[$k]->__documents_reviewer=array_where($complaint->documents, function($key, $document){
                        return in_array($document->author, ['aboveThresholdReviewers', 'reviewers']);
                    });
                }
            }
            
            $__complaints_complaints=array_values($__complaints_complaints);
        }

        foreach($__complaints_complaints as $k=>$complain)
        {
            $key=($item->procurementMethodType!='belowThreshold' ? '!' : '').'belowThreshold';

            $__complaints_complaints[$k]->__status_name=trans('tender.complaints_statuses.'.$key.'.'.$complain->status);
        }

        $__complaints_complaints=array_where($__complaints_complaints, function($key, $complain){
            return $complain->status!='draft';
        });        

        if(!$return)
        {
            $item->__complaints_complaints=new \StdClass();
            $item->__complaints_complaints=$__complaints_complaints;
        }
        else
            return $__complaints_complaints;
        
    }

    private function get_questions(&$item, $type='tender', $return=false)
    {
        if(!empty($item->questions))
        {
            $questions=array_where($item->questions, function($key, $question) use ($item, $type){
                return $question->questionOf==$type || !$item->__isMultiLot;
            });

            if(!$return)
            {
                $item->__questions=new \StdClass();
                $item->__questions=$questions;
            }
            else
                return $questions;
        }
        
        if($return)
            return [];
    }

    private function get_tender_documents(&$item, $type='tender')
    {
        if(!empty($item->documents))
        {            
            $item->__tender_documents=new \StdClass();

            if($type=='tender' && (empty($item->lots) || (!empty($item->lots) && sizeof($item->lots)==1)))
                $type=['tender', 'lot', 'item'];
            else
                $type=[$type];

            $item->__tender_documents=array_where($item->documents, function($key, $document) use ($type, $item){
                return $item->procurementMethodType=='' || in_array($document->documentOf, $type);
            });

            usort($item->__tender_documents, function ($a, $b)
            {
                return intval(strtotime($b->dateModified))>intval(strtotime($a->dateModified));
            });

            $ids=[];
            
            foreach($item->__tender_documents as $document)
            {
                if(in_array($document->id, $ids))
                {
                    $document->stroked=new \StdClass();
                    $document->stroked=true;
                }

                $ids[]=$document->id;
            }
            
            $item->__tender_documents_stroked=sizeof(array_where($item->__tender_documents, function($key, $document){
                return !empty($document->stroked);
            }))>0;
        }
    }

    private function get_uniqie_bids(&$item, $is_lot=false)
    {
        $item->__unique_bids=new \StdClass();
        $item->__unique_bids=null;

        if($is_lot)
            $bids=!empty($item->__bids)?$item->__bids:false;
        elseif(!empty($item->lots) && sizeof($item->lots)==1)
            $bids=!empty($item->bids)?$item->bids:false;
        else
            $bids=!empty($item->bids)?$item->bids:false;

        if(!empty($bids))
        {
            $bids=array_where($bids, function($key, $bid){
                return empty($bid->status) || !in_array($bid->status, ['deleted', 'invalid']);
            });
    
            $ids=[];

            foreach($bids as $award)
            {
                foreach($award->tenderers as $tenderer)
                {
                    array_push($ids, $tenderer->identifier->id);
                }
            }

            $ids=array_unique($ids);
            $item->__unique_bids=sizeof($ids);
        }
    }
    
    private function get_bids(&$item, $return=false)
    {
        if(!empty($item->bids))
        {
            if(in_array($item->status, ['active.pre-qualification', 'active.auction', 'active.pre-qualification.stand-still']))
            {
                if(!$return)
                    $item->__bids=null;
                else
                    return null;
                
            }
            elseif($item->procurementMethod=='open')
            {
                $item->__bids=new \StdClass();
                $item->__bids=[];

                if(!empty($item->bids))
                {
                    $bids=$item->bids;
                    
                    foreach($bids as $k=>$bid)
                    {
                        if(!empty($bid->documents))
                        {
                            $bids[$k]->__documents_public=new \StdClass();
                            $bids[$k]->__documents_public=[];
                            
                            $bids[$k]->__documents_confident=new \StdClass();
                            $bids[$k]->__documents_confident=[];
                            
                            $bids[$k]->__documents_public=array_where($bid->documents, function($key, $document){
                                return empty($document->confidentiality) || $document->confidentiality!='buyerOnly';
                            });
    
                            $bids[$k]->__documents_confident=array_where($bid->documents, function($key, $document){
                                return !empty($document->confidentiality) && $document->confidentiality=='buyerOnly';
                            });
                        }
                    }

                    if(!empty($bids))
                    {
                        $bids=array_where($bids, function($key, $bid){
                            return empty($bid->status) || !in_array($bid->status, ['deleted', 'invalid']);
                        });
                    }

                    if(!$return)
                        $item->__bids=$bids;
                    else
                        return $bids;
                }
            }
        }
    }
    
    private function get_qualifications(&$item, $return=false)
    {
        if(!empty($item->qualifications))
        {
            $__qualifications=[];

            $cnt=1;

            foreach($item->qualifications as $qualification)
            {
                $qualification->__name=new \StdClass();
                $qualification->__name='';

                $qualification->__documents=new \StdClass();
                $qualification->__documents=[];

                $qualification->__bid_documents=new \StdClass();
                $qualification->__bid_documents=[];

                $qualification->__bid_documents_public=new \StdClass();
                $qualification->__bid_documents_public=[];

                $qualification->__bid_documents_confident=new \StdClass();
                $qualification->__bid_documents_confident=[];

                if(starts_with($item->status, 'active.pre-qualification') || starts_with($item->status, 'active.auction') || starts_with($item->status, 'active.pre-qualification.stand-still'))
                    $qualification->__name='Учасник '.$cnt;

                if(!empty($item->bids))
                {
                    $item->bids=array_where($item->bids, function($key, $bid){
                        return empty($bid->status) || !in_array($bid->status, ['deleted', 'invalid']);
                    });
                }

                $bid=array_where($item->bids, function($key, $bid) use ($qualification){
                    return $qualification->bidID==$bid->id;
                });

                if(!empty($qualification->documents))
                    $qualification->__documents=$qualification->documents;

                if(!empty($bid))
                {
                    $bid=head($bid);

                    if(!empty($bid->tenderers[0]))
                        $qualification->__name=$bid->tenderers[0]->name;

                    $qualification->__bid_documents=!empty($bid->documents) ? $bid->documents : [];

                    $qualification->__bid_documents_public=array_where($qualification->__bid_documents, function($key, $document){
                        return empty($document->confidentiality) || $document->confidentiality!='buyerOnly';
                    });

                    $qualification->__bid_documents_confident=array_where($qualification->__bid_documents, function($key, $document){
                        return !empty($document->confidentiality) && $document->confidentiality=='buyerOnly';
                    });
                }

                array_push($__qualifications, $qualification);

                $cnt++;
            }

            if(!$return)
            {
                $item->__qualifications=new \StdClass();
                $item->__qualifications=$__qualifications;
            }
            else
                return $__qualifications;
        }   
    }
    
    private function get_lots(&$item)
    {
        if(!empty($item->lots) && sizeof($item->lots)>1)
        {
            $tender_bids=$this->get_bids($item, true);
            $parsed_lots=[];
            
            foreach($item->lots as $k=>$lot)
            {
                $lot=clone $lot;

                if(!empty($item->__eu_bids[$lot->id]))
                {
                    $lot->__eu_bids=new \StdClass();
                    $lot->__eu_bids=$item->__eu_bids[$lot->id];
                }
                
                $lot->procurementMethod=$item->procurementMethod;
                $lot->procurementMethodType=$item->procurementMethodType;

                if(!empty($item->__initial_bids_by_lot))
                {
                    if(!empty($item->__initial_bids_by_lot[$lot->id]))
                        $lot->__initial_bids=$item->__initial_bids_by_lot[$lot->id];
                }
                else
                    $lot->__initial_bids=$item->__initial_bids;

                $lot->__icon=new \StdClass();
                $lot->__icon=false;

                $lot->__items=new \StdClass();

                $lot->__items=array_where($item->items, function($key, $it) use ($lot){
                    return $it->relatedLot==$lot->id;
                });

                $lot->__questions=new \StdClass();
                $lot->__questions=array_where($this->get_questions($item, 'lot', true), function($key, $question) use ($lot){
                    return !empty($question->relatedItem) && $question->relatedItem==$lot->id;
                });

                $lot->__complaints_claims=new \StdClass();
                $lot->__complaints_claims=array_where($this->get_claims($item, 'lot', true), function($key, $claim) use ($lot){
                    return !empty($claim->relatedLot) && $claim->relatedLot==$lot->id;
                });

                $lot->__complaints_complaints=new \StdClass();
                $lot->__complaints_complaints=array_where($this->get_complaints($item, 'lot', true), function($key, $complaint) use ($lot){
                    return !empty($complaint->relatedLot) && $complaint->relatedLot==$lot->id;
                });

                if(!empty($item->documents))
                {
                    $lot->__tender_documents=new \StdClass();
                    $lot->__tender_documents=array_where($item->documents, function($key, $document) use ($lot){
                        return $document->documentOf=='lot' && $document->relatedItem==$lot->id;
                    });

                    usort($lot->__tender_documents, function ($a, $b)
                    {
                        return intval(strtotime($b->dateModified))>intval(strtotime($a->dateModified));
                    });
        
                    $ids=[];
 
                    foreach($lot->__tender_documents as $document)
                    {
                        if(in_array($document->id, $ids))
                        {
                            $document->stroked=new \StdClass();
                            $document->stroked=true;
                        }
        
                        $ids[]=$document->id;
                    }
                    
                    $lot->__tender_documents_stroked=sizeof(array_where($lot->__tender_documents, function($key, $document){
                        return !empty($document->stroked);
                    }))>0;                    
                }
                
                $lot->awards=new \StdClass();
                $lot->awards=[];

                if(!empty($item->awards))
                {
                    $lot->awards=array_where($item->awards, function($key, $award) use ($lot){
                        return !empty($award->lotID) && $award->lotID==$lot->id;
                    });
                }

                if(!empty($item->features))
                {
                    $tender_features=array_where($item->features, function($key, $feature) use ($lot){
                        return $feature->featureOf=='tenderer' || ($feature->featureOf=='lot' && $feature->relatedItem==$lot->id);
                    });
                }

                $features_price=1;
                
                if(!empty($tender_features))
                {
                    foreach($tender_features as $k=>$feature)
                    {
                        $max=0;
        
                        foreach($feature->enum as $one)
                            $max=max($max, floatval($one->value));
        
                        $tender_features[$k]->max = new \stdClass();
                        $tender_features[$k]->max=$max;
        
                        $features_price-=$max;
        
                        usort($feature->enum, function ($a, $b)
                        {
                            return strcmp($b->value, $a->value);
                        });
        
                        $tender_features[$k]->enum=$feature->enum;
                    }

                    $lot->features=new \StdClass();
                    $lot->features=$tender_features;
                }

                $lot->__features_price=new \StdClass();
                $lot->__features_price=$features_price;
            
                if(!empty($tender_bids))
                {
                    $bids=array_where($tender_bids, function($key, $bid) use ($lot){
                        return !empty($bid->lotValues) && (!empty(array_where($bid->lotValues, function($k, $value) use ($lot){
                            return $value->relatedLot==$lot->id;
                        })));
                    });
                }

                if(!empty($tender_bids))
                {
                    $lot->__bids=new \StdClass();
                    $lot->__bids=[];
                    $lot->bids_values=new \StdClass();
                    $lot->bids_values=[];

                    foreach($tender_bids as $bid)
                    {
                        $lot_bid=false;

                        if(!empty($bid->lotValues))
                        {
                            $lot_bid=array_where($bid->lotValues, function($key, $value) use ($lot) {
                                return $value->relatedLot===$lot->id;
                            });
                        }
                        
                        if(!empty($lot_bid))
                        {
                            $bid_value=array_values($lot_bid)[0];

                            $bid->__featured_coef=new \StdClass();
                            $bid->__featured_price=new \StdClass();

                            if(!empty($bid->parameters))
                            {
                                $featured_coef=trim(number_format(1+array_sum(array_pluck($bid->parameters, 'value'))/$lot->__features_price, 10, '.', ' '), '.0');
             
                                $bid->__featured_coef=$featured_coef;
                                $bid->__featured_price=str_replace('.00', '', number_format($bid_value->value->amount/$featured_coef, 2, '.', ' '));
                            }

                            $bid->value=new \StdClass();
                            $bid->value=clone $bid_value->value;

                            $lot->__bids[]=clone $bid;
                        }
                    }

                    usort($lot->__bids, function ($a, $b)
                    {
                        return floatval($a->value->amount)<floatval($b->value->amount);
                    });
                }

                if(!empty($item->qualifications))
                {
                    $lot->__qualifications=new \StdClass();

                    $lot->__qualifications=array_where($this->get_qualifications($item, true), function($key, $qualification) use ($lot){
                        return !empty($qualification->lotID) && $qualification->lotID==$lot->id;
                    });
                }

                if(!empty($item->cancellations))
                {
                    $lot->__cancellations=new \StdClass();
    
                    $lot->__cancellations=array_where($item->cancellations, function($key, $cancellation) use ($lot){
                        return $cancellation->cancellationOf=='lot' && $cancellation->relatedLot==$lot->id;
                    });
                }

                $lot->tenderID=$item->tenderID;

                $this->get_uniqie_awards($lot);
                $this->get_uniqie_bids($lot, true);
                $this->get_awards($lot);

                $parsed_lots[]=$lot;
            }
            
            $item->lots=$parsed_lots;
        }
    }
    
    private function get_signed_contracts(&$item)
    {
        if(!empty($item->contracts))
        {
            $item->__signed_contracts=array_where($item->contracts, function($key, $contract){
                return !empty($contract->dateSigned);
            });
        }
    }
    
    private function get_contracts(&$item)
    {
        if(!empty($item->contracts))
        {
            $item->__contracts=new \StdClass();
            $documents=[];

            foreach($item->contracts as $contract)
            {
                if(!empty($contract->documents))
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
            }

            usort($documents, function ($a, $b)
            {
                $datea = new \DateTime($a->datePublished);
                $dateb = new \DateTime($b->datePublished);

                return $datea>$dateb;
            });

            $item->__documents=$documents;
        }
    }

    private function get_eu_lots(&$item)
    {
        if($item->procurementMethod=='open' && $item->procurementMethodType=='aboveThresholdEU')
        {
            if(!empty($item->bids))
            {
                if($item->__isMultiLot)
                {
                    $item->__eu_bids=new \StdClass();
                    $item->__eu_bids=[];
    
                    foreach($item->bids as $bid)
                    {
                        if(!empty($bid->documents))
                        {
                            $bid->__documents_public=new \StdClass();
                            $bid->__documents_public=[];
                            
                            $bid->__documents_confident=new \StdClass();
                            $bid->__documents_confident=[];
                            
                            $bid->__documents_public=array_where($bid->documents, function($key, $document){
                                return empty($document->confidentiality) || $document->confidentiality!='buyerOnly';
                            });
    
                            $bid->__documents_confident=array_where($bid->documents, function($key, $document){
                                return !empty($document->confidentiality) && $document->confidentiality=='buyerOnly';
                            });
                        }

                        $eu_bids=[];
                        
                        $lots=array_where($item->qualifications, function($key, $qualification) use ($bid){
                            return $qualification->bidID==$bid->id;
                        });
    
                        foreach($lots as $lot)
                        {
                            if(empty($item->__eu_bids[$lot->lotID]))
                                $item->__eu_bids[$lot->lotID]=[];
    
                            $item->__eu_bids[$lot->lotID][]=clone $bid;
                        }
                    }
                }
                else
                {
                    foreach($item->bids as $k=>$bid)
                    {
                        if(!empty($bid->documents))
                        {
                            $item->bids[$k]->__documents_public=new \StdClass();
                            $item->bids[$k]->__documents_public=[];

                            $item->bids[$k]->__documents_confident=new \StdClass();
                            $item->bids[$k]->__documents_confident=[];
                            
                            $item->bids[$k]->__documents_public=array_where($bid->documents, function($key, $document){
                                return empty($document->confidentiality) || $document->confidentiality!='buyerOnly';
                            });
    
                            $item->bids[$k]->__documents_confident=array_where($bid->documents, function($key, $document){
                                return !empty($document->confidentiality) && $document->confidentiality=='buyerOnly';
                            });
                        }
                    }

                    $item->__eu_bids=$item->bids;
                }
            }
        }
    }

    private function get_cancellations(&$item, $type='tender')
    {
        $item->__cancellations=new \StdClass();
        $item->__cancellations=null;

        if(!empty($item->cancellations))
        {
            $item->__cancellations=array_where($item->cancellations, function($key, $cancellation) use ($type, $item){
                return $cancellation->cancellationOf==$type || (!empty($item->lots) && sizeof($item->lots)==1 && $cancellation->cancellationOf=='lot');
            });
        }
    }

    private function get_yaml_documents(&$item)
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
            $html=file_get_contents(storage_path().'/framework/views/for_menu_'.App::getLocale().'.html');

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

			$popup=substr($html, strpos($html, '<section class="startpopup">'));
			$popup=substr($popup, 0, strpos($popup, '</section>')).'</section>';

			return [
				'header'=>$this->sanitize_html($header),
				'footer'=>$this->sanitize_html($footer),
				'popup'=>$this->sanitize_html($popup)
			];
		});
		
		return $html;
	}
    
    private function get_open_title(&$item)
    {
            $title=false;

            if($item->procurementMethod=='open' && $item->procurementMethodType!='belowThreshold')
                $title=1;

            if($item->procurementMethod=='open' && $item->procurementMethodType=='belowThreshold')
                $title=2;

            if($item->procurementMethod=='limited' && $item->procurementMethodType!='reporting')
                $title=3;

            if($item->procurementMethod=='limited' && $item->procurementMethodType=='reporting')
                $title=4;

        if($title)
        {
            $item->__open_name=new \StdClass();
            $item->__open_name=trans('tender.info_title.title'.$title);
        }
    }
        
    private function get_procedure(&$item)
    {
        if($item->procurementMethod=='open' && $item->procurementMethodType=='belowThreshold')
            $name='Допорогові закупівлі';

        if($item->procurementMethod=='open' && $item->procurementMethodType=='aboveThresholdUA')
            $name='Відкриті торги';

        if($item->procurementMethod=='open' && $item->procurementMethodType=='aboveThresholdEU')
            $name='Відкриті торги з публікацією англ.мовою';

        if($item->procurementMethod=='limited' && $item->procurementMethodType=='reporting')
            $name='Звіт про укладений договір';

        if($item->procurementMethod=='limited' && $item->procurementMethodType=='negotiation')
            $name='Переговорна процедура';

        if($item->procurementMethod=='limited' && $item->procurementMethodType=='negotiation.quick')
            $name='Переговорна процедура за нагальною потребою';

        if($item->procurementMethodType=='')
            $name='Без застосування електронної системи';

        if($item->procurementMethod=='open' && $item->procurementMethodType=='aboveThresholdUA.defense')
            $name='Відкриті торги (особливості оборони)';
            
        $item->__procedure_name=new \StdClass();
        $item->__procedure_name=$name;
    }

    private function parse_is_sign(&$item)
    {
        $item->__is_sign=new \StdClass();

        $is_sign=false;

        if(!empty($item->documents))
        {
            $is_sign=array_where($item->documents, function($key, $document){
                return $document->title=='sign.p7s';
            });
        }
        
        if($is_sign)
        {
            $url=head($is_sign)->url;
            $url=substr($url, 0, strpos($url, 'documents/')-1);

            $item->__sign_url=new \StdClass();
            $item->__sign_url=$url;
        }
        
        $item->__is_sign=!empty($is_sign);
    }
    
    private function parse_eu(&$item)
    {
        if(!empty($item->procurementMethod))
        {
            if($item->procurementMethod=='open' && $item->procurementMethodType=='aboveThresholdEU')
            {
                //if(in_array($item->status, ['active.pre-qualification', 'active.auction', 'active.pre-qualification.stand-still']))
                //    unset($item->bids);
            }
        }
        
    }

    private function sanitize_html($html)
    {
        $search = array('/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s');        
        $replace = array('>', '<', '\\1');
    
        $html = preg_replace($search, $replace, $html);
    
        return $html;
    }
}
