<?php namespace Way\Console;

use Illuminate\Support\ServiceProvider;

class GuardLaravelServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerMake();
		$this->registerWatch();
		$this->registerRefresh();

		$this->registerCommands();
	}

	protected function registerMake()
	{
		// guard:make
		$this->app['guard.make'] = $this->app->share(function($app)
		{
			$generator = new GuardGenerator($app['files'], \Config::getFacadeRoot());
			$gem = new Gem;

			return new GuardInitCommand($generator, $app['files'], $gem);
		});
	}

	protected function registerWatch()
	{
		// guard:watch
		$this->app['guard.watch'] = $this->app->share(function($app)
		{
			return new GuardWatchCommand;
		});
	}

	protected function registerRefresh()
	{
		// guard:refresh
		$this->app['guard.refresh'] = $this->app->share(function($app)
		{
			$generator = new GuardGenerator($app['files'], \Config::getFacadeRoot());

			return new GuardRefreshCommand($generator);
		});
	}

	protected function registerCommands()
	{
		$this->commands(
			'guard.make',
			'guard.watch',
			'guard.refresh'
		);
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}