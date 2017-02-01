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
    	return parent::report($e);

        $url=Request::url();

        Log::info(Request::method().' '.$url);

        if(!empty(Request::all())){
            Log::info('', Request::all());
        }

        $file=$e->getFile();
        $line=$e->getLine();

        $filename=storage_path('logs/'.str_replace(['http://', 'https://', 'www.', '.', '/'], ['', '', '', '-', '-'], $url).'-'.str_replace('.php', '', last(explode('/', $file))).'-'.$line.'.log');

        if(!file_exists($filename))
            file_put_contents($filename, $url."\n\n".$file.' ('.$line.")\n".$e->getMessage());
            
        Log::info($file.' ('.$line.')');
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
