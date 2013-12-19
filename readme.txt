| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET
| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET
| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET
| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET
| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET
| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET

for updates and more releases : http://ebx7.net/index.php?/topic/59-release-unofficial-srcms-free-website-for-your-server-v-12b/


-- This CMS is deprecated .
-- a newer version will be ready as soon as possible .

-- [Created by NATI LEVIN (ISRADEV @2013) ] -- 

Installation:

- Copy all files to your htdocs/wwwroot
- Run sql/install.sql at your sql server management studio (you might have to change account db name at that file)
- Remove comment lines from import_old_users.php [/* and */], and modify your sql server connection information, also, change avatar link !
- Go to http://yourwebaddress/import_old_users.php to import old users info into srcms userprofile db
- Create a web admin account using this query:

	use SRO_VT_ACCOUNT
	update srcms_userprofiles set whois='admin' where JID=(select JID from TB_User where StrUserID='your username')
	
Paypal System Installation:

- Enable extension=php_curl.dll from php.ini (Remove the ; and Restart Apache Server)
- Edit The File \module\ipn.php , Edit Database Info/Silk Prices/Paypal Email).
- Edit The File \module\shop.php , Edit Amounts+Prices,PayPal Email.
- Edit terms.txt change ServerName with your Server Name (Replace All!).

-------------------------------------------------------------

v0.0 - 0.4C
========================================================================================
	- News + comments
	- Downloads
	- Registration
	- Login/logout
	- Team page
	- Server status
	- Fortress status
	- Honor rank
	- Users on web
	- Change password
	- My characters
	- My profile [gender, avatar, skype, msn, publicity]
	- Mailbox [users can communicate directly at web through private messages]
	- User types (for now admin/user/gm)
	- Smileys in news/comments/private messages at website
	- Referals (user can get silk for inviting friends, has ip check)
	- Settings [admin cp] - contains nearly all settings for website (for example:
	  enabling/disablind character functions, setting max/min comment length at news etc)
	- Probably, some more i forgot to mention

v 0.5A
========================================================================================	
	- Admin CP itself
	- Epin system [user cp/admin cp]
	- Add/edit/delete news [admin cp]
	- Add/edit/delete download links [admin cp]
	- Edit CMS settings [admin cp]
	- Fixed honor rank
	- Some more BBcodes for news/comments
	- Once users enters website, if he's account has no SK_Silk record yet, it's being automatically added
	- Added char model pic to view char
v 0.5B
========================================================================================
		- User panel
		- My chars feature at user panel now actually has use
		- Teleport char to town [gold]
		- Reset char pk [silk]
		- Reset char stats [silk]
		- Settings at srcms_settings, you can edit them from admin panel as usually

v 0.6A
========================================================================================
	- User panel
	- Buy skill points [define silk price for each 100 000 SP in settings from ACP]
	- Fixed bug in admin panel (using smileys in news text while edited/added)
	- Little security fix at news module

========================================================================================
v 1.0A
	- Pretty huge website engine mod
	- Added guild rankings (also, guild info and members list)
	- Added race icon to top characters and guild rankings
	- Added "allowShowCharOwner" setting
	- Added last unique kills (overall)
	- Added last 30 unique kills for each character individually
	- Added character search function
	- Added guild search
	- Added job ranking
	- Multiple bugfixes [tons of them, wont describe every]
========================================================================================
v 1.1A
	- Fixed problem with "This character does not belong to you" - was pretty serious bug
	- Fixed problem with uppercase/lowercase username at logging in
	- Fixed XSS vulnerability at messages (news/msgbox etc)
	- Fixed using HTML tags in messages
	- Fixed sending messages at website [class/user.class.php]
========================================================================================
v 1.1B
	- Added Job Ranking by types .
	- Added a New Template .
	- Added Facebook Box .
	- Added Side Menu With stats .
	- Fixed lots of problems.
	- Fixed Security Issue with the ckeditor .
	- Added GPU Drivers Download Section.
	- Started to work at "Guides" at the website (will be added once static pages maker will be ready!).
	- Reduced Errors significatly! .
========================================================================================
v 1.2A
	- Added Job Ranking by types .
	- Added a New Template .
	- Added Facebook Box .
	- Added Side Menu With stats .
	- Fixed lots of problems.
	- Fixed Security Issue with the ckeditor .
	- Added GPU Drivers Download Section.
	- Started to work at "Guides" at the website (will be added once static pages maker will be ready!).
	- Reduced Errors significatly! .
	
========================================================================================
v 1.2B
	- Fixed The Login problem .
	- Added User Image to user panel .
	- Fixed Internet Explorer Login Button size.
	- Added Snow Effect (Removeable from index.php [read the comments there]
	- Cleaned and Removed Unused files/images .
	- Fixed import_old_users.php "JID" error . 
	
========================================================================================
v 1.3A
	- Added Password Reset Module.
	- Added Change Email Module .
	- Added new news style and system (if news older then 1 week , it wont show "new").
	- Added new buttons under login .
	- Fixed Possible Issues.
	- Starting to rewrite the Connection/Mssql class module .
	- Added missing tables.
	- Started working at vote4silk system .
	- Rewrote shop.php (now you just need to change email ,Amounts/prices , nothing else!!) .
	
| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET
| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET
| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET
| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET
| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET
| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET| EBX7.NET | EBX7.NET | EBX7.NET
