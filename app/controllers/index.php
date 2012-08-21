<?php

class IndexController extends BaseController
{
	public function IndexAction()
	{
		Tpl::add("count_documents", Document::count());
		Tpl::add("count_tags", Tag::count());
		Tpl::add("count_pivots", Tag::countPivots());

	}
}