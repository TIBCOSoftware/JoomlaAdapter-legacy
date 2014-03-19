<?php
  /* @copyright Copyright © 2013, TIBCO Software Inc. All rights reserved.
 * @license GNU General Public License version 2; see LICENSE.txt
 */

defined('_JEXEC') or die;
require_once JPATH_BASE . "/includes/api.php";

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
    return CreateOrganizationApi::insertOrganization($options);
  }

  public static function updateApplicationForPlan($apps, $product, $new_subscription){
    include_once dirname ( __FILE__ ) . DIRECTORY_SEPARATOR . 'autosubplan.php';
    $oldSubscriptions = array();

    foreach ($apps as $app_id) {
        if(TibcoAutoSubPlan::checkProductsForApplication($app_id, $product)){
          $oldSubscriptions[$app_id] = TibcoAutoSubPlan::updateSubscriptionForApplication($app_id, $product, $new_subscription);
        }else{
          if(TibcoAutoSubPlan::bindProductForApplicationId($app_id, $product) && TibcoAutoSubPlan::bindNewSubscriptionForApplicationId($app_id,$new_subscription)){
            $oldSubscriptions[$app_id] = array();
          }
        }
    }
    return $oldSubscriptions;
  }

  /**
   * get organization detail by organization id.
   * @param string $org_id
   * @return array it include three values: API limit usage percentage(field_id=130)
   */
  public function getOrganizationDetailById($org_id) {
    $rv = array();
    $db = JFactory::getDbo();
    $sql = "SELECT field_value FROM #__js_res_record_values WHERE  record_id=".$org_id." AND field_id in (130) ORDER BY id";
    $db -> setQuery($sql);
    if ($result = $db -> loadObjectList()) {
      foreach ($result as $record) {
        array_push($rv, $record -> field_value);
      }
    }
    return $rv;
  }

  public static function getFlagForShow($id=0){
    if($id){
      $db = JFactory::getDbo();
      $query = $db->getQuery(true);
      $query->select("is_show")->from("asg_product_show_map")->where("product_id=".$id);
      $db -> setQuery($query);
      if($result = $db->loadColumn()){
        return $result[0];
      }
    }
  }

  /**
   * Get the id of a user group which belongs to an organization and denotes a special type of users, e.g. a developer, a contact or an administrator.
   *
   * @author Kevin Li<huali@tibco-support.com>
   * @param int $org_id The id of the organization whose groups are to be queried.
   * @param string $user_type The user type that the group denotes. This is optional. The default value is "Member".
   * @return int The id the group which matches the criteria or 0 if no group's been found.
   */
  public static function getGroupByOrgAndType($org_id, $user_type = DeveloperPortalApi::USER_TYPE_MEMBER) {
  	$ret = 0;
		if($org_id) {
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)->select("id")->from("#__usergroups")->where("title like 'Organization " . $org_id . " " . $user_type . "'");
			$db->setQuery($query);
			if($result = $db->loadAssoc()) {
				$ret = $result["id"];
			}
		}
		return $ret;
  }

  /**
   * Associate the given user groups to a view access level. If anyone of the user groups has already been associated
   * to the view access level it'll be left intact.
   *
   * @author Kevin Li<huali@tibco-support.com>
   * @param int $view_level The id of the view access level to be associated to.
   * @param array $groups An array of ids of some user groups to be associated to a view access level.
   */
  public static function linkGroupWithViewLevel($view_level, $groups = array()) {
    if($view_level) {
      $table_view_level = JTable::getInstance("ViewLevel");
      if($table_view_level->load($view_level)) {
        $rules = (array) json_decode($table_view_level->rules);
        $rules = array_merge($rules, $groups);
        $rules = array_unique($rules);
        $table_view_level->rules = json_encode($rules);
        $table_view_level->store();
      }
    }
  }

  /**
   * Get the id of the organization to which a user profile that's specified by the $user_profile_id belongs.
   *
   * @author Kevin Li<huali@tibco-support.com>
   * @param int $user_profile_id The id of the user profile for which the organization is to be retrieved.
   * @return int A positive integer which denotes the id of of the organization or 0 if no organization was found.
   */
  public static function getOrgIdByUserProfileId($user_profile_id) {
    $ret = 0;
    if($user_profile_id) {
      $db = JFactory::getDbo();
      $query = $db->getQuery(true)->select("field_value")->from("#__js_res_record_values")->where("type_id=8")->where("field_id=47")->where("record_id=" . $user_profile_id);
      $db->setQuery($query);
      if($result = $db->loadAssoc()){
        $ret = $result["field_value"];
      }
    }
    return $ret;
  }

  /**
   * Get the id of the organization of the user who's currently logged in.
   *
   * @author Kevin Li<huali@tibco-support.com>
   * @return int The non-zero id of the organization if there's a logged-in user. 0 otherwise.
   */
  public static function getCurrentUserOrgId() {
    $ret = 0;
    $user = JFactory::getUser();
    $db = JFactory::getDbo();
    $sql = "SELECT id FROM #__js_res_record WHERE id IN ";
    $sql .= "(SELECT field_value FROM `#__js_res_record_values` WHERE type_id=8 AND field_id=47 AND record_id IN ";
    $sql .= "(SELECT record_id FROM #__js_res_record_values WHERE type_id=8 AND field_id=77 AND field_value=" . $user->id . "))";
    $db->setQuery($sql);
    if($result = $db->loadAssoc()) {
      $ret = $result["id"];
    }
    return $ret;
  }

  /**
   * Get the id of the organization which has the subscription with the id $sub_id.
   *
   * @author Kevin Li<huali@tibco-support.com>
   * @param int $sub_id The id of the subscription whose subscribing organization are to be looked for.
   * @return int The non-zero id of the organization if any. 0 otherwise.
   */
  public static function getSubOrgId($sub_id) {
    $ret = 0;
    $db = JFactory::getDbo();
    $sql = "SELECT field_value FROM #__js_res_record_values WHERE field_id=73 AND type_id=10 AND record_id=" . $sub_id;
    $db->setQuery($sql);
    if($result = $db->loadAssoc()) {
      $ret = $result["field_value"];
    }
    return $ret;
  }

}