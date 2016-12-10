==================================================================================
Project Name    : Adapter Code for TIBCO(R) API Exchange and Joomla!
Release Version : 2.3.0
Release Date    : October 2016
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

/cobalt - Contains Joomla extensions required for the API management portal.
          You must install these extensions after initial installation of
          the Joomla adapter code.  For detailed information see the Adapter
          Code for TIBCO(R) API Exchange Manager and Joomla! Installation and 
          Configuration Guide.
   
/joomla - Contains the basic Joomla distribution (Joomla 3.4.6)

/joomlaUpdate - Contains Joomla update distributions for migration

/adapter - Contains code developed for Joomla and MySQL to support
           integration with TIBCO(R) API Exchange Manager.


==================================================================================
DOCUMENTATION

The documentation for the Adapter Code for TIBCO API Exchange and Joomla!
includes the following:

- Adapter Code for TIBCO API Exchange and Joomla! Installation  -- 
  Describes installation of prerequisite software (MySQL, PHP, Apache, and Cobalt),
  installation of the Adapter Code for TIBCO API Exchange Manager and Joomla!, and
  selected configuration topics.

- Adapter Code for TIBCO API Exchange and Joomla! Administration --  Describes
  using the TIBCO API Exchange Manager Joomla administration interface to set up
  users, user groups, and core configuration.  Also describes how to use the
  Joomla-based TIBCO API Exchange Manager developer portal to perform
  administrative tasks for TIBCO API Exchange; for example, creation of APIs,
  products, and operations, setting up plans and subscriptions, and so on.
  
- Adapter Code for TIBCO API Exchange and Joomla! User's Guide -- Describes how 
  developers use the portal to create applications, associate plans
  with then, request subscriptions, request API keys, and run
  analytics to evaluate APIs.  
  
  The Adapter Code for TIBCO API Exchange Manager and Joomla! documentation
  is available at the following URL: 
     https://github.com/API-Exchange/JoomlaAdapter/wiki
    
======================================================================
Third-party Software

   Database
     MySQL 5.5.x

   Web Browser
     Mozilla Firefox 20.0 +
     Google Chrome 37.0 +
     Microsoft Internet Explorer 10.0.x, 11.0.x

==================================================================================
INSTRUCTIONS

On UNIX, for example, from the directory where you unzipped this
distribution copy the files as follows:

cp -r ./joomla/* $APACHE_HOME/htdocs
cp -r ./adapter/joomla/* $APACHE_HOME/htdocs

For detailed instructions, see the Adapter Code for TIBCO API Exchange Manager and 
Joomla! Installation and Configuration document.

==================================================================================
COPYRIGHT & LICENSE INFORMATION

Except as stated below, this code is made available under the 
GNU General Public License, version 2.0. Copies of the applicable 
licenses are contained in a file titled "LICENSE.txt" that is included
with this code distribution.

TIBCO package:
    * Copyright (c) 2013-2016 TIBCO Software Inc. ALL RIGHTS RESERVED.
    * Copyright (c) 2011 - 2016  Wordnik, Inc. (licensed under the Apache
      License, version 2.0)
    * Copyright (c) 2009-2012 Jeremy Ashkenas, DocumentCloud
      Inc. (licensed under the MIT license)
    * Copyright (c) 2011 by Yehuda Katz 
    * Copyright (c) 2010 "Cowboy" Ben Alman (MIT and GPL licenses)

Joomla package:
    * Copyright (c) 2005 - 2016 Open Source Matters. All rights reserved.
    * See License details at http://docs.joomla.org/Joomla_Licenses

Cobalt packages:
     * Copyright (c) 2005 - 2016 MintJoomla
==================================================================================
