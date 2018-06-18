<?php namespace Nano7\View\Middlewares;

use Closure;
use Nano7\View\Factory as ViewFactory;

class ShareStatusFromSession
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
        $status = $request->session()->flash('status');
        if (! is_null($status)) {
            $status = is_string($status) ? ['message' => $status, 'type' => 'success'] : $status;
            $this->view->share('__status', $status);
        }

        return $next($request);
    }
}
