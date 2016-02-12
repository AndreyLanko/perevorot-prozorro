<?php

return [

	[
		'name'=>'Public Bid',
		'slug'=>'pb',
		'href'=>'https://public-bid.com.ua/?TenderID={tenderID}',
		'contractor'=>true,
		'tender'=>true
	],
	[
		'name'=>'Smart Tender',
		'slug'=>'smart-tender',
		'href'=>'https://smarttender.biz/tenders?mode=gov&TenderID={tenderID}',
		'contractor'=>true,
		'tender'=>true		
	],
	[
		'name'=>'Newtend',
		'slug'=>'newtend',
		'href'=>'https://newtend.com/gos-zakupki/?TenderID={tenderID}',
		'contractor'=>true,
		'tender'=>true
	],
	[
		'name'=>'E-tender',
		'slug'=>'etender',
		'href'=>'http://e-tender.biz/?TenderID={tenderID}',
		'contractor'=>true,
		'tender'=>true
	],
	[
		'name'=>'ПриватМаркет<br>(тільки для учасників)',
		'slug'=>'privatmarket',
		'href'=>'https://privatmarket.ua/business/tenders?TenderID={tenderID}',
		'contractor'=>true,
		'tender'=>true
	],
	[
		'name'=>'Zakupki.prom.ua',
		'slug'=>'zakupki',
		'href'=>'http://zakupki.prom.ua/dz_redirect?TenderID={tenderID}',
		'contractor'=>true,
		'tender'=>true
	],
	[
		'name'=>'Держзакупівлі онлайн',
		'slug'=>'netcast',
		'href'=>'http://www.dzo.com.ua/?TenderID={tenderID}',
		'contractor'=>true,
		'tender'=>true
	],
	[
		'name'=>'zakupki.com.ua<br>(тільки для замовників)',
		'slug'=>'lpzakupki',
		'href'=>'http://lp.zakupki.com.ua/?TenderID={tenderID}',
		'contractor'=>true,
		'tender'=>false,
	]

];