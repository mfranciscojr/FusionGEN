Installation

**** Default_Armory_v2! ****

 1. Extract the contents of the archive usually on the desktop.

 2. Copy the contents of the folder Content into the root folder of your copy of FusionGEN, merge and replace all.

 3. You must disable the FusionGen tooltip in order to the WoWHead tooltip to work properly.

	You can find it in the config/fusion.php
	 /*
	|--------------------------------------------------------------------------
	| Use FusionCMS tooltip system instead of WoWHead tooltips
	|--------------------------------------------------------------------------
	|
	| Put to false if you mainly have "blizzlike" items.
	|
	*/
	$config['use_fcms_tooltip'] = false;

 4. Clear your template cache.
 
 5. Clear your browsers cache.

 6. You are done.

Changelog:

Default_Armory_v2 changes

- Fetching all of the images from wowhead.
- Fetching tooltip from wowhead.
- Fetching item data from wowhead.
- Disabled fetching item_template for the items for certain emulators
- Enabled caching for the mayor part.

