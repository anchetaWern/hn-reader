<?php

class URLHelper {

	public static function getURL($id, $url = ''){
		if(!empty($url)){
			return $url;
		}
		return "https://news.ycombinator.com/item?id={$id}";
	}
}