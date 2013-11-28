<?php
  /* @copyright Copyright © 2013, TIBCO Software Inc. All rights reserved.
 * @license GNU General Public License version 2; see LICENSE.txt
 */
?>
<?php
defined('_JEXEC') or die();

jimport('joomla.application.component.controlleradmin');

require_once JPATH_BASE . "/includes/api.php";
require_once JPATH_BASE . "/includes/subcreate.php";

class CobaltControllerAjaxMore extends JControllerAdmin {
    
    public function updateStatusOfKey() {
        $ids = $_REQUEST['keyList'];
        $ids = implode(',', $ids);
        if (empty($ids)) {
            AjaxHelper::send('Related key has updated', $key = 'result');
        }
        $db = JFactory::getDbo();

        $db -> setQuery('UPDATE #__js_res_record_values SET field_value = "Inactive" WHERE field_id = 85 AND section_id = 5 AND record_id IN (' . $ids . ')');
        $db -> query();
        AjaxHelper::send('Related key has updated', $key = 'result');
    }

    public function getEnvironmentByApis() {

        if (isset($_REQUEST['apis'])) {
            $apis = $_REQUEST['apis'];
        }
        if (!$apis) {
            AjaxHelper::error("You didn't select any apis");
        }
        $db = JFactory::getDbo();
        $db -> setQuery('SELECT record_id,count(record_id) as record_num FROM `#__js_res_record_values` where field_id=25  and field_value in (' . $apis . ') group by record_id order by record_num desc');
        $db -> query();
        $result = array();
        $api_count = count(explode(",", $apis));

        foreach ($db->loadObjectList() as $key => $val) {
            if ($val -> record_num >= $api_count) {
                $result[] = $val -> record_id;
            }
        }
        if (!empty($result)) {
            AjaxHelper::send($result, "result");
        } else {
            AjaxHelper::error("Don't have any public environment!");
        }
    }

    public function getApiRecordsById() {

        if (isset($_REQUEST['ids'])) {
            $ids = $_REQUEST['ids'];
        }

        if (!$ids) {
            AjaxHelper::error("You didn't select any apis");
        }

        $ids = explode(',', $ids);
        $api = new DeveloperPortalApi();
        $result = array();

        foreach ($ids as $key => $id) {
            if ($api -> getApiFieldsForProduct($id)) {
                $result[] = $api -> getApiFieldsForProduct($id);
            }
        }

        if (!empty($result)) {
            AjaxHelper::send($result, "result");
        } else {
            AjaxHelper::error("Don't have any public environment!");
        }
    }
	
	public function archiveOperationsInSpec() {
        if (isset($_REQUEST['specPath'])) {
            $specPath = $_REQUEST['specPath'];
			$apiID = $_REQUEST['apiID'];
			if (!empty($specPath) && !empty($apiID)) {
				$file = file_get_contents($specPath);
				$spec = json_decode($file);
				$titlesArray = array();
				foreach ($spec->apis as $key => $api) {
					$titlesArray[] = '"'.$api->operations[0]->nickname.'"';
				}
				
				if (count($titlesArray)>0) {
					$titles = implode(",",$titlesArray);
					$db = JFactory::getDbo();
					$sql = 'Select id from `#__js_res_record` where id in (select record_id from `#__js_res_record_values` where field_id=30 and field_value='.$apiID.') and title in ('.$titles.') and published <> 2';
					$db->setQuery($sql);
					$result = array();
					foreach ($db->loadObjectList() as $key => $value) {
						$result[] = $value->id;
					}
					if (count($result)>0) {
						$ids = implode(",",$result);
				        $sql = 'Update `#__js_res_record` set published=2 where id in ('.$ids.')';
				        $db->setQuery($sql);
						if ($db -> query()) {
							AjaxHelper::send($result);
						}else{
							AjaxHelper::error("");
						}
					}else{
						AjaxHelper::send("");
					}
				}
			}else{
				AjaxHelper::error("");
			}
        }
	}
    
    public function _getEmailTemplateByAlias($alias){
      $db = JFactory::getDbo();
      $db -> setQuery('SELECT * FROM #__email_templates WHERE alias="'.$alias.'" AND published = 1 LIMIT 1');
      return $db -> loadObject();
    }
    public function requestNormalPlan() {
      $user = JFactory::getUser();
      $user_id = $user -> id;
      
      $plan_id = $_POST["planId"];
      $product_id = $_POST["productId"];
      $_domain = DeveloperPortalApi::getHostUrl();
      
      $config = JFactory::getConfig();
      $admin_email = $config -> get('mailfrom');
      if ($user_id && $plan_id && $product_id) {
        $plan_url         = $_domain.JRoute::_(Url::record($plan_id));
        $product_url      = $_domain.JRoute::_(Url::record($product_id));
        $organization     = DeveloperPortalApi::getUserOrganization();
        $organization_url = $_domain.JRoute::_(Url::record($organization[0]));
        $user_url         = $_domain.JRoute::_(Url::record(DeveloperPortalApi::getUserProfileId($user_id)));
        
        //send email to administrator of joomla back-end
        $results = $this->_getEmailTemplateByAlias("request_normal_plan_notify_admin_of_joomla");
        if ($results -> subject && $results -> content) {
          $create_sub_url = JURI::root()."index.php/subscriptions/submit/6-subscriptions/10-subscription?sub_product_id=".$product_id."&sub_plan_id=".$plan_id."&sub_uid=".$user_id.'&organization_id='.$organization[0];
          $title = $results -> subject;
          $content = $results -> content;
          $content = str_replace("{CREATE_SUB_URL}", $create_sub_url, $content);
          $content = str_replace("{PLAN_URL}", $plan_url, $content);
          $content = str_replace("{PRODUCT_URL}", $product_url, $content);
          $content = str_replace("{ORGANIZATION_URL}", $organization_url, $content);
          $content = str_replace("{USER_URL}", $user_url, $content);
          
          $admin_email_group = DeveloperPortalApi::getEmailsOfJoomlaAdmins();
          $is_send_email_to_admin_joomla = DeveloperPortalApi::send_email($admin_email_group, $title, $content, $results -> isHTML);
        }
        
        //send email to administrator and contacter organization
        $results = $this->_getEmailTemplateByAlias("request_normal_plan_notify_admin_contacter_of_organization");
        if ($results -> subject && $results -> content) {
          $title = $results -> subject;
          $content = $results -> content;
          $content = str_replace("{PLAN_URL}", $plan_url, $content);
          $content = str_replace("{PRODUCT_URL}", $product_url, $content);
          $content = str_replace("{ORGANIZATION_URL}", $organization_url, $content);
          $content = str_replace("{USER_URL}", $user_url, $content);
        
          $admin_email_group = array_merge(DeveloperPortalApi::getEmailsOfOrganizationAdmin(), DeveloperPortalApi::getEmailsOfOrganizationContact());
          DeveloperPortalApi::send_email($admin_email_group, $title, $content, $results -> isHTML);
        }
        
        if ($is_send_email_to_admin_joomla == "1") {
          //send email to requester
          $results = $this->_getEmailTemplateByAlias("request_plan_notify_requester");
          if ($results -> subject && $results -> content) {
            $title = $results -> subject;
            $content = $results -> content;
            $content = str_replace("{PLAN_URL}", $plan_url, $content);
            $content = str_replace("{PRODUCT_URL}", $product_url, $content);
            $content = str_replace("{ORGANIZATION_URL}", $organization_url, $content);
            
            DeveloperPortalApi::send_email($user->email, $title, $content, $results -> isHTML);
          }
          AjaxHelper::send(JText::_('PLAN_REQUEST_RESULT_SUCCESS'),"msg");
        }else{
          AjaxHelper::error(JText::_('EMAIL_RETURN_NOTES_2'));
        }
      }else{
        AjaxHelper::error(JText::_('EMAIL_RETURN_NOTES_4'));
      }
    }
    public function requestCustomPlan() {
      $user = JFactory::getUser();
      $user_id = $user -> id;
      
      $product_id = $_POST["productId"];
      $_domain = DeveloperPortalApi::getHostUrl();
      
      $config = JFactory::getConfig();
      $admin_email = $config -> get('mailfrom');
      if ($user_id && $product_id) {
        $product_url      = $_domain.JRoute::_(Url::record($product_id));
        $organization     = DeveloperPortalApi::getUserOrganization();
        $organization_url = $_domain.JRoute::_(Url::record($organization[0]));
        $user_url         = $_domain.JRoute::_(Url::record(DeveloperPortalApi::getUserProfileId($user_id)));
        
        //send email to administrator of joomla back-end
        //send email to administrator and contacter organization
        $results = $this->_getEmailTemplateByAlias("request_custom_plan_notify_admin_contacter");
        if ($results -> subject && $results -> content) {
          $rate_limit  = $_POST["rate_limit"];
          $quota_limit = $_POST["quota_limit"];
          $additional_request = $_POST["additional_request"];
        
          $title = $results -> subject;
          $content = $results -> content;
          $content = str_replace("{PRODUCT_URL}", $product_url, $content);
          $content = str_replace("{ORGANIZATION_URL}", $organization_url, $content);
          $content = str_replace("{RATE_LIMIT}", $rate_limit, $content);
          $content = str_replace("{QUOTA_LIMIT}", $quota_limit, $content);
          $content = str_replace("{ADDITIONAL_REQUEST}", $additional_request, $content);
          $content = str_replace("{USER_URL}", $user_url, $content);
          
          $admin_email_group = DeveloperPortalApi::getEmailsOfJoomlaAdmins();
          $is_send_email_to_admin_joomla = DeveloperPortalApi::send_email($admin_email_group, $title, $content, $results -> isHTML);
        }
        
        if ($is_send_email_to_admin_joomla == "1") {
          //send email to requester
          $results = $this->_getEmailTemplateByAlias("request_plan_notify_requester");
          if ($results -> subject && $results -> content) {
            $title = $results -> subject;
            $content = $results -> content;
            $content = str_replace("{PRODUCT_URL}", $product_url, $content);
            $content = str_replace("{ORGANIZATION_URL}", $organization_url, $content);
          
            DeveloperPortalApi::send_email($user->email, $title, $content, $results -> isHTML);
          }
          AjaxHelper::send(JText::_('PLAN_REQUEST_RESULT_SUCCESS'),"msg");
        }else{
          AjaxHelper::error(JText::_('EMAIL_RETURN_NOTES_2'));
        }
      }else{
        AjaxHelper::error(JText::_('EMAIL_RETURN_NOTES_4'));
      }
    }
    /**
     * verify if there is same plan title existed in same product. 
     */
    public function validatePlanTitle() {
	    $plan_title = $_POST["plan_title"];
	    $product_id = $_POST["product_id"];
	    $plan_id = $_POST["plan_id"];
	    if ($plan_title && $product_id) {
	      $db = JFactory::getDbo();
	      $sql = "SELECT COUNT(r.id) AS sum  FROM openapi_js_res_record AS r, openapi_js_res_record_values AS rv ";
	      $sql.= " WHERE rv.field_id=53 AND rv.type_id=7 AND rv.record_id=r.id";
	      $sql.= " AND rv.field_value=".$product_id." AND LOWER(r.title)='".strtolower($plan_title)."'";
	      $sql.= $plan_id ? " AND r.id!=".$plan_id : "";
	      $db->setQuery($sql);
	      $result = $db->loadObject();
	      if ($result->sum==0) {
	        AjaxHelper::send("");
	      }else{
  	      AjaxHelper::error(JText::_('DUPLICATE_PLAN_TITLE_IN_PRODUCT'));
	      }
	    }else{
	      AjaxHelper::error(JText::_('EMAIL_RETURN_NOTES_4'));
	    }
    }
    /**
     * verify if there is same gateway title existed in same environments.
     */
    public function validateGatewayTitle() {
      $gateway_title  = $_POST["gateway_title"];
      $environment_id = $_POST["environment_id"];
      $gateway_id = $_POST["gateway_id"];
      if ($gateway_title && $environment_id) {
        $db = JFactory::getDbo();
        $sql = "SELECT COUNT(r.id) AS sum  FROM openapi_js_res_record AS r, openapi_js_res_record_values AS rv ";
        $sql.= " WHERE rv.field_id=16 AND rv.type_id=3 AND rv.record_id=r.id";
        $sql.= " AND rv.field_value=".$environment_id." AND LOWER(r.title)='".strtolower($gateway_title)."'";
        $sql.= $gateway_id ? " AND r.id!=".$gateway_id : "";
        $db->setQuery($sql);
        $result = $db->loadObject();
        if ($result->sum==0) {
          AjaxHelper::send("");
        }else{
          AjaxHelper::error(JText::_('DUPLICATE_GATEWAY_TITLE_IN_ENVIRONMENT'));
        }
      }else{
        AjaxHelper::error(JText::_('EMAIL_RETURN_NOTES_4'));
      }
    }
    /**
     * verify if there is same operation title existed in same api.
     */
    public function validateOperationTitle() {
      $operation_title  = $_POST["operation_title"];
      $api_id = $_POST["api_id"];
      $operation_id = $_POST["operation_id"];
      if ($operation_title && $api_id) {
        $db = JFactory::getDbo();
        $sql = "SELECT COUNT(r.id) AS sum  FROM openapi_js_res_record AS r, openapi_js_res_record_values AS rv ";
        $sql.= " WHERE rv.field_id=30 AND rv.type_id=6 AND rv.record_id=r.id";
        $sql.= " AND rv.field_value=".$api_id." AND LOWER(r.title)='".strtolower($operation_title)."'";
        $sql.= $operation_id ? " AND r.id!=".$operation_id : "";
        $db->setQuery($sql);
        $result = $db->loadObject();
        if ($result->sum==0) {
          AjaxHelper::send("");
        }else{
          AjaxHelper::error(JText::_('DUPLICATE_OPERATION_TITLE_IN_API'));
        }
      }else{
        AjaxHelper::error(JText::_('EMAIL_RETURN_NOTES_4'));
      }
    }
    /**
     * validate gateway's management URL. 
     */
    public function validateGatewaysManagementURLs() {
      $urls = explode(",", $_POST["urls"]);
      $duplicates = array();
	    if (count($urls)>0) {
	      $db = JFactory::getDbo();
        for($i=0;$i<count($urls);$i++) {
          $sql = "select count(id) as sum from #__js_res_record where published!=2 and id in (select record_id from #__js_res_record_values where field_id=89 and type_id=3 and field_value='" . $urls[$i] . "')";
          $db->setQuery($sql);
          $result = $db->loadObject();
          if ($result->sum!=0) {
            $duplicates[] = $urls[$i];
	        }
        }
        if (count($duplicates) == 0) {
          AjaxHelper::send("");
        } else {
          AjaxHelper::error(JText::_('DUPLICATE_MANAGEMENT_URL_IN_GATEWAYS') . ": " . join(",", $duplicates) . ".");
        }
	    }else{
	      AjaxHelper::error(JText::_('EMAIL_RETURN_NOTES_4'));
	    }
    }	
	public function requestSupport() {
	  $name = $_POST["fname"].' '.$_POST["lname"];
	  $email = $_POST["email"];
	  $user_content = $_POST["content"];
	  
	  $db = JFactory::getDbo();
	  $db -> setQuery('SELECT * FROM #__email_templates WHERE alias="request_email" AND published = 1 LIMIT 1');
	  $results = $db -> loadObject();
	  if ($results -> subject && $results -> content) {
	    $config = &JFactory::getConfig();
	    $title = str_replace("{USER}", $name, $results -> subject);
	    
	    $content = str_replace("{USER}", $name, $results -> content);
	    $content = str_replace("{EMAIL}", $email, $content);
	    $content = str_replace("{USER_CONTENT}", $user_content, $content);
	  
	    $config = JFactory::getConfig();
	    $admin_email = $config -> get('mailfrom');
	  
	    if (DeveloperPortalApi::send_email($admin_email, $title, $content, $results -> isHTML)) {
	      AjaxHelper::send("");
	    }else{
	      AjaxHelper::error(JText::_('EMAIL_RETURN_NOTES_2'));
	    }
	  }else{
	    AjaxHelper::error(JText::_('EMAIL_RETURN_NOTES_6'));
	  }
	}
	
	public function subscriptionDidCreate() {
	  $requester_uid   = $_POST["requester_uid"];
	  $product_id      = $_POST["product_id"];
	  $subscription_id = $_POST["subscription_id"];
	  $_domain = DeveloperPortalApi::getHostUrl();
	  $product_url      = $_domain.JRoute::_(Url::record($product_id));
	  $subscription_url = $_domain.JRoute::_(Url::record($subscription_id));
	  
    //send email to joomla admin/organization admin,contacter/requester
	  if ($requester_uid && $product_id && $subscription_id) {
  	  $config = JFactory::getConfig();
  	  $admin_email = $config -> get('mailfrom');
  	  
  	  $user = JFactory::getUser($requester_uid);
  	  $user_email = $user->email;
  	  $email_group = array_merge(
         DeveloperPortalApi::getEmailsOfJoomlaAdmins(),
         DeveloperPortalApi::getEmailsOfOrganizationAdmin(), 
         DeveloperPortalApi::getEmailsOfOrganizationContact(),
         array($user_email)
  	  );
  	  $email_group = array_unique($email_group);
	    $results = $this->_getEmailTemplateByAlias("notification_of_create_subscription");
	    if ($results -> subject && $results -> content) {
	      $title = $results -> subject;
	      $content = $results -> content;
	      $content = str_replace("{PRODUCT_URL}", $product_url, $content);
	      $content = str_replace("{SUBSCRIPTION_URL}", $subscription_url, $content);
	  
	      DeveloperPortalApi::send_email($email_group, $title, $content, $results -> isHTML);
	    }
	    AjaxHelper::send(JText::_('SUBSCRIPTION_CREATE_SUCCESS'),"msg");
	  }else{
	    AjaxHelper::error(JText::_('EMAIL_RETURN_NOTES_4'));
	  }
	}
    
    public function createUserGroups() {
        if(isset($_REQUEST['org_id'])) {
            $org_id = $_REQUEST['org_id'];
        }
        DeveloperPortalApi::createUserGroups($org_id);
    }
    
    public function getUserByUid(){
      $uid  = intval($_GET["uid"]);
      if ($uid) {
        $user = &JFactory::getUser($uid);
        if ($user) {
          $result = array(
              "id"   => $user->id,              
              "name" => $user->name,              
              "username" => $user->username,              
              "email"    => $user->email              
          );
          AjaxHelper::send($result, "result");
        }else{
          AjaxHelper::error("This user does not exist.");
        }
      }else{
        AjaxHelper::error("Miss parameters in the request.");
      }
    }

    /**
     * Used for disadbling user when the related userprofile is being archived.
     * @return Boolean if unactive 
     */
    public static function disabledUser(){
      $db = JFactory::getDbo();
      $query = $db->getQuery(true);
      $userprofile_id = $_REQUEST['userfile_id'];
      $user_id = DeveloperPortalApi::getUserIdByProfileId($userprofile_id);
      $user = &JFactory::getUser($user_id);

      $query->update($db->quoteName('#__users'))->set($db->quoteName('block') . "=1")->where($db->quoteName('id') . '=' . $user_id);
      $db->setQuery($query);

      if($db->query()){
          AjaxHelper::send(JText::_('ENVIRONMENT_REMOVE_FROM_PRODUCT_SUCCESS'));
      }
      else
      {
          AjaxHelper::error(JText::_('ENVIRONMENT_REMOVE_FROM_PRODUCT_FAILED'));
      }
    }

    public function resendActiveEmail(){
        JSession::checkToken('get') or jexit(JText::_('JINVALID_TOKEN'));
        $app = JFactory::getApplication();
        $url = '';
        $url .= JURI::root().'index.php/userprofile?task=registration.activate&token=';

        $res_id = $_REQUEST['id'];
        $db = JFactory::getDbo();
        $db->setQuery('select `field_value` from #__js_res_record_values where `field_id`=77 and `record_id`='.$res_id);
        $result = $db->loadColumn();
        if(empty($result))
        {
            $app->redirect( JRoute::_('index.php'), $msg='Actived failed!', $msgType='message');
        }

        $db->setQuery('select `id` from #__users where `id`="'.($result[0]?$result[0]:0).'"');
        $user_id = $db->loadColumn();
        if(empty($user_id))
        {
         $app->redirect( JRoute::_('index.php'), $msg='Actived failed!', $msgType='message');
        }
        
        $user = &JFactory::getUser($user_id[0]);
        $url .= $user->get('activation');

        if(DeveloperPortalApi::resendActiveEmail($user_id[0], $url)){
            $app->redirect( JRoute::_('index.php'), $msg='Success actived', $msgType='message');
        }
        else
        {
            $app->redirect( JRoute::_('index.php'), $msg='Actived failed!', $msgType='message');
        } 
    }
	
    public function asgLogs(){

      $db = JFactory::getDbo();
      $user = JFactory::getUser();
      $log_item = new stdClass();

      $log_item->log_type             = $_POST['log_type'];
      $log_item->is_show              = 0;
      $log_item->http_status          = $_POST['status'];
      $log_item->http_status_text     = addslashes($_POST['statusText']);
      $log_item->http_response_text   = addslashes('');
      $log_item->content              = addslashes($_POST['content']);
      $log_item->entity_type          = $_POST['entity_type'];
      $log_item->entity_id            = $_POST['entity_id'];
      $log_item->event                = $_POST['event'];
      $log_item->event_status         = $_POST['event_status'];
      $log_item->uid                  = $user->id ? $user->id : 0;
      

      $db->insertObject("asg_logs",$log_item,'id') ? AjaxHelper::send("") : AjaxHelper::error(JText::_('EMAIL_RETURN_NOTES_4'));

    }
    
    /**
     * Archive an object by setting the "published" column to 2.
     * 
     * @author Kevin Li<huali@tibco-support.com> 
     * @return string A JSON string
     * update 13/11/2013 by Jacky
     */
    public function archiveRecord() {
      $flag       = true;
      $type_id    =  JRequest::getInt("type_id",0);
      $record_id  =  JRequest::getInt("rec_id",0);

      $archive_ids = $record_id?array($record_id):array();

      if($type_id == 4 && $record_id)
      {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query->select("record_id")->from("#__js_res_record_values")->where('field_id=16 and field_value='.$record_id);
        $db->setQuery($query);
        $result = $db->loadColumn();

        $archive_ids = array_merge($archive_ids, $result);
      }

      foreach ($archive_ids as $archive_id) 
      {
        if(!DeveloperPortalApi::archiveRecord($archive_id))
        {
          $flag = false;
          break;
        }
      }

      if (!$record_id)
      {
        AjaxHelper::error("The parameter rec_id is missing.");
      }else if ($flag){
        AjaxHelper::send("success");
      }else{
        AjaxHelper::error("Record update failed.");
      }
      
    }

    /**
     * @Author Jacky love0.0chen@gmail.com
     * @Created 2013-10-10 17:04
     * @Updated 2013-10-10 17:04
     * Remove environment from product
     * @Params record_id the product id which we need to make sure which record we need to update
     * @Params return where we need to get back when action is failed or success
     * @Return refresh page
     */
    public function removeEnvFromProduct(){
      $app            =       JFactory::getApplication();
      $db             =       JFactory::getDbo();
      $user           =       JFactory::getUser();
      $query          =       $db->getQuery(true);

      $return_link    =       base64_decode(JRequest::getVar("return"));
      $field_id       =       JRequest::getVar("field_id");
      $record_id      =       $_REQUEST['record_id'];
      $record         =       ItemsStore::getRecord($record_id);


      if(!in_array(3, $user->getAuthorisedViewLevels()))
      {
          AjaxHelper::error(JText::_('ENVIRONMENT_REMOVE_FROM_PRODUCT_NOPERMISSION'));
      }

      //Change the fields value for record
      $fields         =       json_decode($record->fields);
      $fields->{'35'} =       null;
      $fields         =       json_encode($fields);
      $res->fields    =       $fields;


      //Delete attached environment from databse for the record
      $query->delete("#__js_res_record_values")->where('field_id = 34 AND field_value = '.$record_id);
      $db->setQuery($query);

      //if delete failed, refesh page
      if(!$db->query())
      {
          AjaxHelper::error(JText::_('ENVIRONMENT_REMOVE_FROM_PRODUCT_FAILED'));
      }

      //Update the record fields
      $query = $db->getQuery(true);
      $query->update($db->quoteName('#__js_res_record'))->set($db->quoteName('fields') . "='" . addcslashes($record->fields) ."'")->where($db->quoteName('id') . '=' . $record_id);
      $db->setQuery($query);

      if($db->query()){
          AjaxHelper::send(JText::_('ENVIRONMENT_REMOVE_FROM_PRODUCT_SUCCESS'));
      }
      else
      {
          AjaxHelper::error(JText::_('ENVIRONMENT_REMOVE_FROM_PRODUCT_FAILED'));
      }
      return false;
    }


    /**
     * field_id 66  => description
     * field_id 114 => product 179
     * field_id 69  => plan 186
     * field_id 71  => startd date
     * field_id 72  => enddate
     * field_id 73  => organization
     * field_id 78  => status
     * field_id 112 => uuid
     */
    public function insertSub()
    {
      $db = JFactory::getDbo();
      $sub = new stdClass();
      $fields = new stdClass();
      $user = JFactory::getUser();
      $lang   = JFactory::getLanguage();
      

      $config =& JFactory::getConfig();
      $offset = $config->get('offset');
      
      $start_date = new JDate("now", $offset);
      $start_date = $start_date->format("Y-m-d",true);

      $end_date = new JDate("+5 year", $offset);
      $end_date = $end_date->format("Y-m-d",true);

      $fields->{'66'}   =   "";
      $fields->{'114'}  =   JRequest::getVar('product_id');
      $fields->{'69'}   =   JRequest::getVar('plan_id');
      $fields->{'71'}   =   array($start_date);
      $fields->{'72'}   =   array($end_date);
      $fields->{'73'}   =   DeveloperPortalApi::getUserOrganization();
      $fields->{'73'}   =   $fields->{'73'}[0];
      $fields->{'78'}   =   array("Active");
      $fields->{'112'}  =   CreateSubscriptionApi::getUuid('');

      $org_title        =   ItemsStore::getRecord($fields->{'73'})->title;
      $product_title    =   ItemsStore::getRecord($fields->{'114'})->title;
      $plan_title       =   ItemsStore::getRecord($fields->{'69'})->title;
      $title            =   $org_title.'-'.$product_title.'-'.$plan_title;

      $sub->id          =   null;
      $sub->title       =   trim($title);
      $sub->published   =   1;
      $sub->access      =   10;
      $sub->user_id     =   129;
      $sub->section_id  =   6;
      $sub->ctime       =   JFactory::getDate()->toSql();
      $sub->extime      =   '';
      $sub->mtime       =   JFactory::getDate()->toSql();
      $sub->inittime    =   JFactory::getDate()->toSql();
      $sub->ftime       =   '';
      $sub->type_id     =   10;
      $sub->meta_descr  =   '';
      $sub->meta_key    =   '';
      $sub->meta_index  =   '';
      $sub->alias       =   JApplication::stringURLSafe(strip_tags($sub->title));
      $sub->featured    =   0;
      $sub->archive     =   0;
      $sub->ucatid      =   0;
      $sub->langs       =   $lang->getTag();
      $sub->ip          =   $_SERVER['REMOTE_ADDR'];
      $sub->hidden      =   0;
      $sub->access_key  =   md5(time() . $_SERVER['REMOTE_ADDR'] . $sub->title);
      $sub->fields      =   json_encode($fields);
     
      // pre($fields->{'73'});
      if($db->insertObject("#__js_res_record",$sub,'id'))
      {
        $record_id = CreateSubscriptionApi::getSubscription($sub->access_key);
        if($record_id){
          if(CreateSubscriptionApi::insertFields($record_id,$fields))
          {
            $result = array("record_id"=>$record_id, "msg"=>JText::_('AUTO_CREATE_SUBSCRIPTION_SUCCESS'));
            AjaxHelper::send($result,"result");
          }
          else
          {
            AjaxHelper::error(JText::_('AUTO_CREATE_SUBSCRIPTION_FAILED'));
          }
        }
      }
    }
    function answerPing(){
      $isSuccessFromPing = $_SESSION["isSuccessFromPing"];
      if ($isSuccessFromPing) {
        $email      = $_REQUEST["email"];
        $org_name   = $_REQUEST["org_name"];
        $loginBackUrl = JURI::root() . 'index.php?option=com_cobalt&task=ajaxmore.createPingUserProfile&org_name='.$org_name;
        
        $str = '<form action="'.JURI::root().'" method="post" style="display:none;">'."\n";
        $str.= '<input type="text" name="username" value="'.$email.'" />'."\n";
        $str.= '<input type="password" name="password" value="'.$email.'" />'."\n";
        $str.= '<input type="hidden" name="option" value="com_users" />'."\n";
        $str.= '<input type="hidden" name="task" value="user.login" />'."\n";
        $str.= '<input type="hidden" name="isSuccessFromPing" value="'.$isSuccessFromPing.'" />'."\n";
        $str.= '<input type="hidden" value="'.base64_encode($loginBackUrl).'" name="return">'."\n";
        $str.= '<button id="ping_login_submit" type="submit" name="Submit">Sign in</button>'."\n";
        $str.= JHtml::_('form.token')."\n";  
        $str.= '</form>'."\n";
        $str.= '<script type="text/javascript">'."\n";
        $str.= "window.onload=function(){ document.getElementById('ping_login_submit').click(); } \n";
        $str.= '</script>'."\n";
        echo $str;
        exit;
      }else{
        $app = JFactory::getApplication();
        $app->redirect('index.php/component/users/?view=login', JText::_("JGLOBAL_AUTH_FAIL"));
      }
    }
    function createPingUserProfile(){
      $org_name   = $_REQUEST["org_name"];
      $user = JFactory::getUser();
      $user_id = $user->get('id');
      
      if (!DeveloperPortalApi::getUserProfileId()) {
        $options = array(
            "title" => $org_name,
        );
        $org_id = TibcoTibco::forceGetOrganizationId($options);
        $options = array(
            "org_id" => $org_id,
        );
        TibcoTibco::createUserProfile($options);
        
        $app = JFactory::getApplication();
        $app->logout($user_id, array());
        
        header("Location:".JURI::root().'index.php?option=com_cobalt&task=ajaxmore.answerPing&email='.$user->email);exit;
      }
      header("Location:".JURI::root());exit;
    }

    function attachUserToGroup()
    {
      
      $user_id = JRequest::getVar("userId",0);


      $org_name = JRequest::getVar("jform",array());
      $org_name = $org_name['user_group_name'];
      if(!$user_id || !$org_name)
      {
        AjaxHelper::error(JText::_('ATTACH_USER_TO_ORGANIZATION_NO_USER_ORGANIZATION'));
      }

      $user = JFactory::getUser($user_id);

      $group_id = DeveloperPortalApi::getOrganizationIdByName($org_name);

      if (!$group_id)
      {
        AjaxHelper::error(JText::_("ATTACH_USER_NO_ORGANIZATION_FOUND"));
        
      }else if(in_array($group_id, $user->getAuthorisedGroups())){

        AjaxHelper::error(JText::_('ATTACH_USER_TO_ORGANIZATION_FAILED1'));

      }else{
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $userGroupItem = new stdClass();
        $userGroupItem->user_id = $user_id;
        $userGroupItem->group_id = $group_id;
        if($db->insertObject("#__user_usergroup_map",$userGroupItem,'id'))
        {
          AjaxHelper::send("");
        }else{
          AjaxHelper::error(JText::_('ATTACH_USER_TO_ORGANIZATION_FAILED1'));
        }
      }
    }
}
?>