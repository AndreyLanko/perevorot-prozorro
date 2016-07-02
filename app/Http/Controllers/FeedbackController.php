<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\FeedbackRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FeedbackController extends Controller
{
    public function store(FeedbackRequest $request)
    {
                return redirect()->back()->withErrors([
                    'done'=>true
                ]);
                        $data=$request->input();

        $url=env('WORKSECTION_URL').'/api/admin/';
        $action='post_task';
        $page='/project/'.env('WORKSECTION_PROJECT_ID').'/';
        
        $data=[
            'email_user_from'=>'andriy.kucherenko@gmail.com',
            'email_user_to'=>'andriy.kucherenko@gmail.com',
            'title'=>trans('feedback.type.'.$data['type']).(!empty($data['id'])?' '.$data['id']:''),
            'text'=>sprintf('%s<br>%s<br><br><strong>URL</strong>: <a href="%s">%s</a><br><strong>Контактна особа</strong>: %s <%s> %s',
                $data['subject'],
                $data['message'],
                $request->server('HTTP_REFERER'),
                $request->server('HTTP_REFERER'),
                $data['name'],
                $data['email'],
                $data['phone']
            ),
            'action'=>$action,
            'page'=>$page,
            #'hidden'=>'email,email',
            #'subscribe'=>'email,email',
            #'priority'=>'',
            'datestart'=>date('d.m.Y'),
            #'dateend'=>'DD.MM.YYYYY',
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
                        'api'=>'Помилка підключення до API'
                    ]);
                }

                return redirect()->back()->withErrors([
                    'done'=>true
                ]);
            }
        }

        return redirect()->back()->withErrors([
            'api'=>'Помилка підключення до API'
        ]);
    }
}
