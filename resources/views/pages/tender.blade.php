
		
@extends('layouts/app')

@section('content')
{{--

@include('partials/form')

@if ($item && !$error)
	<div style="padding:20px 20px 40px 10px;">
		<h1>{{$item->title}}</h1>
		<div><b>{{$item->tenderID}}</b></div>
		<div><i>{{$item->description}}</i></div>
		<div>{{date('d.m.Y', strtotime($item->tenderPeriod->startDate))}} — {{date('d.m.Y', strtotime($item->tenderPeriod->endDate))}}</div>
		<h4>{{$item->value->amount}} {{$item->value->currency}}</h4>
	</div>
@elseif ($error)
	<div style="padding:20px 20px 40px 10px;">
		API ERROR: {{$error}}
	</div>
@else
	<div style="padding:20px 20px 40px 10px;">
		Тендер не знайдено
	</div>
@endif--}}


<div class="tender" data-js="tender">
	<div class="tender--head gray-bg">
		<div class="container">
			<div class="tender--head--title">{{$item->title}}</div>

			{{--
			<div class="breadcrumb_custom clearfix">
				<a href="#" class="disable"><strong>Створена:</strong> 27.07 (сб)</a>
				<a href="#" class="active"><strong>Уточнення:</strong> до 29.06 (пн)</a>
				<a href="#"><strong>Пропозиції:</strong> до 6.07 (пт)</a>
				<a href="#"><strong>Аукціон:</strong> 12.07 (пт)</a>
				<a href="#"><strong>Кваліфікаця:</strong> з 15.07 (пн)</a>
			</div>
			--}}

			<div class="row">
				<div class="col-sm-9">
					@if (!empty($item->procuringEntity->name))
						<div class="tender--head--company">{{$item->procuringEntity->name}}</div>
					@endif
					<div class="tender--head--inf">Prozorro   <span class="marked">{{$dataStatus[$item->status]}}</span>   @if (!empty($item->procuringEntity->address->locality)){{$item->procuringEntity->address->locality}}@endif</div>
				</div>
				<div class="col-sm-3 tender--menu">
					<a href="#"><i class="sprite-arrow-left"></i> Повернутися до результатів</a>
					<div class="clearfix"></div>
					<a href="#" class="blue-btn">Прийняти участь</a>
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
					<ul class="nav nav-list last">	
						
						<li>
							<a href="#"><i class="sprite-print"></i> Роздрукувати</a>
						</li>
						<li>
							<a href="#"><i class="sprite-download"></i> Зберегти як PDF</a>
						</li>
						<li>
							<a href="#"><i class="sprite-share"></i> Поділитись</a>
						</li>
						<li>
							<a href="#"><i class="sprite-link"></i> Скопіювати посилання</a>
						</li>
					</ul>
					
					<p><strong>Контакти</strong></p>
					<p>{{$item->procuringEntity->contactPoint->name}}</p>

					<p class="small">?Завідуючий складом та голова департаменту з дуже довгою назвою</p>

				</div>
			</div>
		</div>
	</div>
	<div class="tender--description">
		<div class="container">
			<div class="border-bottom margin-bottom">
			<div class="row">
				<div class="col-sm-9">
					<div class=" margin-bottom">
					
						<h3>Опис</h3>
						<div class="row">
							
							<div class="col-md-4 col-md-push-8">
								<div class="gray-bg padding margin-bottom tender--description--number">
									Номер тендера:
									<div class="blue">{{$item->tenderID}}</div>
								</div>

								<div class="gray-bg padding margin-bottom tender--description--cost">
									Бюджет закупівель
									<div class="green tender--description--cost--number">
										<strong>{{number_format($item->value->amount, 0, '', ' ')}} <span class="small">{{$item->value->currency}}</span></strong>
									</div>
								</div>
							</div>
							
							<div class="col-md-8 col-md-pull-4 description-wr croped">
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
						</div>
					</div>

					<div class="margin-bottom">
						<div class="border-bottom">
							<h3>? Позициції</h3>
							<div class="row">
								<div class="col-md-4 col-md-push-8">
									<div class="padding margin-bottom">
										? 320 шт.
									</div>
								</div>
								<div class="col-md-8 col-md-pull-4 description-wr{{mb_strlen($item->description)>350?' croped':' open'}}">
									<div class="tender--description--text description">
										? Папка-реєстратор, 70 мм, А4, Delta, одностороння Швидкозшивач картонний А4, розмітка на обкладинці, в упаковці 10 штук Папка-куточок А4, Economix 180 мкм, прозоро-глянцева Папка з боковим притиском А4 Delta Папка-конверт на кнопці Deli А4, непрозора Файли глянцеві для документів А4 Buromax+, 40 мкм, 100 шт Біндери 19мм (12 шт.) Buromax Біндери 32мм (12 шт.) Buromax Клей ПВА 200 мл із супер ковпачком Buromax Клей-олівець перманентний Kores 40 гр Клей ПВА з пензликом КИП, об'єм 20 мл Коректор-стрiчка Tipp-Ex Pocket Mouse, 5 м х 5 мм Коректор-олівець Buromax металевий наконечник, об'єм 8 мл Коректор з пензликом Buromax, масляна основа, об'єм 20 мл Ніж канцелярський Axent, металеві направляючі, 2 додаткових леза, 18 мм Ножицi офiснi Buromax з резиновими вставками, 21 см Бокс для паперу 90х90х90 мм чорний Скоби Buromax кольорові №24/6 (1000 шт.)
									</div>
									@if (mb_strlen($item->description)>350)
										<a class="search-form--open"><i class="sprite-arrow-down"></i>
											<span>розгорнути</span>
											<span>згорнути</span>
										</a>
									@endif
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						@if ($item->documents)
						<div class="col-sm-4 margin-bottom ">
							<h3>Документація</h3>
							<div class="gray-bg padding margin-bottom">
								<ul class="nav nav-list">
									@foreach ($item->documents as $document)
										<li>
											{{date('d.m.Y', strtotime($document->dateModified))}}<br>
											<a href="{{$document->url}}" target="_blank">{{$document->title}}</a>
										</li>
									@endforeach
									{{--<li><a href="#"><i class="sprite-zip"></i> Зберегти усі документи архівом</a></li>--}}
								</ul>
								
							</div>
						</div>
						@endif
						<div class="col-sm-4 margin-bottom ">
							<h3>Дати</h3>
							<div class="gray-bg padding margin-bottom">
								<ul class="nav nav-list">
									<li>
										<strong>Період уточнень:</strong><br>
										? до 21.10.15 10:00
									</li>
									<li>
										<strong>Подання пропозицій:</strong><br>
										? до 22.10.15 11:00
									</li>
									<li>
										<strong>Початок аукціону:</strong><br>
										? 03.12.15 11:44
									</li>
									<li>
										<strong>? Доставка до: -</strong>
									</li>
								</ul>
							</div>
						</div>
						<div class="col-sm-4 margin-bottom ">
							<h3>Інформація про торги</h3>
							<div class="gray-bg padding margin-bottom">
								<ul class="nav nav-list">
									<li>
										<strong>Статус:</strong> {{$dataStatus[$item->status]}}
									</li>
									<li>
										<strong>Бюджет:</strong> {{number_format($item->value->amount, 0, '', ' ')}} {{$item->value->currency}}
									</li>
									<li>
										<strong>Мінімальний крок:</strong> {{$item->minimalStep->amount}} {{$item->minimalStep->currency}}
									</li>
									<li>
										<strong>ID:</strong> <small>{{$item->id}}</small>
									</li>
									<li>
										<strong>TenderID:</strong><br>
										{{$item->tenderID}}
									</li>
								</ul>
							</div>
						</div>
					</div>
					@if (!empty($item->procuringEntity))
						<h3>Замовник</h3>
						
						<div class="row">
							<table class="tender--customer margin-bottom">
								<tbody>
									<tr>
										<td class="col-sm-4"><strong>Назва підприємства:</strong></td>
										<td class="col-sm-6">{{$item->procuringEntity->identifier->legalName}}</td>
									</tr>
									<tr>
										<td class="col-sm-4"><strong>ЄДРПОУ:</strong></td>
										<td class="col-sm-6">{{$item->procuringEntity->identifier->id}}</td>
									</tr>
									@if ($item->procuringEntity->contactPoint->url)
										<tr>
											<td class="col-sm-4"><strong>Сайт:</strong></td>
											<td class="col-sm-6"><a href="{{$item->procuringEntity->contactPoint->url}}" target="_blank">{{$item->procuringEntity->contactPoint->url}}</a></td>
										</tr>
									@endif
									<tr>
										<td class="col-sm-4"><strong>Адреса:</strong></td>
										<td class="col-sm-6">{{$item->procuringEntity->address->postalCode}}, {{$item->procuringEntity->address->countryName}}, {{$item->procuringEntity->address->region}} обл., {{$item->procuringEntity->address->locality}}, {{$item->procuringEntity->address->streetAddress}}</td>
									</tr>
									<tr>
										<td class="col-sm-4"><strong>Контактна особа:</strong></td>
										<td class="col-sm-6">
											{{$item->procuringEntity->contactPoint->name}}<br>
											{{$item->procuringEntity->contactPoint->telephone}}<br>
											?Завідуючий складом та голова департаменту з дуже довгою
											назвою :)))<br><br>
											@if ($item->procuringEntity->contactPoint->name)
												<a href="mailto:{{$item->procuringEntity->contactPoint->email}}">{{$item->procuringEntity->contactPoint->email}}</a>
											@endif
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					@endif	
				</div>
			</div>
			</div>
			<div class="tender--platforms border-bottom margin-bottom">
				<h3>Взяти участь</h3>
				Оберіть один з майданчиків, щоб прийняти участь у аукціоні
				<div class="tender--platforms--list margin-bottom clearfix">
					@foreach($platforms as $platform)
					<div class="item">
						<div class="img-wr">
							<a href="{{str_replace('{tenderID}', $item->tenderID, $platform['href'])}}" target="_blank">
								<img src="/assets/images/platforms/{{$platform['slug']}}.png" alt="{{$platform['name']}}" title="{{$platform['name']}}">
							</a>
						</div>
						<a href="{{str_replace('{tenderID}', $item->tenderID, $platform['href'])}}" target="_blank">{{$platform['name']}}</a>
					</div>
					@endforeach
				</div>
				{{--<a href="#" class="more margin-bottom"><i class="sprite-arrow-down"></i> Показати всіх</a>--}}
			</div>
			
			<div class="tender--offers border-bottom margin-bottom">
				<h3>Отримані пропозиції</h3>
				
				<ul class="nav nav-list margin-bottom">
					<li>? Назва організації Учасник номер 1 <a href="#">Зберегти pdf</a></li>
					<li>? Назва організації Назва організації Назва організації Назва організації Учасник номер 2 <a href="#">Зберегти pdf</a></li>
					<li>? Назва організації Назва організації Учасник номер 3 <a href="#">Зберегти pdf</a></li>
					<li>? Назва організації Назва організації Назва організації Учасник номер 4 <a href="#">Зберегти pdf</a></li>
				</ul>
			</div>
			
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
		</div>
	</div>
</div>

{{dump($item)}}
@endsection