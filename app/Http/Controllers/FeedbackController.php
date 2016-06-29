<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\FeedbackRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FeedbackController extends Controller
{
    public function store(FeedbackRequest $request)
    {
        //dump($request->all());

        $url=env('WORKSECTION_URL').'/api/admin/';
        $action='post_task';
        $page='/project/'.env('WORKSECTION_PROJECT_ID').'/';
        
        $data=[
            'email_user_from'=>'',/**/
            'email_user_to'=>'',/**/
            'title'=>'',/**/
            'text'=>'',
            'action'=>$action,
            'page'=>$page,
            'hidden'=>'email,email',
            'subscribe'=>'email,email',
            'priority'=>'',
            'datestart'=>'DD.MM.YYYYY',
            'dateend'=>'DD.MM.YYYYY',
            'hash'=>md5($page.$action.env('WORKSECTION_KEY')),
        ];

        $query=$url.'?'.http_build_query($data);
        $header=get_headers($url)[0];

        if(strpos($header, '200 OK')!==false)
        {
            $ch=curl_init();
    
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, $query);
    
            $result=curl_exec($ch);
            curl_close($ch);

            if($result && $json=json_decode($result))
            {
                if(!empty($json->status) && $json->status=='error')
                {
                    return redirect()->back()->withErrors([
                        'api'=>'API error'
                    ]);
                }

                return redirect('/thanks/');
            }
        }

        return redirect()->back()->withErrors([
            'api'=>'API error'
        ]);
        //  /api/admin/?action=post_task&page=/project/PROJECT_ID/&email_user_from=USER_EMAIL&email_user_to=USER_EMAIL&hidden=USER_EMAIL,USER_EMAIL&subscribe=USER_EMAIL,USER_EMAIL&title=TASK_NAME&text=TASK_TEXT&priority=7&datestart=DD.MM.YYYYY&dateend=DD.MM.YYYYY&hash=HASH

    }
}
