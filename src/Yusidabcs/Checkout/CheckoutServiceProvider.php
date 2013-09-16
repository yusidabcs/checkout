<?php namespace Yusidabcs\Checkout;

use Illuminate\Support\ServiceProvider;

class CheckoutServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;
	public function boot()
    {
        $this->package('yusidabcs/checkout');
        include __DIR__.'/../../routes.php';
    }
	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['checkout'] = $this->app->share(function($app)
        {
            return new Checkout;
        });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('checkout');
	}

}