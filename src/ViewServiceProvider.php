<?php namespace Nano7\View;

use Nano7\View\Engines\PhpEngine;
use Nano7\View\Engines\CompilerEngine;
use Nano7\View\Engines\EngineResolver;
use Nano7\View\Compilers\BladeCompiler;
use Nano7\Foundation\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider {

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerEngineResolver();

		$this->registerViewFinder();

		$this->registerFactory();

        $this->registerMiddlewares();
	}

	/**
	 * Register the engine resolver instance.
	 *
	 * @return void
	 */
	public function registerEngineResolver()
	{
		$this->app->singleton('view.engine.resolver', function()
		{
			$resolver = new EngineResolver;

			// Next we will register the various engines with the resolver so that the
			// environment can resolve the engines it needs for various views based
			// on the extension of view files. We call a method for each engines.
			foreach (array('php', 'blade') as $engine)
			{
				$this->{'register'.ucfirst($engine).'Engine'}($resolver);
			}

			return $resolver;
		});
	}

	/**
	 * Register the PHP engine implementation.
	 *
	 * @param  EngineResolver  $resolver
	 * @return void
	 */
	public function registerPhpEngine($resolver)
	{
		$resolver->register('php', function() { return new PhpEngine; });
	}

	/**
	 * Register the Blade engine implementation.
	 *
	 * @param  EngineResolver  $resolver
	 * @return void
	 */
	public function registerBladeEngine($resolver)
	{
		$app = $this->app;

		// The Compiler engine requires an instance of the CompilerInterface, which in
		// this case will be the Blade compiler, so we'll first create the compiler
		// instance to pass into the engine so it can compile the views properly.
		$app->singleton('blade.compiler', function($app)
		{
			$cache = $app['config']['view.compiled'];

			return new BladeCompiler($app['files'], $cache);
		});

		$resolver->register('blade', function() use ($app)
		{
			return new CompilerEngine($app['blade.compiler'], $app['files']);
		});
	}

	/**
	 * Register the view finder implementation.
	 *
	 * @return void
	 */
	public function registerViewFinder()
	{
		$this->app->bind('view.finder', function($app)
		{
			$paths = $app['config']['view.paths'];

			return new FileViewFinder($app['files'], $paths);
		});
	}

	/**
	 * Register the view environment.
	 *
	 * @return void
	 */
	public function registerFactory()
	{
		$this->app->singleton('view', function($app)
		{
			// Next we need to grab the engine resolver instance that will be used by the
			// environment. The resolver will be used by an environment to get each of
			// the various engine implementations such as plain PHP or Blade engine.
			$resolver = $app['view.engine.resolver'];

			$finder = $app['view.finder'];

			$env = new Factory($resolver, $finder, $app['events']);

			// We will also set the container instance on this view environment since the
			// view composers may be classes registered in the container, which allows
			// for great testable, flexible composers for the application developer.
			$env->setContainer($app);

			$env->share('app', $app);

			return $env;
		});
	}

    /**
     * Register middlewares.
     *
     * @return void
     */
    protected function registerMiddlewares()
    {
        event()->listen('web.middleware.register', function ($web) {

            $web->middleware('share.errors', '\Nano7\View\Middlewares\ShareErrorsFromSession');
            $web->alias('share.errors');
        });
    }
}
