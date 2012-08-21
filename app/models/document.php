<?php

class Document extends BaseModel
{
	public function __construct($id)
	{
		global $db;
		$row = $db->get_row("SELECT * FROM documents WHERE id = '{$id}'");
		
		if(count($row) == 0) return false;

		foreach($row as $k=>$v)
		{
			$this->$k = $v;
		}

		$tags = $db->get_col("SELECT tag_id FROM pivot WHERE document_id = '{$id}'");
		$this->tags = $tags;
	}

	static public function count()
	{
		global $db;
		$c = $db->get_col("SELECT count(*) FROM documents");
		return $c[0];
	}
}