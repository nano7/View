<?php namespace Nano7\View\Engines;

interface EngineInterface {

	/**
	 * Get the evaluated contents of the view.
	 *
	 * @param  string  $path
	 * @param  array   $data
	 * @return string
	 */
	public function get($path, array $data = array());

}
