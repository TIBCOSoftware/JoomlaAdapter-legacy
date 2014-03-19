==================================================================================
Project Name    : Adapter Code for TIBCO API Exchange and Joomla!
Release Version : 2.1.0
Release Date    : February 2014
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
   
/joomla - Contains the basic Joomla distribution (Joomla 3.1.1)

/adapter - Contains code developed for Joomla and MySQL to support
           integration with TIBCO(R) API Exchange Manager.


Contents of the /adapter Directory:

/joomla - Contains code developed for Joomla that supports the API
          management portal. During installation, this is merged
          with the contents of a standard Joomla distribution.

/sql-scripts - Contains a full SQL copy of the entire Joomla database.
               The SQL files include:

               - asg-openapi.sql        - A full SQL dump of the entire Joomla
                                          database.
               - install.mysql.utf8.sql - A SQL dump of only Joomla tables
                                          the project has modified.

	       This script may be used to migrate content to a fresh
	       installation of 2.0.0 for testing and staging. It is
	       not recommended for production deployments.
               - build.php              - PHP script that exports selected
                                          database tables and builds a Joomla
                                          extension installer.
               - file_openapi.db.zip    - Template used by build.php
               - file_openapi.db_v1.zip - A full Joomla installation for the open
                                          API database that defines the developer
                                          portal. You must install this as an 
                                          extension to Joomla.  
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
INSTRUCTIONS

On UNIX, for example, from the directory where you unzipped 
this distribution, on a UNIX system you can copy the files as follows:

cp -r ./joomla/* $APACHE_HOME/htdocs
cp -r ./adapter/joomla/* $APACHE_HOME/htdocs

For detailed instructions, see the Adapter Code for API Exchange Manager and 
Joomla! Installation and Configuration document.

==================================================================================
COPYRIGHT & LICENSE INFORMATION

Except as stated below, this code is made available under the 
GNU General Public License, version 2.0. Copies of the applicable 
licenses are contained in a file titled "LICENSE.txt" that is included
with this code distribution.

TIBCO package:
    * Copyright (c) 2013 TIBCO Software Inc. ALL RIGHTS RESERVED.
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
     * Copyright (c) 2005 - 2013 MintJoomla
==================================================================================
