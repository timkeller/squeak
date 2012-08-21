<?php

class TagController extends BaseController
{
	public function IndexAction()
	{
		$tags = Tag::getAll(50);
		
		// Parameters for Tag Cloud
		$maxPercent = 300;
		$minPercent = 100;
		$max = Tag::maxPopularity();
		$min = 1;

		foreach($tags as $k=>$v)
		{
			$tags[$k]['size'] = $minPercent + (($max-($max-($v['popularity']-$min)))*($maxPercent-$minPercent)/($max-$min));  
		}

		Tpl::add("tags",$tags);
	}

	public function ViewAction($id)
	{
		$args = func_get_args();

		global $template_vars;
		$tag = new Tag($args);

		// Parameters for Tag Cloud
		$maxPercent = 200;
		$minPercent = 90;
		$max = ($tag->related_tags[0]['popularity'] > 1 ? $tag->related_tags[0]['popularity'] : 2);
		$min = 1;

		if(count($tag->related_tags))
		{
			foreach($tag->related_tags as $k=>$v)
			{
				$tag->related_tags[$k]['size'] = $minPercent + (($max-($max-($v['popularity']-$min)))*($maxPercent-$minPercent)/($max-$min));  
			}
		}

		Tpl::add("tag", $tag);
	}

	public function SearchAction()
	{
		$query = $_GET['query'];

		$parts = explode("+", $query);
		foreach($parts as $k => $term)
		{
			$parts[$k] = strtolower(trim($parts[$k]));
			$parts[$k] = str_replace(" ", "-", $parts[$k]);
		}

		$tag_string = implode("/", $parts);
		header("Location: /tag/view/".$tag_string);
	}
}