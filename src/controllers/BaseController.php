<?php namespace Yusidabcs\Checkout;
use Pengaturan;
use Illuminate\Support\Facades\View;
class BaseController extends \Illuminate\Routing\Controllers\Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	public $layout = 'checkout::template';
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
            $ga = Pengaturan::find('1')->gAnalytics;

			$this->layout = View::make($this->layout)
				->with('analytic',$ga)
                ->with('kontak', Pengaturan::find(1));
		}
	}

}