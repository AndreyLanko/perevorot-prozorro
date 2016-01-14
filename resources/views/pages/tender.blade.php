
		
@extends('layouts/app')

@section('content')

{{--@include('partials/form')--}}

@if ($item && !$error)
	{{--dump($item)--}}
	<div class="tender" data-js="tender">
		<div class="tender--head gray-bg">
			<div class="container">
				<div class="tender--head--title col-sm-9">{{$item->title}}</div>
	
				{{--
				<div class="breadcrumb_custom clearfix">
					<a href="#" class="disable"><strong>Створена:</strong> 27.07 (сб)</a>
					<a href="#" class="active"><strong>Уточнення:</strong> до 29.06 (пн)</a>
					<a href="#"><strong>Пропозиції:</strong> до 6.07 (пт)</a>
					<a href="#"><strong>Аукціон:</strong> 12.07 (пт)</a>
					<a href="#"><strong>Кваліфікаця:</strong> з 15.07 (пн)</a>
				</div>
				--}}
				<div class="col-md-3 col-sm-3 tender--description--cost--wr">
					@if (!empty($item->value))
					<div class="gray-bg padding margin-bottom tender--description--cost">
						Очікувана вартість
						<div class="green tender--description--cost--number">
							<strong>{{number_format($item->value->amount, 0, '', ' ')}} <span class="small">{{$item->value->currency}}</span></strong>
						</div>
					</div>
					@endif
				</div>
					
				<div class="row">
					<div class="col-sm-9">
						@if (!empty($item->procuringEntity->name))
							<div class="tender--head--company">{{$item->procuringEntity->name}}</div>
						@endif
						<div class="tender--head--inf">Prozorro   <span class="marked">{{$dataStatus[$item->status]}}</span>   @if (!empty($item->procuringEntity->address->locality)){{$item->procuringEntity->address->locality}}@endif</div>
					</div>
					
					<div class="tender_menu_fixed" data-js="tender_menu_fixed">
						<div class="col-sm-3 tender--menu">
							@if($back)
								<a href="{{$back}}" class="back-tender"><i class="sprite-arrow-left"></i> Повернутися до результатів</a>
							@endif
							<div class="clearfix"></div>
							<a href="" class="blue-btn">Подати пропозицію</a>
							{{--
							<ul class="nav nav-list">
								<li>
									<a href="#"><i class="sprite-star"></i> Зберегти у кабінеті</a>
								</li>
								<li>
									<a href="#"><i class="sprite-close-blue"></i> Не цікаво(не відображати)</a>
								</li>
								<li>
									<a href="#"><i class="sprite-warning"></i> Поскаржитись</a>
								</li>
							</ul>
							--}}
							<ul class="nav nav-list last">
								{{--
								<li>
									<a href="#"><i class="sprite-print"></i> Роздрукувати</a>
								</li>
								<li>
									<a href="#"><i class="sprite-download"></i> Зберегти як PDF</a>
								</li>
								--}}
								@if ($item->status=='active.enquiries')
									Аукціон буде запланований після {{date('d.m.Y', strtotime($item->tenderPeriod->startDate))}}
								@elseif(in_array($item->status, ['active.tendering', 'active.auction', 'active.qualification', 'active.awarded', 'unsuccessful', 'cancelled', 'complete']) && !empty($item->auctionUrl))
									<li>
										<a href="{{$item->auctionUrl}}" target="_blank"><i class="sprite-hammer"></i> Перейти на аукціон</a>
										@if(in_array($item->status, ['active.tendering', 'active.auction']))
											<p class="tender-date">Запланований на {{date('d.m.Y H:i', strtotime($item->auctionPeriod->startDate))}}</p>
										@elseif(in_array($item->status, ['active.qualification', 'active.awarded', 'unsuccessful', 'cancelled', 'complete']) && !empty($item->auctionPeriod->endDate))
											<p class="tender-date">Завершений {{date('d.m.Y H:i', strtotime($item->auctionPeriod->endDate))}}</p>
										@elseif(in_array($item->status, ['active.qualification', 'active.awarded', 'unsuccessful', 'cancelled', 'complete']))
											<p class="tender-date">Аукціон не проводився</p>
										@endif
									</li>
								@endif

								@if (!empty($item->bids))
									<li>
										<a href="" class="tender--offers--ancor"><i class="sprite-props"></i> Отримані пропозиції</a>
									</li>
								@endif

								<li>
									<a href=""><i class="sprite-share"></i> Поділитись</a>
								</li>
								<li>
									<a href=""><i class="sprite-link"></i> Скопіювати посилання</a>
								</li>
							</ul>
							@if(!empty($item->procuringEntity->contactPoint->name) || !empty($item->procuringEntity->contactPoint->email))
								<p><strong>Контакти</strong></p>
								@if(!empty($item->procuringEntity->contactPoint->name))
									<p>{{$item->procuringEntity->contactPoint->name}}</p>
								@endif
								@if(!empty($item->procuringEntity->contactPoint->telephone))
									<p>{{$item->procuringEntity->contactPoint->telephone}}</p>
								@endif
								@if(!empty($item->procuringEntity->contactPoint->email))
									<small><a href="mailto:{{$item->procuringEntity->contactPoint->email}}" class="word-break">{{$item->procuringEntity->contactPoint->email}}</a></small>
								@endif
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="tender--description">
			<div class="container">
				<div class="border-bottom margin-bottom-xl">
					<div class="row">
						<div class="col-sm-9">
							<div class=" margin-bottom">
								<h3>Опис</h3>
								<div class="row">
									<div class="col-md-4 col-md-push-8">
										{{--
										<div class="gray-bg padding margin-bottom tender--description--number">
											Номер тендера:
											<div class="blue">{{$item->tenderID}}</div>
										</div>
										--}}
									</div>
									@if (!empty($item->description))
										<div class="col-md-12 description-wr croped">
											<div class="tender--description--text description{{mb_strlen($item->description)>350?' croped':' open'}}">
												{{$item->description}}
											</div>
											@if (mb_strlen($item->description)>350)
												<a class="search-form--open" href="">
													<i class="sprite-arrow-right"></i>
													<span>розгорнути</span>
													<span>згорнути</span>
												</a>
											@endif
										</div>
									@endif
								</div>
							</div>
							@if (!empty($item->items))
								<div class="margin-bottom">
									<div class="border-bottom">
										<h3>Позиції</h3>
										@foreach($item->items as $one)
											<div class="row">
												<div class="col-md-4 col-md-push-8">
													<div class="padding margin-bottom">
														{{$one->quantity}} шт.
													</div>
												</div>
												<div class="col-md-8 col-md-pull-4 description-wr{{mb_strlen($one->description)>350?' croped':' open'}}">
													<div class="tender--description--text description">
														{{$one->description}}
													</div>
													@if (mb_strlen($one->description)>350)
														<a class="search-form--open"><i class="sprite-arrow-down"></i>
															<span>розгорнути</span>
															<span>згорнути</span>
														</a>
													@endif
												</div>
											</div>
										@endforeach
									</div>
								</div>
							@endif
							<div class="row">
								@if (!empty($item->documents))
								<div class="col-sm-4 margin-bottom ">
									<h3>Документація</h3>
									<div class="gray-bg padding margin-bottom">
										<ul class="nav nav-list">
											@foreach ($item->documents as $k=>$document)
												@if($k<5)
													<li>
														{{date('d.m.Y', strtotime($document->dateModified))}}<br>
														<a href="{{$document->url}}" target="_blank" class="word-break">{{$document->title}}</a>
													</li>
												@endif
											@endforeach
											{{--<li><a href="#"><i class="sprite-zip"></i> Зберегти усі документи архівом</a></li>--}}
										</ul>
										@if(sizeof($item->documents)>5)
											<div class="documents-all-wr"><a href="" class="documents-all">Всі документи </a><span class="all-number">({{sizeof($item->documents)}})</span></div>
										@endif
										<div class="overlay overlay-documents-all">
											<div class="overlay-close overlay-close-layout"></div>
											<div class="overlay-box">
												<div class="tender--offers documents" data-id="e059392ff4204074bfd76bf56cca7c74" style="display: block;">
													<h4 class="overlay-title">Документація</h4>
													@foreach ($item->documents as $k=>$document)
														<div class="document-info">
															<div class="document-date">{{date('d.m.Y H:i', strtotime($document->dateModified))}}</div>
															<a href="{{$document->url}}" target="_blank" class="document-name">{{$document->title}}</a>
														</div>
													@endforeach																																													</div>
													<div class="overlay-close"><i class="sprite-close-grey"></i></div>
												</div>
										</div>
									</div>
								</div>
								@endif
								<div class="col-sm-4 margin-bottom ">
									<h3>Дати</h3>
									<div class="gray-bg padding margin-bottom">
										<ul class="nav nav-list">
											@if(!empty($item->enquiryPeriod->endDate))
												<li>
													<strong>Період уточнень:</strong><br>
													до {{date('d.m.Y H:i', strtotime($item->enquiryPeriod->endDate))}}
												</li>
											@endif
											@if(!empty($item->tenderPeriod->endDate))
												<li>
													<strong>Подання пропозицій:</strong><br>
													до {{date('d.m.Y H:i', strtotime($item->tenderPeriod->endDate))}}
												</li>
											@endif
											@if(!empty($item->enquiryPeriod->endDate))
												<li>
													<strong>Початок аукціону:</strong><br>
													{{date('d.m.Y H:i', strtotime($item->enquiryPeriod->endDate))}}
												</li>
											@endif
										</ul>
									</div>
								</div>
								<div class="col-sm-4 margin-bottom ">
									<h3>Інформація про торги</h3>
									<div class="gray-bg padding margin-bottom">
										<ul class="nav nav-list">
											@if (!empty($dataStatus[$item->status]))
												<li>
													<strong>Статус:</strong><br>{{$dataStatus[$item->status]}}
												</li>
											@endif
											@if (!empty($item->value->amount))
												<li>
													<strong>Бюджет:</strong><br>{{number_format($item->value->amount, 0, '', ' ')}} {{$item->value->currency}}
												</li>
											@endif
											@if (!empty($item->minimalStep->amount))
												<li>
													<strong>Мінімальний крок:</strong><br>{{$item->minimalStep->amount}} {{$item->minimalStep->currency}}
												</li>
											@endif
											<li>
												<strong>Номер тендеру:</strong><br>
												{{$item->tenderID}}
											</li>
										</ul>
										<div class="info-all-wr"><a href="" class="info-all">Додаткова інформація</a></span></div>
										<div class="overlay overlay-info-all">
											<div class="overlay-close overlay-close-layout"></div>
											<div class="overlay-box">
												<div class="tender--offers documents" data-id="e059392ff4204074bfd76bf56cca7c74" style="display: block;">
													<h4 class="overlay-title">Інформація про торги</h4>
													<div class="document-info">
														ID
														<div class="document-date">{{$item->id}}</div>
													</div>																																																							</div>
												<div class="overlay-close"><i class="sprite-close-grey"></i></div>
											</div>
										</div>
									</div>
								</div>
							</div>
							@if (!empty($item->procuringEntity))
								<h3>Замовник</h3>
								
								<div class="row">
									<table class="tender--customer margin-bottom">
										<tbody>
											@if (!empty($item->procuringEntity->identifier->legalName))
												<tr>
													<td class="col-sm-4"><strong>Назва підприємства:</strong></td>
													<td class="col-sm-6">{{$item->procuringEntity->identifier->legalName}}</td>
												</tr>
											@endif
											@if (!empty($item->procuringEntity->identifier->id))
												<tr>
													<td class="col-sm-4"><strong>ЄДРПОУ:</strong></td>
													<td class="col-sm-6">{{$item->procuringEntity->identifier->id}}</td>
												</tr>
											@endif
											@if (!empty($item->procuringEntity->contactPoint->url))
												<tr>
													<td class="col-sm-4"><strong>Сайт:</strong></td>
													<td class="col-sm-6"><a href="{{$item->procuringEntity->contactPoint->url}}" target="_blank">{{$item->procuringEntity->contactPoint->url}}</a></td>
												</tr>
											@endif
											@if (!empty($item->procuringEntity->address))
												<tr>
													<td class="col-sm-4"><strong>Адреса:</strong></td>
													<td class="col-sm-6">{{!empty($item->procuringEntity->address->postalCode) ? $item->procuringEntity->address->postalCode.', ': ''}}{{$item->procuringEntity->address->countryName}}, {{!empty($item->procuringEntity->address->region) ? $item->procuringEntity->address->region.' обл., ' : ''}}{{!empty($item->procuringEntity->address->locality) ? $item->procuringEntity->address->locality.', ' : ''}}{{!empty($item->procuringEntity->address->streetAddress) ? $item->procuringEntity->address->streetAddress : ''}}</td>
												</tr>
											@endif
											@if (!empty($item->procuringEntity->contactPoint))
												<tr>
													<td class="col-sm-4"><strong>Контактна особа:</strong></td>
													<td class="col-sm-6">
														@if (!empty($item->procuringEntity->contactPoint->name))
															{{$item->procuringEntity->contactPoint->name}}<br>
														@endif
														@if (!empty($item->procuringEntity->contactPoint->telephone))
															{{$item->procuringEntity->contactPoint->telephone}}<br>
														@endif
														@if (!empty($item->procuringEntity->contactPoint->email))
															<a href="mailto:{{$item->procuringEntity->contactPoint->email}}">{{$item->procuringEntity->contactPoint->email}}</a><br>
														@endif
													</td>
												</tr>
											@endif
										</tbody>
									</table>
								</div>
							@endif	
						</div>
					</div>
				</div>
				<div class="tender--platforms border-bottom margin-bottom-xl">
					<h3>Подати пропозицію</h3>
					Оберіть один з майданчиків, щоб прийняти участь у аукціоні
					<div class="tender--platforms--list clearfix">
						@foreach($platforms as $platform)
							<div class="item">
								<div class="img-wr">
									<a href="{{str_replace('{tenderID}', $item->tenderID, $platform['href'])}}" target="_blank">
										<img src="/assets/images/platforms/{{$platform['slug']}}.png" alt="{{$platform['name']}}" title="{{$platform['name']}}">
									</a>
								</div>
								<div class="border-hover">
									<div class="btn-wr"><a href="{{str_replace('{tenderID}', $item->tenderID, $platform['href'])}}" target="_blank" class="btn">Прийняти участь</a></div>
								</div>
							</div>
						@endforeach
					</div>
					{{--<a href="#" class="more margin-bottom"><i class="sprite-arrow-down"></i> Показати всіх</a>--}}
				</div>

				@if (!empty($item->bids))
					<div class="tender--offers margin-bottom-xl">
						<h3>Отримані пропозиції</h3>
						<table class="table table-striped margin-bottom">
							<thead>
								<tr>
									<th>Учасник</th>
									<th>Пропозиція</th>
									<th>Статус</th>
									<th>Документи</th>
								</tr>
							</thead>
							<tbody>
								@foreach($item->bids as $bid)
									<tr>
										<td>{{$bid->tenderers[0]->name}}</td>
										<td>{{number_format($bid->value->amount, 0, '', ' ')}} {{$bid->value->currency}}{{$bid->value->valueAddedTaxIncluded?' з ПДВ':''}}</td>
										<td>
											@if (!empty($item->awards))
												@foreach($item->awards as $award)
													@if($award->bid_id==$bid->id)
														@if($award->status=='unsuccessful')
															Дискваліфіковано
														@elseif($award->status=='active')
															Переможець
														@elseif($award->status=='pending')
															Очікує рішення
														@else
															{{$award->status}}
														@endif
													@endif
												@endforeach
											@endif
										</td>
										<td>
											@if(!empty($bid->documents))
												<a href="" class="document-link" data-id="{{$bid->id}}">Документи</a>
											@else
												Немає
											@endif
										</td>
									</tr>
								@endforeach
							</tbody>
					</table>
					<div class="overlay overlay-documents">
						<div class="overlay-close overlay-close-layout"></div>
						<div class="overlay-box">
							@foreach($item->bids as $bid)
								<div class="tender--offers documents" data-id="{{$bid->id}}">
									@if(!empty($bid->documents))
										<h4 class="overlay-title">Документи, подані з пропозицією</h4>
										@foreach($bid->documents as $document)
											<div class="document-info">
												<div class="document-date">{{date('d.m.Y H:i', strtotime($document->datePublished))}}</div>
												<a href="{{$document->url}}" target="_blank" class="document-name">{{$document->title}}</a>
											</div>
										@endforeach
									@endif
									@if(!empty($item->awards))
										@foreach($item->awards as $award)
											@if($award->bid_id==$bid->id && !empty($award->documents))
												<h4 class="overlay-title">Рішення відповідальної особи</h4>
												@foreach($award->documents as $award_document)
													<div class="document-info">
														<div class="document-date">{{date('d.m.Y H:i', strtotime($award_document->datePublished))}}</div>
														<a href="{{$award_document->url}}" target="_blank" class="document-name">{{$award_document->title}}</a>
													</div>
												@endforeach
											@endif
										@endforeach
									@endif
								</div>
							@endforeach
							<div class="overlay-close"><i class="sprite-close-grey"></i></div>
						</div>
					</div>
				@endif
				{{--
				<div class="tender--offers border-bottom margin-bottom">
					<h3>Отримані пропозиції</h3>
					
					<ul class="nav nav-list margin-bottom">
						@foreach($item->bids as $bid)
							<li> <a href="#">Зберегти pdf</a></li>
						@endforeach
					</ul>
				</div>
				--}}
				{{--
				<div class="tender--complaint margin-bottom">
					<h3>? Отримані скарги</h3>
					<table class="table table-striped margin-bottom">
						<thead>
							<tr>
								<th>Дата подачі скарги</th>
								<th>Назва скаргоподавця</th>
								<th>Статус скарги</th>
								<th>Документи</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>? 22-12-2016</td>
								<td>? Назва організації Назва організації </td>
								<td>? Наразі невідомо)</td>
								<td>
									<a href="#">Зберегти pdf</a>
								</td>
							</tr>
							<tr>
								<td>? 22-12-2016</td>
								<td>? Назва організації Назва організації </td>
								<td>? Наразі невідомо)</td>
								<td>
									<a href="#">Зберегти pdf</a>
								</td>
							</tr>
							<tr>
								<td>? 22-12-2016</td>
								<td>? Назва організації Назва організації </td>
								<td>? Наразі невідомо)</td>
								<td>
									<a href="#">Зберегти pdf</a>
								</td>
							</tr>
						</tbody>
					</table>
					<a href="#" class="more margin-bottom"><i class="sprite-arrow-down"></i> Показати всі</a>
				</div>
				
				<div class="tender--simmilar">
					<h3>? Схожі аукціони в періоді уточнення</h3>
					
					<div class="row">
						<div class="col-md-4 col-sm-6">
							<div class="tender--simmilar--item gray-bg padding margin-bottom active">
								<div class="tender--simmilar--item--control">
									<a href="#"><i class="sprite-star"></i></a>
									<a href="#"><i class="sprite-close-blue"></i></a>
								</div>
								<div class="green tender--simmilar--item--cost">30 124 <span class="small">грн</span></div>
								<a href="#" class="title">Проведення вимірювальних робіт електроустановок та електропристроїв на відповідність до вимог ПТЕ та ПТБ</a>
								
								
								<div class="tender--legend">Prozorro    <span class="marked">Уточнення</span> м. Київ</div>
								
								<div class="tender--simmilar--item--hidden-part">	
									<div class="tender--simmilar--text margin-bottom">
										<p>1. Вимірювання опору розтікання на основних заземлювачах і заземленнях магістралей і устаткування на об’єкті (вимірювання опору розтікання заземлюючого пристрою, питомого опору ґрунту, активного опору на змінному струмі PU183.1); (4 вимірювань) 2. ...</p>
										<strong>Компанія:</strong> Вищий навчальний заклад Київський медичний коледж ім.П.І.Гаврося"
									</div>
									<a href="#"><i class="sprite-arrow-right"></i> Детальніше</a>
								</div>
							</div>
						</div>
						
						<div class="col-md-4 col-sm-6">
							<div class="tender--simmilar--item gray-bg padding margin-bottom">
								<div class="tender--simmilar--item--control">
									<a href="#"><i class="sprite-star"></i></a>
									<a href="#"><i class="sprite-close-blue"></i></a>
								</div>
								<div class="green tender--simmilar--item--cost">30 124 <span class="small">грн</span></div>
								<a href="#" class="title">Проведення вимірювальних робіт електроустановок та електропристроїв на відповідність до вимог ПТЕ та ПТБ</a>
								
								
								<div class="tender--legend">Допорогові торги    <span class="marked">Уточнення</span>    м. Київ</div>
								
								<div class="tender--simmilar--item--hidden-part">	
									<div class="tender--simmilar--text margin-bottom">
										<p>1. Вимірювання опору розтікання на основних заземлювачах і заземленнях магістралей і устаткування на об’єкті (вимірювання опору розтікання заземлюючого пристрою, питомого опору ґрунту, активного опору на змінному струмі PU183.1); (4 вимірювань) 2. ...</p>
										<strong>Компанія:</strong> Вищий навчальний заклад Київський медичний коледж ім.П.І.Гаврося"
									</div>
									<a href="#"><i class="sprite-arrow-right"></i> Детальніше</a>
								</div>
							</div>
						</div>
						
						<div class="col-md-4 col-sm-6">
							<div class="tender--simmilar--item gray-bg padding margin-bottom">
								<div class="tender--simmilar--item--control">
									<a href="#"><i class="sprite-star"></i></a>
									<a href="#"><i class="sprite-close-blue"></i></a>
								</div>
								<div class="green tender--simmilar--item--cost">30 124 <span class="small">грн</span></div>
								<a href="#" class="title">Проведення вимірювальних робіт електроустановок та електропристроїв на відповідність до вимог ПТЕ та ПТБ</a>
								
								
								<div class="tender--legend">Допорогові торги    <span class="marked">Уточнення</span>    м. Київ</div>
								
								<div class="tender--simmilar--item--hidden-part">	
									<div class="tender--simmilar--text margin-bottom">
										<p>1. Вимірювання опору розтікання на основних заземлювачах і заземленнях магістралей і устаткування на об’єкті (вимірювання опору розтікання заземлюючого пристрою, питомого опору ґрунту, активного опору на змінному струмі PU183.1); (4 вимірювань) 2. ...</p>
										<strong>Компанія:</strong> Вищий навчальний заклад Київський медичний коледж ім.П.І.Гаврося"
									</div>
									<a href="#"><i class="sprite-arrow-right"></i> Детальніше</a>
								</div>
							</div>
						</div>
						
						<div class="col-md-4 col-sm-6">
							<div class="tender--simmilar--item gray-bg padding margin-bottom">
								<div class="tender--simmilar--item--control">
									<a href="#"><i class="sprite-star"></i></a>
									<a href="#"><i class="sprite-close-blue"></i></a>
								</div>
								<div class="green tender--simmilar--item--cost">30 124 <span class="small">грн</span></div>
								<a href="#" class="title">Проведення вимірювальних робіт електроустановок та електропристроїв на відповідність до вимог ПТЕ та ПТБ</a>
								
								
								<div class="tender--legend">Допорогові торги    <span class="marked">Уточнення</span>    м. Київ</div>
								
								<div class="tender--simmilar--item--hidden-part">	
									<div class="tender--simmilar--text margin-bottom">
										<p>1. Вимірювання опору розтікання на основних заземлювачах і заземленнях магістралей і устаткування на об’єкті (вимірювання опору розтікання заземлюючого пристрою, питомого опору ґрунту, активного опору на змінному струмі PU183.1); (4 вимірювань) 2. ...</p>
										<strong>Компанія:</strong> Вищий навчальний заклад Київський медичний коледж ім.П.І.Гаврося"
									</div>
									<a href="#"><i class="sprite-arrow-right"></i> Детальніше</a>
								</div>
							</div>
						</div>
						
						<div class="col-md-4 col-sm-6">
							<div class="tender--simmilar--item gray-bg padding margin-bottom">
								<div class="tender--simmilar--item--control">
									<a href="#"><i class="sprite-star"></i></a>
									<a href="#"><i class="sprite-close-blue"></i></a>
								</div>
								<div class="green tender--simmilar--item--cost">30 124 <span class="small">грн</span></div>
								<a href="#" class="title">Проведення вимірювальних робіт електроустановок та електропристроїв на відповідність до вимог ПТЕ та ПТБ</a>
								
								
								<div class="tender--legend">Допорогові торги    <span class="marked">Уточнення</span>    м. Київ</div>
								
								<div class="tender--simmilar--item--hidden-part">	
									<div class="tender--simmilar--text margin-bottom">
										<p>1. Вимірювання опору розтікання на основних заземлювачах і заземленнях магістралей і устаткування на об’єкті (вимірювання опору розтікання заземлюючого пристрою, питомого опору ґрунту, активного опору на змінному струмі PU183.1); (4 вимірювань) 2. ...</p>
										<strong>Компанія:</strong> Вищий навчальний заклад Київський медичний коледж ім.П.І.Гаврося"
									</div>
									<a href="#"><i class="sprite-arrow-right"></i> Детальніше</a>
								</div>
							</div>
						</div>
						
						<div class="col-md-4 col-sm-6">
							<div class="tender--simmilar--item gray-bg padding margin-bottom">
								<div class="tender--simmilar--item--control">
									<a href="#"><i class="sprite-star"></i></a>
									<a href="#"><i class="sprite-close-blue"></i></a>
								</div>
								<div class="green tender--simmilar--item--cost">30 124 <span class="small">грн</span></div>
								<a href="#" class="title">Проведення вимірювальних робіт електроустановок та електропристроїв на відповідність до вимог ПТЕ та ПТБ</a>
								
								
								<div class="tender--legend">Допорогові торги    <span class="marked">Уточнення</span>    м. Київ</div>
								
								<div class="tender--simmilar--item--hidden-part">	
									<div class="tender--simmilar--text margin-bottom">
										<p>1. Вимірювання опору розтікання на основних заземлювачах і заземленнях магістралей і устаткування на об’єкті (вимірювання опору розтікання заземлюючого пристрою, питомого опору ґрунту, активного опору на змінному струмі PU183.1); (4 вимірювань) 2. ...</p>
										<strong>Компанія:</strong> Вищий навчальний заклад Київський медичний коледж ім.П.І.Гаврося"
									</div>
									<a href="#"><i class="sprite-arrow-right"></i> Детальніше</a>
								</div>
							</div>
						</div>
	
					</div>
				</div>
				--}}
			</div>
		</div>
	</div>
@elseif ($error)
	<div style="padding:20px 20px 40px 10px;">
		API ERROR: {{$error}}
	</div>
@else
	<div style="padding:20px 20px 40px 10px;">
		Тендер не знайдено
	</div>
@endif

@endsection