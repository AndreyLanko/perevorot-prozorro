<?php namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class PrintController extends BaseController
{
    var $types=[
        'welcome'
    ];
    
	public function index($tender_id, $type)
	{
		if(!in_array($type, $this->types))
			abort(404);

        $item=app('App\Http\Controllers\PageController')->tender_parse($tender_id);

        $pdf = \App::make('dompdf.wrapper');

        $pdf->loadView('pages/print', [
            'item'=>$item
        ]);
        
        return $pdf->stream();


        return view('pages/print')->with('item', $item);
	}
}
