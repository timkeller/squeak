<?php

class DocumentController extends BaseController
{
	public function IndexAction()
	{

	}

	public function ViewAction($id)
	{
		$document = new Document($id);
	}
}