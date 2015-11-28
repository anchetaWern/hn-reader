<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;
use GuzzleHttp\Client;

class UpdateNewsItems extends Command
{

    protected $name = 'update:news_items';

    public function fire()
    {
        

		$client = new Client(array(
			'base_uri' => 'https://hacker-news.firebaseio.com'
		));

    	$endpoints = array(
    		'top' => '/v0/topstories.json',
    		'ask' => '/v0/askstories.json',
    		'job' => '/v0/jobstories.json',
    		'show' => '/v0/showstories.json',
    		'new' => '/v0/newstories.json'
    	);

		foreach($endpoints as $type => $endpoint){

			$response = $client->get($endpoint);
			$result = $response->getBody();

			$items = json_decode($result, true);
				    
		    foreach($items as $id){
		        
		        $item_res = $client->get("/v0/item/" . $id . ".json");
		        $item_data = json_decode($item_res->getBody(), true);

		        if(!empty($item_data)){

					$item = array(	
						'id' => $id,
					    'title' => $item_data['title'],
					    'item_type' => $item_data['type'],
					    'username' => $item_data['by'],
					    'score' => $item_data['score'],
					    'time_stamp' => $item_data['time'],
					);

					$item['is_' . $type] = true;

					if(!empty($item_data['text'])){
						$item['description'] = strip_tags($item_data['text']);
					}

					if(!empty($item_data['url'])){
						$item['url'] = $item_data['url'];
					}
				    
			        $db_item = DB::table('items')
			            ->where('id', '=', $id)
			            ->first();

			        if(empty($db_item)){

			            DB::table('items')->insert($item);

			        }else{
			        	
			        	DB::table('items')->where('id', $id)
			        		->update($item);
			        }
		        }

		    }
		}
		return 'ok';

    }
}