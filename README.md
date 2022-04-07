Description:

Default_Armory_v2.1 - Display character items , stats, achievements and professions. (Using WoWhead API)
Should support all the major emulators. Needs testing.
This module is developed and maintaned by Favorit from FusionGEN
--------------------------------------------------------------------------

Installation:

 1. Extract the contents of the archive usually on the desktop.
 --------------------------------------------------------------------------
 2. Copy the contents of the folder Content into the root folder of your copy of FusionGEN, merge and replace all.
 --------------------------------------------------------------------------
 3. Import the content from to SQL folder into your fusiongen database.
 --------------------------------------------------------------------------
 4. You must enable this in your worldserver.conf to display character stats properly.
 --------------------------------------------------------------------------
  		
	#    PlayerSave.Stats.MinLevel
  	#        Description: Minimum level for saving character stats in the database for external usage.
  	#        Default:     0  - (Disabled, Do not save character stats)
  	#                     1+ - (Enabled, Level beyond which character stats are saved)
	PlayerSave.Stats.MinLevel = 1

 5. You must disable the FusionGen tooltip if you dont have item_template in you world database in order to the WoWHead tooltip to work properly.
 --------------------------------------------------------------------------
	
	You can find it in the config/fusion.php
	
	Use FusionCMS tooltip system instead of WoWHead tooltips
	
	Put to false if you mainly have "blizzlike" items.

	$config[- use_fcms_tooltip- ] = false;
	
 6. Clear your template cache.
 --------------------------------------------------------------------------
 7. Clear your browsers cache.
--------------------------------------------------------------------------
 8. You are done.
 --------------------------------------------------------------------------
 
Tested emulators:

- CATA without item_template (The Cataclysm Preservation Project)
- TrinityCore Legion
- TrinityCore BFA
- TrinityCore Shadowlands
- AzerothCore

Compatible emulators (Untested): 

- trinity :
- trinity_tbc :
- trinity_cata :
- trinity_mop :
- trinity_wod :
- skyfire :
- arkcore :
- oregoncore :
- ascemu :
- arcemu :
- mangosr2 :
- mangos :
- mangoszero :
- vmangos :
- azerothcore :
- skyfire_mop :

Changelog:

Default_Armory_v2.1

- Added achievements and professions.

Default_Armory_v2

- Fetching all the images from wowhead.
- Fetching the tooltips from wowhead.
- Fetching the item data from wowhead.
- Disabled fetching item_template data for the items, only if the emulator dosn- t support item_template.
- Enabled caching for the major part.
