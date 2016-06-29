@section('recaptcha')
    
@endsection

@section('error-form1')
    <div class="form-fixed none">
        <form method="post" action="/feedback/" class="form-fixed-inner">
            <div class="relative{{$errors->has('email') ? ' error' : ''}}">
                <input type="text" name="email" value="lanko.andrey@gmail.com" placeholder="Електронна пошта">
                <p class="error-text">{!!$errors->first('email')!!}</p>
            </div>
    
            <div class="relative{{$errors->has('phone') ? ' error' : ''}}">
                <input type="text" name="phone" value="" placeholder="Телефон">
                <div class="input-help">формат: 38...</div>
                <p class="error-text">{!!$errors->first('phone')!!}</p>
            </div>
            
            <div class="relative{{$errors->has('name') ? ' error' : ''}}">
                <input type="text" name="name" value="" placeholder="Ім'я">
                <p class="error-text">{!!$errors->first('name')!!}</p>
            </div>
            
            <div class="relative{{$errors->has('subject') ? ' error' : ''}}">
                <textarea name="subject" placeholder="Test">текст ошибки</textarea>
                @if ($errors->has('subject'))<p class="error-text">{!!$errors->first('subject')!!}</p>@endif
            </div>
            
            <div class="relative">
                <select>
                    <option value="1">Помилка у відображенні документу</option>
                    <option value="2">Інша помилка</option>
                </select>
            </div>
    
            <div class="relative{{$errors->has('tenderid') ? ' error' : ''}}">
                <input type="text" name="tenderid" value="UA-" placeholder="Номер тендеру">
                <div class="input-help">формат: UA-</div>
                <p class="error-text">{!!$errors->first('tenderid')!!}</p>
            </div>
            
            <div class="relative{{$errors->has('g-recaptcha-response') || $errors->has('api') ? ' error' : ''}}">
                {!! Recaptcha::render(['lang'=>'uk']) !!}
                @if ($errors->has('g-recaptcha-response'))<p class="error-text">{!!$errors->first('g-recaptcha-response')!!}</p>@endif
                @if ($errors->has('api'))<p class="error-text error-recaptcha">{!!$errors->first('api')!!}</p>@endif
            </div>
            
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="main-menu"><input type="submit" value="Отправить" class="btn green-btn registration"></div>
    
            <div class="close sprite-close-blue"></div>
        </form>
    </div>

    <div class="main-menu">
        <input type="submit" value="Отправить" class="btn btn-fixed-form green-btn registration send-button">
    </div>
@endsection