<?php
/**
 * @package   com_zoo
 * @author    YOOtheme https://yootheme.com
 * @copyright Copyright (C) YOOtheme GmbH
 * @license   https://www.gnu.org/licenses/gpl.html GNU/GPL
 */

use Joomla\CMS\Factory;
use Joomla\CMS\User\User;

/**
 * Helper to deal with user and user operations
 *
 * @package Framework.Helpers
 */
class UserAppHelper extends AppHelper {

	protected $_queried_users = array();

	/**
	 * Get the helper name
	 *
	 * @return string The name of the helper
	 *
	 * @since 1.0.0
	 */
	public function getName() {
		return 'user';
	}

	/**
	 * Get a user object
	 *
	 * @param int The id of the user to retrieve (default: the current user)
	 *
	 * @return User The JUser object
	 *
	 * @since 1.0.0
	 */
	public function get($id = null) {

		// get database
		$db = $this->app->database;

		// check if user id exists
		if (!is_null($id) && !in_array($id, $this->_queried_users) && !$db->queryResult('SELECT id FROM #__users WHERE id = '.$db->escape($id))) {
			return null;
		}

		$this->_queried_users[$id] = $id;

		// get user
		$user = $this->_call(array(Factory::class, 'getUser'), array($id));

		// add super administrator var to user
		$user->superadmin = $this->isJoomlaSuperAdmin($user);

		return $user;
	}

	/**
	 * Retrieve a user by his username
	 *
	 * @param string $username The username
	 *
	 * @return User The JUser object
	 *
	 * @since 1.0.0
	 */
	public function getByUsername($username) {

		// get database
		$db = $this->app->database;

		// search username
		if ($id = $db->queryResult('SELECT id FROM #__users WHERE username = '.$db->Quote($username))) {
			return $this->get($id);
		}

		return null;
	}

	/**
	 * Retrieve a user by his email
	 *
	 * @param string $email The email
	 *
	 * @return User The JUser object
	 *
	 * @since 1.0.0
	 */
	public function getByEmail($email) {

		// get database
		$db = $this->app->database;

		// search email
		if ($id = $db->queryResult('SELECT id FROM #__users WHERE email = '.$db->Quote($email))) {
			return $this->get($id);
		}

		return null;
	}

	/**
	 * The a value from the user state
	 *
	 * @param string $key The name of the variable to retrieve
	 *
	 * @return mixed The value of the variable
	 *
	 * @since 1.0.0
	 */
	public function getState($key) {
		$registry = $this->app->session->get('registry');

		if (!is_null($registry)) {
			return $registry->get($key);
		}

		return null;
	}

	/**
	 * Set a variable in the user state
	 *
	 * @param string $key The variable name
	 * @param mixed $value The value to set
	 *
	 * @return mixed The value of the variable
	 *
	 * @since 1.0.0
	 */
	public function setState($key, $value) {
		$registry = $this->app->session->get('registry');

		if (!is_null($registry)) {
			return $registry->setValue($key, $value);
		}

		return null;
	}

	/**
	 * Get a value from the user state, checking also the request
	 *
	 * @param string $key The name of the variable
	 * @param string $request The name of the request variable
	 * @param mixed $default The default value
	 * @param string $type The type of the variable
	 *
	 * @return mixed The value of the variable
	 *
	 * @since 1.0.0
	 */
	public function getStateFromRequest($key, $request, $default = null, $type = 'none') {

		$old = $this->getState($key);
		$cur = (!is_null($old)) ? $old : $default;
		$new = $this->app->request->getVar($request, null, 'default', $type);

		if ($new !== null) {
			$this->setState($key, $new);
		} else {
			$new = $cur;
		}

		return $new;
	}

	/**
	 * Check if a username already exists
	 *
	 * @param string $username The username to check
	 * @param int $id A id to confront with the loaded user id (default: 0)
	 *
	 * @return boolean If the username exists
	 *
	 * @since 1.0.0
	 */
	public function checkUsernameExists($username, $id = 0) {
		$user = $this->getByUsername($username);
		return $user && $user->id != intval($id);
	}

	/**
	 * Check if an email already exists
	 *
	 * @param string $email The email to check
	 * @param int $id A id to confront with the loaded user id (default: 0)
	 *
	 * @return boolean If the email exists
	 *
	 * @since 1.0.0
	 */
	public function checkEmailExists($email, $id = 0) {
		$user = $this->getByEmail($email);
		return $user && $user->id != intval($id);
	}

	/**
	 * Check if a user is a joomla administrator
	 *
	 * @param User $user The user to check
	 *
	 * @return boolean if the user is an administrator
	 *
	 * @since 1.0.0
	 */
    public function isJoomlaAdmin(User $user) {
		return $user->authorise('core.login.admin', 'root.1');
    }

	/**
	 * Check if the user is a joomla super administrator
	 *
	 * @param User $user The user to check
	 *
	 * @return boolean If the user is a super administrator
	 *
	 * @since 1.0.0
	 */
    public function isJoomlaSuperAdmin(User $user) {
		return $user->authorise('core.admin', 'root.1');
    }

	/**
	 * Get the user's browser default language
	 *
	 * @return string The language code
	 *
	 * @since 1.0.0
	 */
	public function getBrowserDefaultLanguage() {
		$langs = array();

		if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {

			preg_match_all('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $lang_parse);

			if (count($lang_parse[1])) {

				$langs = array_combine($lang_parse[1], $lang_parse[4]);

				foreach ($langs as $lang => $val) {
					if ($val === '') $langs[$lang] = 1;
				}

				arsort($langs, SORT_NUMERIC);
			}
		}

		return explode('-', array_keys($langs)[0])[0];

	}

	/**
	 * Check if a user can access a resource
	 *
	 * @param User $user The user to check
	 * @param int $access The access level to check against
	 *
	 * @return boolean If the user have the rights to access that level
	 *
	 * @since 1.0.0
	 */
	public function canAccess($user = null, $access = 0) {

		if (is_null($user)) {
			$user = $this->get();
		}

		return in_array($access, $user->getAuthorisedViewLevels());

	}

	/**
	 * Evaluates user permission
	 *
	 * @param User $user User Object
	 * @param int $asset_id
	 * @param int $created_by
	 *
	 * @return boolean True if user has permission
	 *
	 * @since 3.2
	 */
	public function canEdit($user = null, $asset_id = 0, $created_by = 0) {
		if (is_null($user)) {
			$user = $this->get();
		}
		return $this->isAdmin($user, $asset_id) || $this->authorise($user, 'core.edit', $asset_id) || ((int) $created_by === (int) $user->id && $user->authorise('core.edit.own', $asset_id));
	}

	/**
	 * Evaluates user permission
	 *
	 * @param User $user User Object
	 * @param int $asset_id
	 *
	 * @return boolean True if user has permission
	 *
	 * @since 3.2
	 */
	public function canEditState($user = null, $asset_id = 0) {
		return $this->isAdmin($user, $asset_id) || $this->authorise($user, 'core.edit.state', $asset_id);
	}

	/**
	 * Evaluates user permission
	 *
	 * @param User $user User Object
	 * @param int $asset_id
	 *
	 * @return boolean True if user has permission
	 *
	 * @since 3.2
	 */
	public function canCreate($user = null, $asset_id = 0) {
		return $this->isAdmin($user, $asset_id) || $this->authorise($user, 'core.create', $asset_id);
	}

	/**
	 * Evaluates user permission
	 *
	 * @param User $user User Object
	 * @param int $asset_id
	 *
	 * @return boolean True if user has permission
	 *
	 * @since 3.2
	 */
	public function canDelete($user = null, $asset_id = 0) {
		return $this->isAdmin($user, $asset_id) || $this->authorise($user, 'core.delete', $asset_id);
	}

	/**
	 * Evaluates user permission
	 *
	 * @param User $user User Object
	 * @param int $asset_id
	 *
	 * @return boolean True if user has permission
	 *
	 * @since 3.2
	 */
	public function canManage($user = null, $asset_id = 0) {
		return $this->isAdmin($user, $asset_id) || $this->authorise($user, 'core.manage', $asset_id);
	}

	/**
	 * Evaluates user permission
	 *
	 * @param User $user User Object
	 * @param int $asset_id
	 *
	 * @return boolean True if user has permission
	 *
	 * @since 3.2
	 */
	public function isAdmin($user = null, $asset_id = 0) {
		return $this->authorise($user, 'core.admin', $asset_id);
	}

	/**
	 * Evaluates user permission
	 *
	 * @param User $user User Object
	 * @param int $asset_id
	 *
	 * @return boolean True if user has permission
	 *
	 * @since 3.2
	 */
	public function canManageCategories($user = null, $asset_id = 0) {
		return $this->isAdmin($user, $asset_id) ||  $this->authorise($user, 'zoo.categories.manage', $asset_id);
	}

	/**
	 * Evaluates user permission
	 *
	 * @param User $user User Object
	 * @param int $asset_id
	 *
	 * @return boolean True if user has permission
	 *
	 * @since 3.2
	 */
	public function canManageComments($user = null, $asset_id = 0) {
		return $this->isAdmin($user, $asset_id) ||  $this->authorise($user, 'zoo.comments.manage', $asset_id);
	}

	/**
	 * Evaluates user permission
	 *
	 * @param User $user User Object
	 * @param int $asset_id
	 *
	 * @return boolean True if user has permission
	 *
	 * @since 3.2
	 */
	public function canManageFrontpage($user = null, $asset_id = 0) {
		return $this->isAdmin($user, $asset_id) ||  $this->authorise($user, 'zoo.frontpage.manage', $asset_id);
	}

	/**
	 * Evaluates user permission
	 *
	 * @param User $user User Object
	 * @param int $asset_id
	 *
	 * @return boolean True if user has permission
	 *
	 * @since 3.2
	 */
	public function canManageTags($user = null, $asset_id = 0) {
		return $this->isAdmin($user, $asset_id) || $this->authorise($user, 'zoo.tags.manage', $asset_id);
	}

	/**
	 * Wrapper method to get the users database access string
	 *
	 * @param User $user The user
	 *
	 * @return string The part of the sql query that checks the user rights
	 *
	 * @since 1.0.0
	 */
	public function getDBAccessString($user = null) {

		if (is_null($user)) {
			$user = $this->get();
		}

		$groups	= implode(',', array_unique($user->getAuthorisedViewLevels()));
		return "access IN ($groups)";

	}

	/**
	 * Evaluates user permission
	 *
	 * @param User $user User Object
	 * @param string $action
	 * @param int $asset_id
	 *
	 * @return boolean True if user has permission
	 *
	 * @since 3.2
	 */
	protected function authorise($user, $action, $asset_id) {
		if (!$asset_id) {
			$asset_id = 'com_zoo';
		}
		if (is_null($user)) {
			$user = $this->get();
		}

		return (bool) $user->authorise($action, $asset_id);
	}
}
