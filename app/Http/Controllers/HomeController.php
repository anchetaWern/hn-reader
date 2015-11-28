<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use DB;


class HomeController extends BaseController {

	private $types;

	public function __construct(){
		$this->types = array(
			'top',
			'ask',
			'job',
			'new',
			'show'
		);
	}


	public function index($type = 'top'){

		$items = DB::table('items')
			->where('is_' . $type, true)
			->get();

		$page_data = array(
			'title' => $type,
			'types' => $this->types,
			'items' => $items
		);

		return view('home', $page_data);
	}

}
