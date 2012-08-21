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
	}
}