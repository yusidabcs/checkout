<?php namespace Yusidabcs\Checkout;
use Pengaturan;
use Session;
use Illuminate\Support\Facades\View;
class BaseController extends \Illuminate\Routing\Controllers\Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	public $layout = 'checkout::template';
	public $setting;
	public $akunId;
	protected function setupLayout()
	{		
		$this->akunId =Session::get('akunid');
		$this->setting = Pengaturan::where('akunId','=',$this->akunId)->first();
		if ( ! is_null($this->layout))
		{
            $ga = $this->setting->gAnalytics;

			$this->layout = View::make($this->layout)
				->with('analytic',$ga)
                ->with('kontak', $this->setting);
		}
	}

}