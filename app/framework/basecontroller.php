<?php

// -----------------------------------------
// Squeak Framework - Base Controller
// -----------------------------------------

class BaseController 
{
	public function _render($request, $template_vars=array())
	{
		global $twig;
		global $tracking_time_start;

		$controller = $request[0];
		$action = $request[1];

		try {
			$template = $twig->loadTemplate("$controller/$action.html");
		}
		catch (Twig_Error_Loader $e)
		{
			Route::error404($e);
		}
		echo $template->render($template_vars+array("time" => microtime(true) - $tracking_time_start));
	}
}