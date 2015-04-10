<?php namespace Yusidabcs\Checkout;

use Produk;
use Pengaturan;
use Kategori;
use Sentry;
use Illuminate\View\Environment;


class Checkout{
	public $produk;
	public $login;
	protected $view;
	public function __construct(Environment $view)
    {
        $this->view = $view;
         if ( ! Sentry::check())
		{
		    return false;
		}
    }	
}