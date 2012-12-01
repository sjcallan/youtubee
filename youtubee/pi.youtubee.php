<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$plugin_info = array(
	'pi_name' => 'YouTubEE',
	'pi_version' =>'1.0',
	'pi_author' =>'Steve Callan',
	'pi_author_url' => 'http://www.stevecallan.com/',
	'pi_description' => 'Displays loop of a users YouTube videos.',
	'pi_usage' => youtubee::usage()
);

/**
* Youtubee Class
*
* @package		ExpressionEngine
* @category		Plugin
* @author		Steve Callan
* @copyright	Copyright (c) 2012, Callan Interactive
*/

require_once("domparser.php");

class YouTubee {

	function __construct()
	{
		$this->EE =& get_instance();
	}
	
	function entries()
	{	
	
		/* Intitial Variables */
			$output = "";
			$tag_content = $this->EE->TMPL->tagdata;
			$user = $this->EE->TMPL->fetch_param('user');
			$limit = $this->EE->TMPL->fetch_param('limit');
			$key_limit = $this->EE->TMPL->fetch_param('key');
			$feed_url = "http://gdata.youtube.com/feeds/base/users/" . $user . "/uploads?alt=rss&v=2&orderby=published&client=ytapi-youtube-profile";
			
			if($limit == "")
			{
				$limit = 0;
			}
			
			if($key_limit == "")
			{
				$key_limit = NULL;
			}
		
		/* Get the feed as an array */
			$feed_array = $this->_get_data($feed_url,$limit,$key_limit);
		
			foreach($feed_array AS $video)
			{
				
				$swap = array(
					'title' => $video["title"], 
					'image' => $video["image"], 
					'short_description' => $video["short_description"], 
					'time' => $video["time"],
					"url" => $video["url"],
					"views" => $video["views"],
					"key" => $video["key"]
				);
	  			
	  			$item_content	= $this->EE->functions->prep_conditionals( $tag_content, $swap );
				
				$record_contents = "";
				$record_contents = $this->EE->functions->var_swap($item_content, $swap);
				$output .= $record_contents;
				
			}
		
		/*  AND finally return */
			if($output == "")
			{
				$output = "<p>There were no videos found for this user.</p>";
			}
		
			return $output;
		
	}
	
	function _get_data($feed,$count = 0,$key_limit = NULL)
	{
		
		if($key_limit != NULL)
		{
			$key_array = explode("|",$key_limit);
		}
	
		$feed_array = array();
	
		try{
			$xml_feed = @file_get_contents($feed);
			$xml = new SimpleXMLElement($xml_feed);
			$i = 0;
	
			foreach($xml->channel->item AS $entry){
		
				$push_data = FALSE;
				$entry_data = $this->_parse_item($entry->description);
	
				if($count != 0)
				{
					if($i < $count){
						
						/* If we are filting our keys check if it's in the key array */
						if($key_limit != NULL)
						{
							
							if(in_array($entry_data["key"],$key_array))
							{
								$push_data = TRUE;
							}
							
						}
						else
						{
							$push_data = TRUE;
						}
					}
				}	
				else
				{
					/* If we are filting our keys check if it's in the key array */
						if($key_limit != NULL)
						{
							
							if(in_array($entry_data["key"],$key_array))
							{
								$push_data = TRUE;
							}
							
						}
						else
						{
							$push_data = TRUE;
						}
				}
				
				if($push_data == TRUE)
				{
						
					array_push($feed_array,array(
						"title"=>$entry->title,
						"image"=>$entry_data["image"], 
						"short_description"=>$entry_data["short_description"], 
						"time"=>$entry_data["time"],
						"url"=>$entry_data["url"],
						"views"=>number_format($entry_data["views"]),
						"key" => $entry_data["key"]
					));
				}
	
				$i++;
			}		
	
		}catch (Exception $e) {
			return $feed_array;
		}
		
		return $feed_array;
	
	}
	
	function _parse_item($item)
	{
		//echo($item);
		
		$dom = new domparser;
		$html = $dom->str_get_html("<html><body>" . $item . "</body></html>");
		
		$image = $html->find('img',0)->src;
		$url = $html->find('a',0)->href;
		$short_description = $html->find('td',1)->find('a',0)->innertext;
		$time = $html->find("tr",1)->find("td",0)->find("span",1)->innertext;
		$views = $html->find("tr",0)->find("td",2)->find("div",1)->innertext;
		$views = strip_tags($views);
		$views = str_replace("Views:","",$views);
		
		$key = str_replace("http://www.youtube.com/watch?v=","",$url);
		$key = str_replace("&amp;feature=youtube_gdata","",$key);
	
		$data_array = array(
			"image"=> $image, 
			"short_description"=> $short_description, 
			"time"=>$time,
			"url"=>$url,
			"views"=>$views,
			"key"=>$key
		);
	
		return $data_array;
		
	}
		
	function usage()
	{
	
		ob_start(); 
		?>
		YouTubEE plugin allows you to display the contents of a users YouTube stream.
		
		{exp:youtubee:entries}
			<article>
				<h3>{title}</h3>
				{short_description}
			</article>
		{/exp:youtubee:entries}
		
		Supports:
		title, short_description, image, views, time
		
		<?php
		$buffer = ob_get_contents();
		
		ob_end_clean(); 
		
		return $buffer;
	
	}
	
}