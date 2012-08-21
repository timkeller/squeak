<?php

class DocumentController extends BaseController
{
	public function IndexAction()
	{

	}

	public function ViewAction($id)
	{
		global $template_vars;
		$document = new Document($id);
		Tpl::add("document", $document);
	}
}