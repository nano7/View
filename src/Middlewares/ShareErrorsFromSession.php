<?php namespace Nano7\View\Middlewares;

use Closure;
use Illuminate\Support\MessageBag;
use Nano7\View\Factory as ViewFactory;

class ShareErrorsFromSession
{
    /**
     * The view factory implementation.
     *
     * @var \Illuminate\Contracts\View\Factory
     */
    protected $view;

    /**
     * Create a new error binder instance.
     *
     * @param  \Nano7\View\Factory  $view
     * @return void
     */
    public function __construct(ViewFactory $view)
    {
        $this->view = $view;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Nano7\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // If the current session has an "errors" variable bound to it, we will share
        // its value with all view instances so the views can easily access errors
        // without having to bind. An empty bag is set when there aren't errors.
        $errors = $request->session()->flash('errors');
        if (! is_null($errors) && is_array($errors)) {
            $errors = new MessageBag($errors);
        } else {
            $errors = new MessageBag();
        }
        $this->view->share('errors', $errors);

        // Putting the errors in the view for every view allows the developer to just
        // assume that some errors are always available, which is convenient since
        // they don't have to continually run checks for the presence of errors.

        return $next($request);
    }
}
