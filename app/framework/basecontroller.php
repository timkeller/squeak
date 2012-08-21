<?php

// -----------------------------------------
// Squeak Framework - Base Controller
// -----------------------------------------

class BaseController 
{
	public function _render($controller='index', $action='index')
	{
		global $twig;
		$template = $twig->loadTemplate("$controller/$action.html.php");
		echo $template->render(array('the' => 'variables', 'go' => 'here'));
	}
}