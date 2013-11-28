<?php
/* Portions copyright © 2013, TIBCO Software Inc.
 * All rights reserved.
 */
?>
<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_logs
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Methods supporting a list of logs logs.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_logs
 * @since       1.6
 */
class LogsModelLogs extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  An optional associative array of configuration settings.
	 * @see     JController
	 * @since   1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'uid', 'a.uid',
				'is_show', 'a.is_show',
				'log_type', 'a.log_type',
				'content', 'a.content',
				'http_status_text', 'a.http_status_text',
				'http_status', 'a.http_status',
				'http_response_text', 'a.http_response_text',
				'entity_type', 'a.entity_type',
				'entity_id', 'a.entity_id',
				'event', 'a.event',
				'event_id', 'a.event_id',
				'content', 'a.content',
				'create_time', 'a.create_time',
				'published' , 'a.published',
				'status' , 'a.status'
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$state = $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_state', '', 'string');
		$this->setState('filter.state', $state);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_logs');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.log_type', 'asc');
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string	A prefix for the store id.
	 *
	 * @return  string	A store id.
	 * @since   1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.state');

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  JDatabaseQuery
	 * @since   1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.*'
			)
		);
		$query->from($db->quoteName('asg_logs').' AS a');
		// Filter by published state
		$state = $this->getState('filter.state');
		if (is_numeric($state))
		{
			$query->where('a.published = '.(int) $state);
		} elseif ($state === '')
		{
			$query->where('(a.published IN (0,1,2))');
		}

		// Filter the items over the search string if set.
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where(
					'('.$db->quoteName('content').' LIKE '.$search.
					' OR '.$db->quoteName('a.uid').' LIKE '.$search.')'
					
				);
			}
		}

		// Add the list ordering clause.
		$query->order($db->escape($this->getState('list.ordering', 'a.log_type')).' '.$db->escape($this->getState('list.direction', 'ASC')));

		return $query;
	}
}
