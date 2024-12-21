<?php
/**
 * @package   com_zoo
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl.html GNU/GPL
 */

/*
	Class: ConfigurationController
		The controller class for application configuration
*/

use Joomla\Archive\Archive;
use Joomla\Archive\Zip;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Table\Table;

class ConfigurationController extends AppController {

	public $application;

	public function __construct($default = array()) {
		parent::__construct($default);

		// set table
		$this->table = $this->zoo->table->application;

		// get application
		$this->application 	= $this->zoo->zoo->getApplication();

		// check ACL
		if (!$this->application->isAdmin()) {
			throw new ConfigurationControllerException("Invalid Access Permissions!", 1);
		}

		// set base url
		$this->baseurl = $this->zoo->link(array('controller' => $this->controller), false);

		// register tasks
		$this->registerTask('applyassignelements', 'saveassignelements');
		$this->registerTask('apply', 'save');
	}

	public function display($cachable = false, $urlparams = false) {

		// set toolbar items
		$this->zoo->system->application->JComponentTitle = $this->application->getToolbarTitle(Text::_('Config'));
		$this->zoo->toolbar->apply();
		$this->zoo->zoo->toolbarHelp();

		// get params
		$this->params = $this->application->getParams();

		// template select
		$options = array($this->zoo->html->_('select.option', '', '- '. Text::_('Select Template').' -'));
		foreach ($this->application->getTemplates() as $template) {
			$options[] = $this->zoo->html->_('select.option', $template->name, $template->getMetaData('name'));
		}

		$this->lists['select_template'] = $this->zoo->html->_('select.genericlist',  $options, 'template', '', 'value', 'text', $this->params->get('template'));

		// get permission form
		$xml = simplexml_load_file(JPATH_COMPONENT . '/models/forms/permissions.xml');
		$xml->fieldset->field->attributes()->name = 'rules_application';

		$this->permissions = Form::getInstance('com_zoo.new', $xml->asXML());
		$this->permissions->bind(array('asset_id' => $this->application->asset_id));
		$this->assetPermissions = array();
		$asset = Table::getInstance('Asset');

		foreach ($this->application->getTypes() as $typeName => $type) {
			$xml->fieldset->field->attributes()->section = 'type';
			$xml->fieldset->field->attributes()->name = 'rules_' . $typeName;
			$this->assetPermissions[$typeName] = Form::getInstance('com_zoo.new.' . $typeName, $xml->asXML());

			if ($asset->loadByName($type->getAssetName())) {
				$this->assetPermissions[$typeName]->bind(array('asset_id' => $asset->id));
			}
		}

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

			// set params
			$params = $this->application
				->getParams()
				->remove('global.')
				->set('template', @$post['template'])
				->set('global.config.', @$post['params']['config'])
				->set('global.template.', @$post['params']['template']);

			if (isset($post['addons']) && is_array($post['addons'])) {
				foreach ($post['addons'] as $addon => $value) {
					$params->set("global.$addon.", $value);
				}
			}

			// save application
			$this->table->save($this->application);

			// set redirect message
			$msg = Text::_('Application Saved');

		} catch (AppException $e) {

			// raise notice on exception
			$this->zoo->error->raiseNotice(0, Text::_('Error Saving Application').' ('.$e.')');
			$msg = null;

		}

		$this->setRedirect($this->baseurl, $msg);
	}

	public function getApplicationParams() {

		// init vars
		$template     = $this->zoo->request->getCmd('template');

		// get params
		$this->params = $this->application->getParams();

        // get permission form
        $xml = simplexml_load_file(JPATH_COMPONENT . '/models/forms/permissions.xml');
		$xml->fieldset->field->attributes()->name = 'rules_application';

        $this->permissions = Form::getInstance('com_zoo.new', $xml->asXML());
        $this->permissions->bind(array('asset_id' => $this->application->asset_id));
        $this->assetPermissions = array();
        $asset = Table::getInstance('Asset');

        foreach ($this->application->getTypes() as $typeName => $type) {
            $xml->fieldset->field->attributes()->section = 'type';
            $xml->fieldset->field->attributes()->name = 'rules_' . $typeName;
            $this->assetPermissions[$typeName] = Form::getInstance('com_zoo.new.' . $typeName, $xml->asXML());

            if ($asset->loadByName($type->getAssetName())) {
				$this->assetPermissions[$typeName]->bind(array('asset_id' => $asset->id));
			}
        }

		// set template
		$this->params->set('template', $template);

		// display view
		$this->getView()->setLayout('_applicationparams')->display();
	}

	public function importExport() {

		// set toolbar items
		$this->zoo->system->application->JComponentTitle = $this->application->getToolbarTitle(Text::_('Import / Export'));
		$this->zoo->zoo->toolbarHelp();

		$this->exporter = $this->zoo->export->getExporters('Zoo v2');

		// display view
		$this->getView()->setLayout('importexport')->display();
	}

	public function importFrom() {

		// check for request forgeries
		$this->zoo->session->checkToken() or jexit('Invalid Token');

		$exporter = $this->zoo->request->getString('exporter');

		try {

			$xml = $this->zoo->export->create($exporter)->export();

			$file = rtrim($this->zoo->system->config->get('tmp_path'), '\/') . '/' . $this->zoo->utility->generateUUID() . '.tmp';
			if (File::exists($file)) {
				File::delete($file);
			}
			File::write($file, $xml);

		} catch (Exception $e) {

			// raise error on exception
			$this->zoo->error->raiseNotice(0, Text::_('Error During Export').' ('.$e.')');
			$this->setRedirect($this->baseurl.'&task=importexport');
			return;

		}

		$this->_import($file);

	}

	public function import() {

		// check for request forgeries
		$this->zoo->session->checkToken() or jexit('Invalid Token');

		$userfile = null;

		$jsonfile = $this->input->files->get('import-json', array());

		try {

			// validate
			$validator = $this->zoo->validator->create('file', array('extensions' => array('json')));
			$userfile = $validator->clean($jsonfile);
			$type = 'json';

		} catch (AppValidatorException $e) {}

		$csvfile = $this->input->files->get('import-csv', array());

		try {

			// validate
			$validator = $this->zoo->validator->create('file', array('extensions' => array('csv')));
			$userfile = $validator->clean($csvfile);
			$type = 'csv';

		} catch (AppValidatorException $e) {}

		if (!empty($userfile)) {
			$file = rtrim($this->zoo->system->config->get('tmp_path'), '\/') . '/' . basename($userfile['tmp_name']);
			if (File::upload($userfile['tmp_name'], $file)) {

				$this->_import($file, $type);

			} else {
				// raise error on exception
				$this->zoo->error->raiseNotice(0, Text::_('Error Importing (Unable to upload file.)'));
				$this->setRedirect($this->baseurl.'&task=importexport');
				return;
			}
		} else {
			// raise error on exception
			$this->zoo->error->raiseNotice(0, Text::_('Error Importing (Unable to upload file.)'));
			$this->setRedirect($this->baseurl.'&task=importexport');
			return;
		}


	}

	public function importCSV() {

		$file = $this->zoo->request->getCmd('file', '');
		$file = rtrim($this->zoo->system->config->get('tmp_path'), '\/') . '/' . $file;

		$this->_import($file, 'importcsv');
	}

	protected function _import($file, $type = 'json') {

		// disable menu
		$this->zoo->request->setVar('hidemainmenu', 1);

		// set toolbar items
		$this->zoo->system->application->JComponentTitle = $this->application->getToolbarTitle(Text::_('Import').': '.$this->application->name);
		$this->zoo->toolbar->cancel('importexport', 'Cancel');
		$this->zoo->zoo->toolbarHelp();

		// set_time_limit doesn't work in safe mode
        if (!ini_get('safe_mode')) {
		    @set_time_limit(0);
        }

		$layout = '';
		switch ($type) {
			case 'xml':
				$this->zoo->error->raiseWarning(0, 'XML import is not supported since ZOO 2.5!');
				$this->importExport();
				break;
			case 'json':
				if (File::exists($file) && $data = $this->zoo->data->create(file_get_contents($file))) {

					$this->info = $this->zoo->import->getImportInfo($data);
					$this->file = basename($file);

				} else {

					// raise error on exception
					$this->zoo->error->raiseNotice(0, Text::_('Error Importing (Not a valid JSON file)'));
					$this->setRedirect($this->baseurl.'&task=importexport');
					return;

				}
				$layout = 'importjson';
				break;
			case 'csv':

				$this->file = basename($file);

				$layout = 'configcsv';
				break;
			case 'importcsv':
				$this->contains_headers = $this->zoo->request->getBool('contains-headers', false);
				$this->field_separator	= $this->zoo->request->getString('field-separator', ',');
				$this->field_separator	= empty($this->field_separator) ? ',' : substr($this->field_separator, 0, 1);
				$this->field_enclosure	= $this->zoo->request->getString('field-enclosure', '"');
				$this->field_enclosure	= empty($this->field_enclosure) ? '"' : substr($this->field_enclosure, 0, 1);

				$this->info = $this->zoo->import->getImportInfoCSV($file, $this->contains_headers, $this->field_separator, $this->field_enclosure);
				$this->file = basename($file);

				$layout = 'importcsv';
				break;
		}

		// display view
		$this->getView()->setLayout($layout)->display();

	}

	public function doImport() {

		// init vars
		$import_frontpage   = $this->zoo->request->getBool('import-frontpage', false);
		$import_categories  = $this->zoo->request->getBool('import-categories', false);
		$frontpage_assignment = $this->zoo->request->get('frontpage-assign', 'array', array());
		$category_assignment = $this->zoo->request->get('category-assign', 'array', array());
		$element_assignment = $this->zoo->request->get('element-assign', 'array', array());
		$types				= $this->zoo->request->get('types', 'array', array());
		$file 				= $this->zoo->request->getCmd('file', '');
		$file 				= rtrim($this->zoo->system->config->get('tmp_path'), '\/') . '/' . $file;

		if (File::exists($file)) {
			$this->zoo->import->import($file, $import_frontpage, $import_categories, $element_assignment, $types, $frontpage_assignment, $category_assignment);
		}

		$this->setRedirect($this->baseurl.'&task=importexport', Text::_('Import successfull'));
	}

	public function doImportCSV() {

		// init vars
		$contains_headers   = $this->zoo->request->getBool('contains-headers', false);
		$field_separator    = $this->zoo->request->getString('field-separator', ',');
		$field_enclosure    = $this->zoo->request->getString('field-enclosure', '"');
		$element_assignment = $this->zoo->request->get('element-assign', 'array', array());
		$type				= $this->zoo->request->getCmd('type', '');
		$file 				= $this->zoo->request->getCmd('file', '');
		$file 				= rtrim($this->zoo->system->config->get('tmp_path'), '\/') . '/' . $file;

		if (File::exists($file)) {
			$this->zoo->import->importCSV($file, $type, $contains_headers, $field_separator, $field_enclosure, $element_assignment);
		}

		$this->setRedirect($this->baseurl.'&task=importexport', Text::_('Import successfull'));
	}

	public function doExport() {

		$exporter = $this->zoo->request->getCmd('exporter');

		if ($exporter) {

			try {

				// set_time_limit doesn't work in safe mode
		        if (!ini_get('safe_mode')) {
				    @set_time_limit(0);
		        }

				$json = $this->zoo->export->create($exporter)->export();

				header("Pragma: public");
		        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		        header("Expires: 0");
		        header("Content-Transfer-Encoding: binary");
				header ("Content-Type: application/json");
				header('Content-Disposition: attachment;'
				.' filename="'. OutputFilter::stringURLSafe($this->application->name).'.json";'
				);

				echo $json;

			} catch (AppExporterException $e) {

				// raise error on exception
				$this->zoo->error->raiseNotice(0, Text::_('Error Exporting').' ('.$e.')');
				$this->setRedirect($this->baseurl.'&task=importexport');
				return;

			}
		}
	}

	public function doExportCSV() {

		//init vars
		$files = array();
        $zipFiles = array();

		try {

			foreach ($this->application->getTypes() as $type) {
				if ($file = $this->zoo->export->toCSV($type)) {
					$files[] = $file;
                    $zipFiles[] = ['name' => basename($file), 'data' => file_get_contents($file)];
				}
			}

			if (empty($files)) {
				throw new AppException(Text::sprintf('There are no items to export'));
			}

            /** @var Zip $zip */
            $zip = (new Archive)->getAdapter('zip');
            $filepath = $this->zoo->path->path("tmp:").'/'.$this->application->getGroup().'.zip';
            $zip->create($filepath, $zipFiles);
			if (is_readable($filepath) && File::exists($filepath)) {
				$this->zoo->filesystem->output($filepath);
				$files[] = $filepath;
				foreach ($files as $file) {
					if (File::exists($file)) {
						File::delete($file);
					}
				}
			} else {
				throw new AppException(Text::sprintf('Unable to create file %s', $filepath));
			}

		} catch (AppException $e) {
				// raise error on exception
				$this->zoo->error->raiseNotice(0, Text::_('Error Exporting').' ('.$e.')');
				$this->setRedirect($this->baseurl.'&task=importexport');
				return;
		}

	}

}

/*
	Class: ConfigurationControllerException
*/
class ConfigurationControllerException extends AppException {}
