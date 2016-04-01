<?php namespace App\Exceptions;

use Exception;
use Request;
use Log;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler {

	/**
	 * A list of the exception types that should not be reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		'Symfony\Component\HttpKernel\Exception\HttpException'
	];

	/**
	 * Report or log an exception.
	 *
	 * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
	 *
	 * @param  \Exception  $e
	 * @return void
	 */
	public function report(Exception $e)
	{
        Log::info(Request::method().' '.Request::url());

        if(!empty(Request::all())){
            Log::info('', Request::all());
        }

        Log::info($e->getFile().' ('.$e->getLine().')');
        Log::info($e->getMessage());

		//return parent::report($e);
	}

	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Exception  $e
	 * @return \Illuminate\Http\Response
	 */
	public function render($request, Exception $e)
	{
        if($e instanceof \Symfony\Component\Debug\Exception\FatalErrorException) {
            $statusCode = 500;
        }

        if(($e instanceof NotFoundHttpException || $e instanceof MethodNotAllowedHttpException) && view()->exists('errors.'.$e->getStatusCode())) {
            $statusCode=$e->getStatusCode();
            
            return response()->view('errors.'.$e->getStatusCode(), [], $statusCode);
        }

        if (app()->environment() == 'production') {
            return response()->view('errors.500', [], 200);
        }
        
        return parent::render($request, $e);
	}
}
