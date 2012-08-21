<?php

// -----------------------------------------
// Squeak Framework - Base Controller
// -----------------------------------------

class BaseController 
{
	/*
	 * Render to a TWIG template name /controller/action.html
	 */
	public function _render($request, $template_vars=array())
	{
		global $twig;
		global $tracking_time_start;

		// Collect destination from router
		$controller = $request[0];
		$action 	= $request[1];

		// Render template using the variables passed
		// by the router
		try {
			$template = $twig->loadTemplate("$controller/$action.html");
		}
		catch (Twig_Error_Loader $e)
		{
			Route::error404($e);
		}

		// How long has this request taken?
		$execution_time = microtime(true) - $tracking_time_start;

		// Output to buffer
		echo $template->render($template_vars + array("time" => $execution_time));
	}
}