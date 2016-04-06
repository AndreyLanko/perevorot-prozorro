<?php namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class PrintController extends BaseController
{
    var $types=[
        'open',
        'limited',
        'limited-reporting'
    ];
    
	public function index($tender_id, $type, $output)
	{
		if(!in_array($type, $this->types))
			abort(404);

        $item=app('App\Http\Controllers\PageController')->tender_parse($tender_id);

        if($item->__print_href!=$type)
            abort(404);

        if($output=='pdf')
        {
            $pdf=\App::make('dompdf.wrapper');
    
            $pdf->loadView('pages/print/tender/'.$type, [
                'item'=>$item
            ]);

            return $pdf->stream();
        }
        
        return view('pages/print/tender/'.$type)->with('item', $item);//;
	}
}
