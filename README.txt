==================================================================================
Project Name    : Adapter Code for TIBCO(R) API Exchange and Joomla!
Release Version : 2.1.1_HF-003
Release Date    : July 2015
==================================================================================
DISCLAIMER

No Support. You acknowledge that your license to this code does not entitle you
to any maintenance or support for this code, including upgrades, patches,
enhancements, bug fixes, new versions or new releases of the code.

If you wish to obtain support for this code, please contact TIBCO for details
on TIBCO's open source support plans.

==================================================================================
CONTENTS OF THE DISTRIBUTION

The Adapter Code for TIBCO(R) API Exchange and Joomla! distribution is 
contained in a zip file.

The distribution zip file contains the following folders:

/cobalt - Contains Cobalt extensions required for the API management portal.
          You must install these extensions after initial installation of
          the Joomla adapter code.  For detailed information see the Adapter
          Code for API Exchange Manager and Joomla! Installation and 
          Configuration Guide.
   
/joomla -  Contains the basic Joomla distribution (Joomla 3.3.6)

/adapter - Contains code developed for Joomla and MySQL to support
           integration with TIBCO(R) API Exchange Manager.


Contents of the /adapter Directory:

/joomla - Contains code developed for Joomla that supports the API
          management portal. During installation, this is merged
          with the contents of a standard Joomla distribution.

/sql-scripts - Contains a full SQL copy of the entire Joomla database.
               The SQL files include:

             - baseline&seed_data directory	           - Scripts for fresh 
                                                             install and upload the 
                                                             samples data.
                                                
             - com_migrate(upgrade_2.1.0_to_2.1.0-HF4).zip - Scripts to migrate 
                                                             from 2.1.0 to 2.1.0 HF4

             - com_migrate(upgrade_2.1.0-HF4_to_2.1.1).zip - Scripts to migrate 
                                                             from 2.1.0 HF4 to 2.1.1

             - com_migrate(upgrade_2.1.1_to_2.1.1-hf1).zip - Scripts to migrate 
                                                             from 2.1.1 to 2.1.1 HF1

             - com_migrate(upgrade_2.1.1_or_2.1.1-hf1_to_2.1.1-hf2).zip - Scripts
                                                             to migrate from 2.1.1 
                                                             or 2.1.1 HF1 to
                                                             2.1.1 HF2

             - com_migrate(upgrade_2.1.1_or_2.1.1-hfx_to_2.1.1-hf3).zip - Scripts
                                                             to migrate from 2.1.1 
                                                             (including HF1, HF2)
                                                             to 2.1.1 HF3

             - access_field_type_int(10).zip               - Scripts to update access
               column in openapi_js_res_record table from tinyint(1) to int(10)
                                                             
==================================================================================
DOCUMENTATION

The following is the documentation for the Adapter Code for TIBCO API Exchange
and Joomla!:

- Adapter Code for TIBCO API Exchange and Joomla! Installation  -- 
  Describes installation of prerequisite software (MySQL, PHP, Apache, and Cobalt),
  installation of the Adapter Code for API Exchange Manager and Joomla!, and
  selected configuration topics.

- Adapter Code for TIBCO API Exchange and Joomla! Administration --  Describes
  using the API Exchange Manager Joomla administration interface to set up users,
  user groups, and core configuration.  Also describes how to use the Joomla-based
  API Exchange Manager developer portal to perform administrative tasks for 
  API Exchange; for example, creation of APIs, products, and operations, setting
  up plans and subscriptions, and so on.
  
- Adapter Code for TIBCO API Exchange and Joomla! User's Guide -- Describes how 
  developers use the portal to create applications, associate plans
  with then, request subscriptions, request API keys, and run
  analytics to evaluate APIs.  
  
  The Adapter Code for TIBCO API Exchange Manager and Joomla! documentation
  is available at the following URL: 
     https://github.com/API-Exchange/JoomlaAdapter/wiki
    
==================================================================================
New Installation

 1. Unzip the package which includes Joomla 3.3.6. 
    Note: Do not install any sample data during the installation. You also do not
    need to install any other languages. If you encounter any issues please go to
    Joomla! website for help.
 2. Upgrade to Joomla 3.4.1
  2.1. Log in to the Joomla administration utility.
  2.2. Choose Extensions > Extension Manager.
  2.3. Choose the extension package "Joomla_3.4.1-Stable-Update_Package.zip".
  2.4. Click the "Upload and Install" button. The upgrade might take a while.
 3. Navigate to the Joomla Administration page for 3.4.1 and install the patched
    Cobalt packages in the same way: "pkg_cobalt.j3.everything.v.8.652_patch02.zip"
    and "pkg.mint.j3.media.v.8.82_patch01.zip".
 4. Copy over all of the files inside the following folder:
    "TIB_api-exchange-joomla-adapter_2.1.1_HF-003/adapter/joomla" into your
    Joomla's root folder. 
 5. Overwrite any existing files.
 6. Install the "com_baseline.zip" file located in the "TIB_api-exchange-joomla
    -adapter_2.1.1_HF-003/adapter/sql-scripts/baseline&seed_data" folder in the 
    Extension Manager of Joomla.
 7. If you see any "Notice" shown on top of the page, go to the "Global
    Configuration" by clicking the menu in the "System" menu.
 8. On the "Global Configuration" page, click the "Server" tab. Then change the
    "Error Reporting" to "None." Click the "Save and Close" button.

==================================================================================
Migration for Adapter Code for TIBCO API Exchange and Joomla! from "Release 2.1.1 
or Release 2.1.1_HF-002" to "Release 2.1.1_HF-003"

Follow these steps to migrate release 2.1.1, 2.1.1 HF001 or 2.1.1 HF003 of
Adapter Code for TIBCO API Exchange and Joomla! to "Release 2.1.1_HF-003":

Task A) Back up Existing Database
Before upgrading to API Exchange 2.1.1_HF-003, it is best practice to 
create a backup of both the Joomla files and the database. 
Refer to the "Adapter Code for TIBCO(R) API Exchange and Joomla!" Release Notes 
Version 2.1.1 for the instructions to backup the database and Joomla files.
Note: There are several different tools you can use to backup the database. 
      Adjust the following instructions as necessary for your chosen method.

Follow these steps if you are using "phpMyAdmin."
 1. Login to phpMyAdmin and select the database working with the API Exchange
    Portal.
 2. Click "Export" at the top button bar.
 3. Choose "Custom-display all possible options" radio button.
 4. Scroll down and select the check box "Add Drop Table/View/Procedure/Function/
    Event/ statement."
 5. Scroll down and click the "Go" button to save the database back up file.
 6. Copy your Joomla installation folder to another location as a back up.


Task B) Migrate to Release 2.1.1_HF-003

Follow these steps to update to Release 2.1.1_HF-003:
 1. Log in to the Joomla administration utility.
 2. Choose Extensions > Extension Manager.
 3. Choose the extension package "Joomla_3.4.1-Stable-Update_Package.zip".
 4. Click the "Upload and Install" button. The migration installation might take a while.
 5. Choose Extensions > Extension Manager.
 6. Choose the latest Cobalt package: "pkg_cobalt.j3.everything.v.8.652_pathch02.zip."
 7. Click the "Upload and Install" button. The migration installation might take a while. 
 8. Choose Extensions > Extension Manager.
 9. Choose the latest Mint package: "pkg.mint.j3.media.v.8.82_patch01.zip."
10. Click the "Upload and Install" button. The migration installation might take a while.
11. Choose Extensions > Extension Manager.
12. Choose the following migration package: "com_migrate(upgrade_2.1.1_or_2.1.1-hfx_to
     _2.1.1-hf3).zip".
13. Click the "Upload and Install" button. The migration installation might take while.
14. Copy the contents of the Adapter/Joomla folder into your installed Joomla. Be sure
    that this is a merged copy which overwrites existing files, but one that does not clear
    the directly contents before copying.
15. Clear the expired cache by clicking on the menu item located in the "System" menu.


Note: Your project installation is now upgraded to version 2.1.1_HF-003.
Clear your browser to ensure that the latest content is being used in the portal.

Task C) Restore Installation with Backup Data (Optional)
If anything goes wrong during the migration process, you can easily restore your data 
by restoring the database using the backup data you retained after backing up your 
original data. Refer to the 
"Adapter Code for TIBCO(R) API Exchange and Joomla!" Release Notes 
Version 2.1.1 for the instructions to restore the installation with backup data.
==================================================================================
Closed Issues in 2.1.1_HF-003 (This Release)

ASG-6742
Upgrade to Joomla! 3.4.1 for stability.
Follow these steps to upgrade Joomla!:
 1. Install Joomla 3.4.1. that comes with the Adapter first.
    Note: Do not install any sample data during the installation. You also do not
    need to install any other languages. If you encounter any issues please go to
    Joomla! website for help.
 2. Navigate to the Joomla Administration page for 3.4.1 and install the patched
    Cobalt packages: "pkg_cobalt.j3.everything.v.8.652_patch02.zip" and
    "pkg.mint.j3.media.v.8.82_patch01.zip" folder.
 3. Copy over all of the files inside the following folder:
    "TIB_api-exchange-joomla-adapter_2.1.1_HF-003/adapter/joomla" into your
    Joomla's root folder. 
 4. Overwrite any existing files.
 5. Install the "com_baseline.zip" file located in the "TIB_api-exchange-joomla
    -adapter_2.1.1_HF-003/adapter/sql-scripts/baseline&seed_data" folder in the 
    Extension Manager of Joomla.
 6. Install the migration package which is the "com_migrate(upgrade_2.1.1_or
    -2.1.1-hfx_to_2.1.1.-hf3).zip" file located in the "adapter/sql-scripts" folder
    from the Extension Manager of Joomla.
 7. If you see any "Notice" shown on top of the page, go to the "Global Configuration"  
    by clicking the menu in the "System" menu.
 8. On the "Global Configuration" page, click the "Server" tab. Then change the
    "Error Reporting" to "None." Click the "Save and Close" button.

Note: Your Joomla! version has been upgraded. When you navigate to the API Manager 
      by clicking on the menu item in the "Components" menu, you will find the 
      version number, which should match the one in the file name of the API
      Explorer Adapter package.

ASG-6744
Support for reCAPTCHA v2. 
Migration to reCAPTCHA v2 might require:

 1. Configuring the Captcha - ReCaptcha Plugin from the administrator user interface
    (Extensions > Plugin Manager). Click the plugin to configure the site key and
    secret key that can be obtained for free from Google.
 2. Changing the Default Captcha on Global Configuration > Site Settings 
    to “Captcha - ReCaptcha"

==================================================================================
Closed Issues in 2.1.1_HF-002

ASG-6287
TIBCO API Exchange Manager threw an exception when the user created or edited 
more than one target environment containing more than one API.

ASG-6281
TIBCO API Exchange Manager did not allow the user to edit the application after 
a product had been enabled in the application.

==================================================================================
Closed Issues in 2.1.1_HF-001

ASG-6186
API import of specification was looking for operations through httpMethod
rather than method which is required for Swagger 1.2. Both are now supported.

==================================================================================
COPYRIGHT & LICENSE INFORMATION

Except as stated below, this code is made available under the 
GNU General Public License, version 2.0. Copies of the applicable 
licenses are contained in a file titled "LICENSE.txt" that is included
with this code distribution.

TIBCO package:
    * Copyright (c) 2013-2015 TIBCO Software Inc. ALL RIGHTS RESERVED.
    * Copyright (c) 2011 - 2013  Wordnik, Inc. (licensed under the Apache
      License, version 2.0)
    * Copyright (c) 2009-2012 Jeremy Ashkenas, DocumentCloud
      Inc. (licensed under the MIT license)
    * Copyright (c) 2011 by Yehuda Katz 
    * Copyright (c) 2010 "Cowboy" Ben Alman (MIT and GPL licenses)

Joomla package:
    * Copyright (c) 2005 - 2015 Open Source Matters. All rights reserved.
    * See License details at http://docs.joomla.org/Joomla_Licenses

Swagger:

Cobalt packages:
     * Copyright (c) 2005 - 2015 MintJoomla
==================================================================================
