=== Just Another Instagram Feed ===
Contributors: sforsberg
Donate link: 
Tags: Instagram,API,custom,feed
Requires at least: 3.0.1
Tested up to: 3.8.1
Stable tag: 0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A highly customizable Instagram plugin that allows you to place Instagram feeds anywhere using widgets, shortcodes or directly with PHP code 

== Description ==

A highly customizable Instagram feed plugin that allows you to place any specified Instagram feed in the form of widgets, shortcodes or using PHP code. Templates and hooks exist for almost every section of output created by Just Another Instagram Feed allowing you to fully customize the display of your feeds. Just Another Instagram Plugin can also be used to search Instagram based on tags/users, as well as the ability to allow users follow other Instagram users and like posts from within any site using the Instagram API found [here](https://github.com/cosenary/Instagram-PHP-API).  

= Just Another Instagram Feed features include: =

* Ability to place an Instagram feed via shortcode, widget or php code based on a user or tag
* Highly customizable template files for almost every section of the feed
* [jQuery RS Carousel](https://github.com/richardscarrott/jquery-ui-carousel) and [jQuery fancyBox](https://github.com/fancyapps/fancyBox) optional integration. RS Carousel is under development.
* Shortcode: [jaif]
* Widget: Just Another Instagram Feed

= Available Customizations: =

* Unlimited column numbers per feed (Limited to CSS classes defined, columns 1-5 defined by default) 
* Full template control using custom JAIF hooks and/or template file modifications, hook index coming soon...

== Installation ==

1. Download Just Another Instagram Feed from [Wordpress.org](https://downloads.wordpress.org/plugin/just-another-instagram-feed-stable.zip)
1. Upload `just-another-instagram-feed.zip` to your `../wp-content/plugins/` directory
1. Extract the contents of the uploaded .zip file into the `../wp-content/plugins/` directory 
1. Activate the plugin through the 'Plugins' menu in the WordPress Admin area
1. Follow the instructions provided to you on-screen after plugin has been activation

= OR =

Install the plugin from within your wp-admin of your Wordpress site by following the official [Wordpress plugin installation procedures](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins)


== Frequently Asked Questions ==

= How do I get my Instagram API Client Secret and Client Keys? =

Please follow the instructions given at the Instagram Developers website [here](http://instagram.com/developer)

= What should I set as my Redirect URI? =

In almost all cases you will set the Redirect URI to your Wordpress siteurl, eg. http://your-site.com 
Make sure the Redirect URI you use in the JAIF settings matches the URI you used when were setting up your Instagram API Client over at [Instagram's Developer Portal](http://instagram.com/developer)  

= Is OAuth supported by JAIF? =

OAuth is currently not supported by JAIF, however will be developed at some point in the near future.

= Where do I put my custom JAIF template files? =

In order to safely modify template files for JAIF you will want to create a directory within your current theme's root directory called `jaif` and copy any template files you may have modified to that newly created directory. You do not need to copy template files that you did not modify. The JAIF template files can be found in the `templates` directory of the JAIF plugin.  

= Why do I get an error code '-1 - Unknown Caught Exception' when I try to use JAIF? =

This can occur for a number of reasons, however from personal experience this has to do with your server having an invalid SSL certificate, which is more common on local testing environments. You can find a possible solution to this SSL certificate problem [here](http://stackoverflow.com/questions/17478283/paypal-access-ssl-certificate-unable-to-get-local-issuer-certificate/19149687#19149687). If you are unable to determine the cause, please feel free to [submit a bug report](http://wordpress.org/support/plugin/just-another-instagram-feed).

== Screenshots ==

== Changelog ==

= 0.3 =
* Added the search type: `user-media` - to display a specific user's posts (does not display private user's posts)
* Added to the Help tab: `PHP Code` and provided more information about the variables used in all 3 JAIF display methods in the JAIF Help tabs
* Enabled each instance of JAIF on a single page to be unique for use with multiple fancybox galleries 
* Fixed a bug in the pagination where JAIF would continue to load the same "No More Posts" message to the feed, now if no more posts are found the button is disabled and the message is shown in the load more button instead
* Fixed a bug that prevented an API cache from being created if the filename exceeded the filesystem's filename character limit
* Cleaned up some of the code (replaced tabs with double spaces) **WIP**
* Updated styling

= 0.2 = 
* Fixed all known errors/warnings present when a fresh install and activation has occurred 
* Removed the persistent/annoying JAIF setup warning message on fresh install, now only shows up after
* Added Wordpress Help Tabs for JAIF Setup and Shortcode 
* Cleaned up the default templates
* Added a new hook: `jaif-end` - can be used to display additional content just before the JAIF containing div closes

= 0.1 =
* Initial Wordpress.org release

== Upgrade Notice ==

= 0.3 =
Enables you to display public users' posts.  