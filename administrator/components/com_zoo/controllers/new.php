<?php
/**
 * @package   com_zoo
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl.html GNU/GPL
 */

/*
	Class: NewController
		The controller class for creating a new application
*/

use Joomla\CMS\Language\Text;

class NewController extends AppController {

	public $group;
	public $application;

	public function __construct($default = array()) {
		parent::__construct($default);

        // check ACL
        if (!$this->zoo->user->isAdmin()) {
            throw new NewControllerException("Invalid Access Permissions!", 1);
        }

		// get application group
		$this->group = $this->zoo->request->getString('group');

		// set base url
		$this->baseurl = $this->zoo->link(array('controller' => $this->controller), false);

		// if group exists
		if ($this->group) {

			// add group to base url
			$this->baseurl .= '&group='.$this->group;

			// create application object
			$this->application = $this->zoo->object->create('Application');
			$this->application->setGroup($this->group);
		}
	}

	public function display($cachable = false, $urlparams = false) {

		// set toolbar items
		$this->zoo->toolbar->title(Text::_('New App'), $this->zoo->get('icon'));
		$this->zoo->zoo->toolbarHelp();

		// get applications
		$this->applications = $this->zoo->application->groups();
        uasort($this->applications, function ($a, $b) {
            return strtotime($b->getMetadata('creationdate')) - strtotime($a->getMetadata('creationdate'));
        });

		// display view
		$this->getView()->display();
	}

	public function add() {

		// disable menu
		$this->zoo->request->setVar('hidemainmenu', 1);

		// set toolbar items
		$this->zoo->system->application->JComponentTitle = $this->application->getToolbarTitle(Text::_('New App').': '.$this->application->getMetaData('name'));
		$this->zoo->toolbar->save();
		$this->zoo->toolbar->cancel('cancel', 'Cancel');
		$this->zoo->zoo->toolbarHelp();

		// get params
		$this->params = $this->application->getParams();

		// check for Warp7 and set default template
		$templates      = $this->application->getTemplates();
		$defaulTemplate = $this->zoo->database->queryResult("SELECT template FROM #__template_styles WHERE client_id = 0 AND home = 1");

		if (isset($templates['uikit']) && file_exists(JPATH_ROOT . '/templates/' . $defaulTemplate . '/warp.php')) {
		    $this->params->set('template', 'uikit');
		} elseif (isset($templates['uikit3']) && $defaulTemplate == 'yootheme') {
			$this->params->set('template', 'uikit3');
		} elseif (isset($templates['default'])) {
		    $this->params->set('template', 'default');
		}

		// template select
		$options = array($this->zoo->html->_('select.option', '', '- '.Text::_('Select Template').' -'));
		foreach ($templates as $template) {
			$options[] = $this->zoo->html->_('select.option', $template->name, $template->getMetaData('name'));
		}

		$this->lists['select_template'] = $this->zoo->html->_('select.genericlist',  $options, 'template', '', 'value', 'text', $this->params->get('template'));

		// display view
		$this->getView()->setLayout('application')->display();
	}

	public function save() {

		// check for request forgeries
		$this->zoo->session->checkToken() or jexit('Invalid Token');

		// init vars
		$post = $this->zoo->request->get('post:', 'array');

		try {

			// bind post
			self::bind($this->application, $post, array('params'));

			// Force alias
			if (!strlen(trim($this->application->alias))) {
				$this->application->alias = $this->zoo->string->sluggify($this->application->name);
			}
			$this->application->alias = $this->zoo->alias->application->getUniqueAlias($this->application->id, $this->zoo->string->sluggify($this->application->alias));

			// set params
			$params = $this->application
				->getParams()
				->remove('global.')
				->set('group', @$post['group'])
				->set('template', @$post['template'])
				->set('global.config.', @$post['params']['config'])
				->set('global.template.', @$post['params']['template']);

			if (isset($post['addons']) && is_array($post['addons'])) {
				foreach ($post['addons'] as $addon => $value) {
					$params->set("global.$addon.", $value);
				}
			}

			// add empty rules to application object
			$this->application->rules = array();
			foreach ($this->application->getTypes() as $typeName => $type) {
				$this->application->assetRules[$typeName] = array();
			}

			// save application
			$this->zoo->table->application->save($this->application);

			// set redirect
			$msg  = Text::_('Application Saved');
			$link = $this->zoo->link(array('changeapp' => $this->application->id), false);

		} catch (AppException $e) {

			// raise notice on exception
			$this->zoo->error->raiseNotice(0, Text::_('Error Saving Application').' ('.$e.')');

			// set redirect
			$msg  = null;
			$link = $this->baseurl.'&task=add';

		}

		$this->setRedirect($link, $msg);
	}

	public function getApplicationParams() {

		// init vars
		$template     = $this->zoo->request->getCmd('template');
		$this->params = $this->application->getParams();

		// set template
		$this->params->set('template', $template);

		// display view
		$this->getView()->setLayout('_applicationparams')->display();
	}

}

/*
	Class: NewControllerException
*/
class NewControllerException extends AppException {}
