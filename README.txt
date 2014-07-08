==================================================================================
Project Name    : Adapter Code for TIBCO API Exchange and Joomla!
Release Version : 2.1.0_HF-004
Release Date    : July 2014
==================================================================================
DISCLAIMER

No Support. You acknowledge that TIBCO Software does not commit to provide
any maintenance or support for this code, including upgrades, patches,
enhancements, bug fixes, new versions or new releases of the code.

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
   
/joomla -  Contains the basic Joomla distribution (Joomla 3.1.1)

/adapter - Contains code developed for Joomla and MySQL to support
           integration with TIBCO(R) API Exchange Manager.


Contents of the /adapter Directory:

/joomla - Contains code developed for Joomla that supports the API
          management portal. During installation, this is merged
          with the contents of a standard Joomla distribution.

/sql-scripts - Contains a full SQL copy of the entire Joomla database.
               The SQL files include:

               - asg-openapi.sql                - A full SQL dump of the entire 
                                                  Joomla database.
               - upgrade_2.1.0_to_2.1.0-hf4.sql - A migration script to upgrade
                                                  the database schema changes.
 
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
  user groups, and core configuraton.  Also describes how to use the Joomla-based
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
Installation Instructions

On UNIX, for example, from the directory where you unzipped 
this distribution, you can copy the files as follows:

cp -r ./joomla/* $APACHE_HOME/htdocs
cp -r ./adapter/joomla/* $APACHE_HOME/htdocs

For detailed instructions, see the Adapter Code for TIBCO API Exchange Manager and 
Joomla! Installation and Configuration document.

TIBCO API Exchange Migration

To migrate the database from 2.1.0 or a 2.1.0 hotfix version to
2.1.0_HF-004, do the following:

1. It is recommended to back up the database.
2. Do one of the following:

- Run the following script on the database:
   
/adapter/sql-scripts/upgrade_2.1.0_to_2.1.0-hf4.sql

For example, at the SQL command line enter:
mysql -u USERNAME -p DATABASE_NAME < /adapter/sql-scripts/upgrade_2.1.0_to_2.1.0-hf4.sql

where USERNAME is  the username of the MySQL database, and DATABASE_NAME is the name
of the database that holds your data.

- Import the SQL file "/adapter/sql-scripts/upgrade_2.1.0_to_2.1.0-hf4.sql" from any
  of the GUIs for the MySQL database, such as phpMyAdmin.

  Note that phpMyAdmin is now a link in the software that you can select.

==================================================================================
Closed Issues in 2.1.0_HF-004 (This Release)

ASG-4631
The bubble walk-through now starts immediately after a self registered user
logs in and changes their password.

ASG-4839
An index on the 'subscription_id' column has been added to the
asg_subscription_usage table, in addition to the primary index on the 'id' column.

ASG-4744
Spotfire reports are now displayed on a full page.

ASG-4899
A column for storing the UUID in the asg_logs table has been added to
errors logged by the portal engine.

ASG-4900
The data format of the access column has been changed to int(10). This
allows storing any integer.

ASG-4905
TIBCO API Exchange Manager now displays appropriate (meaningful) error messages to 
the end user for the errors returned from the portal engine.

ASG-4926
The Self Registration process is slow on TIBCO API Exchange Manager.

ASG-4963
TIBCO API Exchange Manager now allows users to search error messages using uuid 
in the logs.

AS-5096
The message that the portal displays when there is an error in parsing
a JSON spec now informs the user that the invalid spec can be replaced
or operations updated manually.

==================================================================================
COPYRIGHT & LICENSE INFORMATION

Except as stated below, this code is made available under the 
GNU General Public License, version 2.0. Copies of the applicable 
licenses are contained in a file titled "LICENSE.txt" that is included
with this code distribution.

TIBCO package:
    * Copyright (c) 2013-2014 TIBCO Software Inc. ALL RIGHTS RESERVED.
    * Copyright (c) 2011 - 2013  Wordnik, Inc. (licensed under the Apache
      License, version 2.0)
    * Copyright (c) 2009-2012 Jeremy Ashkenas, DocumentCloud
      Inc. (licensed under the MIT license)
    * Copyright (c) 2011 by Yehuda Katz 
    * Copyright (c) 2010 "Cowboy" Ben Alman (MIT and GPL licenses)

Joomla package:
    * Copyright (c) 2005 - 2013 Open Source Matters. All rights reserved.
    * See License details at http://docs.joomla.org/Joomla_Licenses

Swagger:

Cobalt packages:
     * Copyright (c) 2005 - 2014 MintJoomla
==================================================================================
