# moodle-block_aprende_coursenavigation

Note
====
This plugin was forked from https://github.com/DigiDago/moodle-course_modulenavigation by http://pimenko.com/

Introduction
============
Aprende Course navigation is a block that show the users a summary (like a table of content) of a course with sections name and a list of all resources and activties (except URL). 
One objective of this block is to replace classical block navigation in a way to present only the course contents and sections title.

If you click on resources and activites of the menu, you display the page of the resource or activity.

This block uses automaticaly names of sections and names of resources and activities. When you use course module navigation, we recommand to use pages to add videos or contents in order to be able to view all resources in the list of the block.
If you want to display course navigation on all pages of the course (main, activities, resources), make sure to check permission of the block and display it on "every page".

== Some configuration options ==

**Section Names**
- Option A : Clicking at section name will point to section area or section page (for example if you use a course format as one section by page).
- Option B : Clicking at section name will display the list of resources and activities

**Labels**
You can choose if labels are displayed or not in the menu with the option "toggleshowlabels".

**Expand/open menu**
You can chose if menu is always open with the option "togglecollapse"

**Show restricted activities**
If activity completion is used in the course, course navigation block display a proper icon to show state of completion. (Only available for activities)
If restrictions are used in the course, you can choose if the block should display or not restricted items. (Available for sections and activities).

Installation
============
 1. Put Moodle in 'Maintenance Mode' (docs.moodle.org/en/admin/setting/maintenancemode) so that there are no 
    users using it bar you as the administrator - if you have not already done so.
 2. Copy 'course_modulenavigation' to '/blocks/' if you have not already done so.
 3. Login as an administrator and follow standard the 'plugin' update notification.  If needed, go to
    'Site administration' -> 'Notifications' if this does not happen.
 4.  Put Moodle out of Maintenance Mode.

Some configuration notes
====================
Once te plugin has been installed correctly:
1. Ensure the old block(Coursemodule Navigation) was unistalled.
2. Enable block editing mode from the LMS main admin page.
3. With block editing mode on, go to the homepage (/index.php?redirect=0) and add a new Course Navigation block.
4. Review the options on the new added block and choose "Display on any page" The block knows that it should be displayed on course and activity pages.
5. To manage plugin system options, go to the admin area on the plugins section.

About the main moodle menu (flat navigation)
====================
To be able to customize the moodle flat navigation the best option is to use and configure:
 - https://moodle.org/plugins/local_boostnavigation
2. Once installed and configured, you will be able to choose the nodes that will be shown on the menu, and create custom ones with several rules based on roles, contexts, and user data, even it is possible to manege the menu icons.
3. For more info have a read on: https://github.com/moodleuulm/moodle-local_boostnavigation/blob/master/README.md

Uninstallation
==============
 1. Put Moodle in 'Maintenance Mode' so that there are no users using it bar you as the administrator.
 2. In '/blocks/' remove the folder 'course_modulenavigation'.
 4. In the database, remove the for 'course_modulenavigation' ('plugin' attribute) in the table 'config_plugins'.  If
    using the default prefix this will be 'mdl_config_plugins'.
 5. Put Moodle out of Maintenance Mode.

Version Information
===================
See changelog.md


Languages and translation
===================
English and spanish versions included / versiones en inglés y español.


Us
==
@copyright 2021 Aprende Institute. https://aprende.com
