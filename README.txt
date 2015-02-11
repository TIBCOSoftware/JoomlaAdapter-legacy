==================================================================================
Project Name    : Adapter Code for TIBCO(R) API Exchange and Joomla!
Release Version : 2.1.1_HF-001
Release Date    : February 2015
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

==================================================================================
DOCUMENTATION

The documentation for the Adapter Code for TIBCO API Exchange and Joomla!
includes the following:

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
Migration for Adapter Code for TIBCO API Exchange and Joomla! from Release 2.1.1 
to Release 2.1.1_HF-001

Follow these steps to migrate Release 2.1.1 of 
Adapter Code for TIBCO API Exchange and Joomla! to Release 2.1.1_HF-001:

Task A) Back up Existing Database
Before upgrading to API Exchange 2.1.1_HF-001, it is highly recommended to 
create a backup of both the Joomla files and the database. 
Refer to the "Adapter Code for TIBCO(R) API Exchange and Joomla!" Release Notes 
Version 2.1.1 for the instructions to backup the database and Joomla files.

Task B) Migrate to Release 2.1.1_HF-001
Follow these steps to update to Release 2.1.1_HF-001:
 1. Log in to the Joomla administration utility.
 2. Choose Extensions > Extension Manager.
 3. Choose the extension package "com_migrate(upgrade_2.1.1_to_2.1.1-hf1).zip".
 4. Click Upload & install. The migration installation might take a while.
 5. Copy the contents of the TIB_api-exchange-joomla-adapter_2.1.1_HF-001/adapter/joomla 
    folder into your installed Joomla folder. On UNIX, for example, from the directory 
    where you unzipped this distribution, you can copy the files as follows:

    cp -r ./adapter/joomla/* $APACHE_HOME/htdocs

    For detailed instructions, see the Adapter Code for TIBCO(R) API Exchange and 
    Joomla! Installation and Configuration document.

 6. Complete these steps to purge the expired cache:
    a. Select System, and then choose Purge Expired Cache from the pull-down menu.
.   b. Click Purge Expired Cache at the left of the display.
 7. Clear your browser cache to ensure the latest content is being used in the 
    portal.

Note: Your project installation is now upgraded to version 2.1.1_HF-001.

Task C) Restore Installation with Backup Data (Optional)
If anything goes wrong during the migration process, you can easily restore your data 
by restoring the database using the backup data you retained after backing up your 
original data. Refer to the 
"Adapter Code for TIBCO(R) API Exchange and Joomla!" Release Notes 
Version 2.1.1 for the instructions to restore the installation with backup data.

==================================================================================
Closed Issues in 2.1.1_HF-001 (This Release)

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
    * Copyright (c) 2005 - 2014 Open Source Matters. All rights reserved.
    * See License details at http://docs.joomla.org/Joomla_Licenses

Swagger:

Cobalt packages:
     * Copyright (c) 2005 - 2014 MintJoomla
==================================================================================
