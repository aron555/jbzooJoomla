<?php
/**
 * @package   com_zoo
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl.html GNU/GPL
 */

use Joomla\CMS\MVC\View\HtmlView;
use Joomla\Filesystem\Path;

/**
 * Base View class
 *
 * @package Framework.Classes
 *
 * @property App $app
 */
class AppView extends HtmlView {

	/**
	 * Set the layout for the view
	 *
	 * @param string $layout The layout to set
	 *
	 * @return AppView The view itself to allow chaining
	 *
	 * @since 1.0.0
	 */
	public function setLayout($layout) {
		parent::setLayout($layout);
		return $this;
	}

	/**
	 * Adds a path to the template search path list
	 *
	 * @param string $path The path to add
	 *
	 * @return AppView The view itself to allow chaining
	 *
	 * @since 1.0.0
	 */
	public function addTemplatePath($path) {
		parent::addTemplatePath($path);
		return $this;
	}

	/**
	 * Render a partial view template file
	 *
	 * The partial view template filename starts with an underscore (_)
	 * and is meant to render a reusable part of a bigger view
	 *
	 * @param string $name The name of the partial (without the underscore)
	 * @param array $args The list of arguments to pass on to the template
	 *
	 * @return string The output of the rendering
	 *
	 * @since 1.0.0
	 */
	public function partial($name, $args = array()) {

		// clean the file name
		$file = preg_replace('/[^A-Z0-9_\.-]/i', '', '_'.$name);

		// set template path and add global partials
		$path   = $this->_path['template'];
		$path[] = $this->_basePath.'/partials';

		// load the partial
		$__file    = $this->_createFileName('template', array('name' => $file));
		$__partial = Path::find($path, $__file);

		// render the partial
		if ($__partial != false) {

			// import vars and get content
			extract($args);
			ob_start();
			include($__partial);
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}

		return $this->app->error->raiseError(500, 'Partial Layout "'.$__file.'" not found. ('.$this->app->utility->debugInfo(debug_backtrace()).')');
	}

}

/**
 * Dedicated Exception for the AppView class
 *
 * @see AppView
 */
class AppViewException extends AppException {}
