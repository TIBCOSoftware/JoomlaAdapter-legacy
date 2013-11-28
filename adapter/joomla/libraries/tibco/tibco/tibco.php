<?php
  /* @copyright Copyright © 2013, TIBCO Software Inc. All rights reserved.
 * @license GNU General Public License version 2; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Library of functions specific to TIBCO Software's OpenAPI
 *
 * @since       1.0
 */
class TibcoTibco
{
  /**
   * create ping user profile
   * @param array $options: lastName, firstName, org_id, usertype
   */
  public function createUserProfile($options=array())
  {
    include_once JPATH_BASE . "/includes/userprofile.php"; 
    CreateUserprofileApi::insertUserProfile($options);
  }

  /**
   * Creat an organization and get the organization's id
   * @param  array  $options['title'] title of the new created organization
   * @return           [description]
   */
  public function forceGetOrganizationId($options=array()){
    include_once JPATH_BASE . "/includes/organization.php"; 
    return CreateOrganizationApi::insertOganization($options);
  }

}