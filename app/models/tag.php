<?php

class Tag extends BaseModel
{
	public function __construct($tags)
	{
		// -----------------------------------------
		// Build up query based on selected tags
		// -----------------------------------------
		foreach($tags as $k=>$v)
		{
			if($v == "") unset($tags[$k]);
		}
		$this->boolean = $tags;
		$this->boolean_prefix = implode("/", $tags);

		$id = $tags[count($tags)-1];
		$all_tags_str = implode("','", $tags);
	
		$all_tags_count = count($tags);

		// -----------------------------------------
		// Get the active tag from the database
		// -----------------------------------------
		global $db;
		$row = $db->get_row("SELECT * FROM tags WHERE name = '{$id}'");
		
		if(count($row) == 0) return false;

		foreach($row as $k=>$v)
		{
			$this->$k = $v;
		}

		// -----------------------------------------
		// Fetch relevant documents
		// -----------------------------------------
		$sql = "SELECT document_id, name 
			    FROM pivot 
			    INNER JOIN documents ON pivot.document_id = documents.id 
			    WHERE pivot.tag_id IN ('$all_tags_str') 
			    GROUP BY document_id 
			    HAVING COUNT(DISTINCT tag_id) = $all_tags_count";

		$documents = $db->get_results($sql, ARRAY_A);
		$this->documents = $documents;


		// -----------------------------------------
		// Find the tags related to this tag
		// -----------------------------------------
		if(count($this->documents) > 0)
		{
			foreach($this->documents as $k=>$v)
			{
				$document_list[] = $v['document_id'];
			}

			$document_list_str = implode(",", $document_list);

			$sql = "SELECT tag_id, count(*) as popularity 
					FROM pivot 
					WHERE tag_id NOT IN ('$all_tags_str') 
					AND document_id IN ($document_list_str) 
					GROUP BY tag_id 
					ORDER BY popularity DESC";

			$related_tags = $db->get_results($sql, ARRAY_A);
			$this->related_tags = $related_tags;
		}
		else 
		{
			$this->related_tags = array();
		}
	}

	static public function getAll($popularity_threshold)
	{
		global $db;
		$all = $db->get_results("SELECT tag_id as name, count(*) as popularity FROM pivot WHERE tag_id <> '' GROUP BY tag_id HAVING popularity > {$popularity_threshold} ORDER BY popularity DESC", ARRAY_A);
		return $all;
	}

	static public function maxPopularity()
	{
		global $db;
		$c = $db->get_col("SELECT count(*) AS popularity FROM pivot WHERE tag_id <> '' GROUP BY tag_id ORDER BY popularity DESC LIMIT 1");
		return $c[0];
	}

	static public function count()
	{
		global $db;
		$c = $db->get_col("SELECT count(*) FROM tags");
		return $c[0];
	}

	static public function countPivots()
	{
		global $db;
		$c = $db->get_col("SELECT count(*) FROM pivot");
		return $c[0];
	}
}