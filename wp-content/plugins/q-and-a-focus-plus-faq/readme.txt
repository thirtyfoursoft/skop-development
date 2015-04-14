=== Q and A Focus Plus FAQ ===
Contributors: ELsMystERy, Dalton
Author: Lanexatek Creations
Author URL: http://lanexatek.com
Plugin URL: http://lanexatek.com/downloads/wordpress-plugins/qa-focus-plus
Requires at least: 3.6.1
Tested up to: 3.9.1
Stable tag: 1.3.9.7
Tags: FAQ, Frequently, Asked, Questions, Knowledge, Comments, Tags, Ratings, Anchor
License: GPLv2
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=E6XU6KH92J688

A powerful and easy to use full-featured FAQ with comments, tags and ratings for your WordPress site.

== Description ==

### Show the Love ###

If you like Q and A Focus Plus FAQ, please <a href="http://wordpress.org/support/view/plugin-reviews/q-and-a-focus-plus-faq">rate and review it</a>.

### Details ###

Q & A Focus Plus FAQ, by [Lanexatek Creations](http://lanexatek.com "Lanexatek Creations"), adds new features and enhancements to the popular Q & A Plus FAQ and Knowledge Base by Raygun, making it easy to create an even better full-featured, fully searchable FAQ on your WordPress site. The source code has been cleaned up and optimized and the JavaScript and CSS files have been minimized for better performance. It allows you to create, categorize, and reorder an unlimited number of FAQs and insert them into a page with simple shortcodes. Q & A Focus Plus uses the native Custom Post Type functionality in WordPress 3.0 and above with added support for comments and post tags.

Questions can be shown/hidden with a simple jQuery animation; users without JavaScript enabled will click through to the single question page. There is an option to have questions jump into "focus" at the top of the page when clicked on, much like anchor links. With the new ratings system, you can allow anonymous visitors to rate your FAQs, or restrict the ratings to logged in users only. It even includes a Recent FAQs widget! ALL FOR FREE!

Q & A Focus Plus supports post tags. You can add tags to each question in the editor. The tags will function like the tags used on standard posts and even show up in your tag cloud.

If you have version 1.0.5 or higher of Raygun's Q & A Plus FAQ and Knowledge Base installed, Q & A Focus Plus will automatically import your settings upon activation.

Version 1.3 includes these new features:

* Optimized source code, minified JavaScript, and CSS.

* A Recent FAQs widget.

* Support for comments.

* Add post tags to your FAQs.

* Question title focus/anchor link behavior.

* Ratings system.

* Change category header size and question title CSS.

* Sort the FAQ by menu order, or by ratings.

* Change the category sort order.

* Show category descriptions.

* Option to diaplay a collapse/expand symbol.

* Custom left margin indentations.

== Installation ==

IMPORTANT: If you have the Raygun/Momnt version of Q & A Plus FAQ and Knowledge Base installed you must deactivate it before installing this one. Your current FAQs will be preserved and Q & A Focus Plus will import your settings from Q & A version 1.0.5 and up. You will need to visit the settings page to activate some of the new features.

1. Extract the zip file and upload the contents to the wp-content/plugins/ directory of your WordPress installation. 

2. Deactivate Q & A FAQ and Knowledgbase by Raygun/Momnt, if it is installed.

3. Activate Q & A Focus Plus FAQ from plugins page. 

To get started, click on the **Q & A Focus Plus** section of the **Settings** menu of your WordPress Dashboard. The first thing you'll want to do is create a FAQ homepage, this is where visitors will be able to view your FAQs. This can be a page that already exists, or the plugin can automatically create the page and add the shortcode for you. By default, the FAQ homepage is "FAQS", so if that works for you, go ahead and click the **Create Page** button to add a new page to your site.

To use a page that already exists on your site, enter the page slug in the **FAQ homepage** field. For example, the page slug of your "About" page is "about". If you'd like your FAQs to be on a sub-page on your site, you can use a slash, so a page called "FAQs" that is a child page of "About" would have the slug "about/faqs". You will then need to add the "[qa]" shortcode to that page.

The default options should work for most sites, so let's create a few FAQs and see how they look. From the WordPress Dashboard, look for the **FAQs** menu, and then click **Add New**. Just like a typical WordPress post, you'll be able to add a title and body content, as well as set your category, add tags and enable comments. The title is the "Question" part of the FAQ and will be displayed on the FAQ page. The content section is hidden by default and will be displayed when the visitor clicks on the title. The category section allows you to organize your FAQs into multiple categories which are displayed on the homepage and on their own individual category pages. A FAQ can belong to multiple categories.

Add your FAQ like you would any normal WordPress post. Once you've added some FAQs, visit your site and take a look. The FAQ homepage will be at "yoursite.com/faqs" by default, or wherever you set the FAQs homepage slug in the plugin settings.

Take a look at the options on the "Plugin Settings" tab and try them out. You can add a search box on the FAQ homepage, category pages, and control the position of the search box. You can also customimze the animations and other aspects of the FAQ show/hide behavior. Each option has a small question mark next to it. Hover over this mark for a tooltip with more information about that option.

### The [qafp] and [qa] shortcodes ###

The [qafp] and [qa] (for backwards compatibility) shortcodes allow you to put your FAQs on any page on your site, and has quite a few options. If you need to create a new FAQ page, just use the shortcode without any options. You can also use the shortcode to place individual FAQs, single FAQ categories, and FAQ pages with custom options anywhere on your site. You can combine most shortcode attributes in any combination you want. Here are the basic Q & A Focus Plus shortcode options:

**FAQ homepage**: <code>[qafp]</code> and <code>[qafp]</code>. Will insert the entire FAQ homepage anywhere on your site.

**Single category page**: <code>[qafp cat=dogs]</code> and <code>[qa cat=dogs]</code>. By specifying a category slug, you can add an entire category of FAQ entries anywhere on your site. You can find the category slug on the **FAQs &rarr; FAQ Categories** page.

**Single FAQ page**: <code>[qafp id=123]</code> and <code>[qa id=123]</code>. By specifying an ID, you can insert an individual FAQ entry anywhere on your site.

Hompage specific shortcodes:

**Limit**: <code>[qafp limit=5]</code> and <code>[qa limit=5]</code>. Controls the number of FAQs returned on the FAQ homepage. Use **-1** to return all FAQ entries.

**Enable excerpts**: <code>[qafp excerpts=true]</code> and <code>[qa excerpts=true]</code>. Whether to limit posts length on the homepage. Entries that are longer than 40 words will be shortened and a "Continue reading" link will be added. Possible values are **true** or **false**.

**Show category links**: <code>[qafp catlink=true]</code> and <code>[qa catlink=true]</code>. Show links to the single category page below each category title. Works well in conjunction with the limit setting to condense your FAQ homepage.

**Category order**: <code>[qafp catorder=ID/name/slug/term_order]</code> and <code>[qa catorder=catorder=ID/name/slug/term_order]</code>. Sort the categories on the FAQ homepage by ID, name, slug, or term order.

**Sort order**: <code>[qafp sort=menu_order/ratings]</code> and <code>[qa sort=menu_order/ratings]</code>. Sort the questions on the FAQ homepage by **menu order**, or **ratings** (if ratings are enabled).

General shortcode attributes:

**Show FAQ link on categories**: <code>[qafp homelink=above/below/both/none]</code> and <code>[qa homelink=above/below/both/none]</code>. Show link to the main FAQ page above, and/or below categories, or not at all.

**Show category descriptions**: <code>[qafp catdesc=true]</code> and <code>[qa catdesc=true]</code>. Show FAQ category descriptions under the category titles.

**Search**: <code>[qafp search=home]</code> and <code>[qa search=home]</code>. Whether to show the search field. Possible values are **home**, **categories**, **both**, or **none** to disable the search field.

**Search position**: <code>[qafp searchpos=top]</code> and <code>[qa searchpos=top]</code>. Position of the search box, if enabled. Possible values are **top** or **bottom**.

**Permalinks**: <code>[qafp permalinks=true]</code> and <code>[qa permalinks=true]</code>. Whether to show permalinks for individual FAQs. This makes it easier for users to click through and bookmark your content. Possible values are **true** or **false**.

**Animation**: <code>[qafp animation=fade]</code> and <code>[qa animation=fade]</code>. Customize the animation style when opening and closing FAQs. Possible values are **fade**, **slide**, and **none**.

**Accordion**: <code>[qafp accordion=true]</code> and <code>[qa accordion=true]</code>. Clicking on one FAQ entry closes any other open FAQ entries on the page. Setting this to **false** will allow multiple FAQs to be open and visible on the page at the same time.

**Collapsible**: <code>[qafp collapsible=true]</code> and <code>[qa collapsible=true]</code>. You can completely disable the show/hide behavior by setting this to **false**.

**Show expand and collapse symbol**: <code>[qafp plusminus=true]</code> and <code>[qa plusminus=true]</code>. Display the plus/minus (expand and collapse) signs beside the FAQ title when show/hide behavior is set to **true**.

**Focus**: <code>[qafp focus=true]</code> and <code>[qa focus=true]</code>. **NEW FEATURE** Adds a link anchor behavior. When set to **true**, the current question will jump to the top of the browser window. You can completely disable the focus behavior by setting this to **false**.

**Horizontal Rule**: <code>[qafp hr=true]</code> and <code>[qa hr=true]</code>. **NEW FEATURE** Adds a horizontal rule after each answer. You can completely disable the horizontal rule by setting this to **false**.

Miscellaneous shortcodes:

**Show home link**: <code>[qafp_show_home_link]</code>. Adds a link to the FAQ home page anywhere on the site.

**Show last updated**: <code>[qafp_show_last_updated]</code>. Displays the date that the FAQ was last updated anywhere on the site, even when the option to display it on the FAQ home page is disabled. It only displays the date: no extra text or formatting.

== Frequently Asked Questions ==

= Is Q & A Focus Plus better than the old Q & A? =

We believe it is. A lot of time has been spent updating the old code to optimize it and remove many redundancies. We have also spent a lot of time adding new features and troubleshooting it on several different websites.

= Is Q & A Focus Plus totally free? =

It is indeed! You do not have to pay for any additional features and there is no premium version that you will need to buy in order to get premium features.

= Where can I find a demo of Q & A Focus Plus? =

Check out the FAQs at [ELsMystERy.com](https://elsmystery.com/content/home-studio-faq/ "ELsMystERy.com FAQ") and [Lanexatek.com](http://lanexatek.com/frequently-asked-questions "Lanexatek Creations FAQ").

= Why do I get an error when I try to install Q & A Focus Plus? =

You probably need to disable the Raygun/Momnt version of the Q & A FAQ and Knowledge Base for WordPress. Q & A Focus Plus will not install if Raygun's Q & A FAQ and Knowledge Base is enabled.

= Q & A Focus Plus did not import my settings from the Raygun/Momnt version of the Q & A FAQ and Knowledge Base for WordPress. =

Q & A Focus Plus will only import settings from version 1.0.5 and higher of Raygun's Q & A FAQ and Knowledge Base.

= Why do I get a "nothing found" error when I click the tags on my FAQs? =

Make sure that you have not checked "Disable built-in tag support" on the options page.

= Why does the question title CSS revert back to the default setting? =

You probably entered invalid CSS, or had some unsupported characters in it.

= What characters are supported by the question title CSS option? =

You can enter the following into the question title CSS input field: **alphanumeric characters** (A to Z and 0 to 9), **periods** (.), **dashes** (-), **colons** (:), **semicolons** (;) and the **percent sign** (%).

= The ratings do not seem to be working. =

Make sure that JavaScript is enabled in your browser. Also, you will only be able to rate each question once if you are not restricting ratings to logged in users only and you have left the "Visitor rating wait time" option blank.

= How do I prevent anonymous visitors from rating more then once? =

The plugin tries to prevent that when the "Visitor rating wait time" option is left blank. If someone has found a way around that, there isn't much that you can do other than restrict the ratings to registered users.

= How do I enable comments on my FAQs? =

Check "Allow comments" in the post editor. You can also enable comments on all FAQs by doing a bulk edit in the FAQ list. 

= When I click "Add comment" I get a 404 error. =

Go to the Admin -> Settings -> Permalinks page to refresh your permalinks and try again.

= When I select a parent category for FAQ categories all of the questions show up, but no categories?

We are aware of this and hope to have parent categories functioning correctly in a future update.

= With JavaScript disabled, clicking on FAQ titles causes a 404 error. =

You may need to refresh your permalinks. From the WP Dashboard, visit "Settings->Permalinks", then click "Save Permalinks".

= I'm having trouble with the plugin. What should I do? =

Read the documentation! If that does not help, check the [frequently asked questions](http://wordpress.org/plugins/q-and-a-focus-plus-faq/faq/ "FAQ"). If you do not find a solution there, you can get support on the [Wordpress Q and A Focus Plus FAQ plugin support page](http://wordpress.org/support/plugin/q-and-a-focus-plus-faq "Wordpress support page").

If you ask a question that is already answered in one of these resources, you probably won't get a reply. Ninety percent of the support requests we get are because people will not read the documentation, or experiment with different options. So, PLEASE, make sure that you try turning options off and on to see if that solves the issue before contacting us. We will be glad to help you solve undocumented issues, but we cannot help you solve problems caused by other programs, or not reading the documentation.

== Screenshots ==

1. The FAQ homepage.

2. A single FAQ page.

3. The plugin settings page.

4. The FAQ entry page.

== Upgrade Notice ==

Version 1.2 and higher is a customized upgrade to the original 1.0.6 version of Q and A Plus. If you have the Raygun/Momnt version installed you must deactivate it before installing this one. Your FAQs will be preserved. 

== Changelog ==

= 1.3.9.7 =

* Added author option to FAQ page.

= 1.3.9.6 =

* Fixed missing closing divs when viewing a single category.

* Fixed the updated date display to show the format selected in the WordPress admin settings.

= 1.3.9.5 =

* Partially corrected a problem with the Recent FAQs widget that caused it to not display the recent FAQs on category archives.

* Added option to the Recent FAQs widget to hide it on category archives, in the event that it does not function properly.

* KNOWN BUG: the Recent FAQs widget may display posts from different categories, as well as the recent FAQs when displayed on a category archive. The cause of this is undetermined at the time of this update.

* Fixed problem with expand/collapse indicator not working correctly with accordian behavior off. Thanks [t3rra]

= 1.3.9.4 =

* Changed STYLESHEETPATH back to TEMPLATEPATH because it broke search results on some themes.

= 1.3.9.3 =

* Changed the sort order of the FAQs on the Recent FAQs widget from date to modified. Thanks [GeoffM1968](http://wordpress.org/support/profile/geoffm1968).

* Changed TEMPLATEPATH to STYLESHEETPATH for better child theme support. Thanks [Ccile](http://profiles.wordpress.org/ccile).

= 1.3.9.2 =

* Made some minor code optimizations.

* Fixed translation issue with the "person/people found this helpful" part of the ratings.

* Fixed an issue that would sometimes prevent 0 (zero) from showing up as the number of ratings.

= 1.3.9.1 =

* Removed code that caused notices in WP debug mode when no categories are shown on the FAQ home page.

= 1.3.9 =

* Made settings page title translatable.

* Stopped translation of shortcode settings in documentation.

* Fixed bug that prevented some of the shorcode attributes from overriding the settings. 

* Added true/false option and [qafp catdesc] shortcode to show the category description under category titles.

* Added option and [qafp homelink] shortcode to show the link to the main FAQ page above, and/or below categories, or not at all.

* Added option and [qafp catorder] shortcode to change the order that categories appear on the main FAQ page.

* Fixed bug that prevented enqueued scripts from displaying version. Thanks [AugusGils](http://profiles.wordpress.org/augusgils).

* Corrected front end script enqueueing code, which was copied from another file in an older version and never changed. Thanks [AugusGils](http://profiles.wordpress.org/augusgils).

* Fixed two bugs where ratings caused undefined offset, and undefined variable errors. Thanks [unreal4u](http://profiles.wordpress.org/unreal4u).

* Some minor bug fixes.

* Changed developer branding from Spectrum Visions to Lanexatek Creations.

* Updated the documentation.

= 1.3.8.6 =

* Improved communications with the [Easy Taxonomy Support](http://wordpress.org/plugins/easy-taxonomy-support/ "Easy Taxonomy Support") plugin. Easy Taxonomy Support 1.0.2, or higher is recommended when used with Q and A Focus Plus.

* Overrides versions 1.0.0 and 1.0.1 of Easy Taxonomy Support to prevent conflicts.

* Added a rate and review link to the bottom of the admin page.

* Cosmetic improvements to admin page.

= 1.3.8.5 =

* Added option to set the indentation of the FAQ answer.

* Made some more minor cosmetic improvements.

* Changed width of the horizontal rule below each question to match the width of the entire question and not just the answer.

= 1.3.8.2 =

* Appearance adjustments for Wordpress 3.8 compatibility.

* Added option and [qafp plusminus] shortcode to show plus and minus signs next to FAQ titles to indicate the expanded and collapsed states when show/hide behavior is enabled.

* Added option to indent FAQs.

* Moved the "view category" link from below the entire category to below the category title.

* Various minor cosmetic changes.

* Changed Wordpress version requirements to 3.6.1 since some people with versions older than that were having problems with some of the new features.

= 1.3.8 = 

* Removed FAQ last updated information from categories to prevent it from showing multiple times when there is more than one category on a page.

* The link to FAQ home will now only appear when viewing a single FAQ, or category. It will no longer appear when using the shortcode in regular posts and pages.

* Added <code>[qafp_show_home_link]</code> shortcode to add a link to the FAQ home anywhere on the site.

* Added <code>[qafp_show_last_updated]</code> shortcode to display the FAQ last updated date anywhere on the site, even when disabled in the options.

* Optimized some more code.

* Made minor cosmetic changes to the FAQ last updated information CSS.

* Updated documentation.

= 1.3.7 =

* Removed unnecessary and redundant excerpt functions that were left over from the old version and did weird things with some themes.

* Tested for compatibility with Wordpress 3.7.

= 1.3.6.4 =

* Added option to show, or hide the FAQ last updated line.

* Changed the name of the .pot file to match the text domain.

= 1.3.6.2 =

* Minor changes made to the documentation.

= 1.3.6 =

* Fixed: forgot to update the .pot file in version 1.3.5.

= 1.3.5 =

* Fixed: search button translation problem.

* Updated the readme file.

= 1.3.4 =

* Removed breadcrumbs option.

= 1.3.3 =

* Improved communications between Q & A Focus Plus and Easy Taxonomy Support.

* Changed the comments link at the bottom of FAQs in page and category views to the Wordpress default.

* Fixed old bug: the excerpts option would not be checked after saving the settings.

= 1.3.2 =

* Reverted back to global post_tag support because the changes in 1.3.1 did not work correctly.

* Added option to disable tag support. This is useful if you do not want to use tags, or you have a plugin that provides post_tag support for custom post types.

* Added option to hide post tags so they do not display on the FAQs.

* Updated the documentation.

= 1.3.1 =

* Improved tag support. It now only adds post tag support to Q & A Focus Plas FAQs without filtering all other post types.

* Removed obsolete code, such as the authorization options.

* Made some minor cosmetic changes.

* Updated the documentation.

* Removed changes prior to 1.2.0 from the readme file to shorten it up. 

= 1.3.0 =

* Added option to restrict ratings to logged in members only, or allow anonymous visitors to rate questions.

* Added option to set the number of minutes an anonymous visitor must wait before rating a question again. This option can be left blank to restrict anonymous visitors from rating the same question more than once from the same IP address.

= 1.2.9 =

* Converted "true/false" string values from the original Q & A to boolean values.

= 1.2.8 =

* Fixed version update bug.

* Fixed comments link style issues.

= 1.2.7 =

* Added comment support.

* Added a Recent FAQs widget.

* Added FAQ last updated date and time at the top of the FAQ home page and category views.

= 1.2.6 =

* Removed ratings shortcode.

* Included taxonomy support in the plugin.

* Moved ratings options to their own settings group.

* Fixed bug that caused the selected ratings icon color option to get lost when turning off ratings.

= 1.2.5 =

* Fixed breadcrumbs. When enabled, breadcrumbs replace the category in the "Posted in:" meta field. The title is no longer converted to breadcrumbs. 

* Corrected problems with the way the front end JavaScript and CSS files were being enqueued.

* Removed the jQuery option, since Wordpress comes with jQuery there is no need to force load it from another source.

* Updated the documentation.

= 1.2.4 =

* Updated the documentation.

* Added CSS input checks.

* Added links to header and CSS information.

* Corrected code for better language translation support.

= 1.2.3 =

* Added settings are now imported from Q and A Plus during installation.

= 1.2.0 =

* Added focus behavior, setting and shortcode. This works like an anchor link, so the question jumps to the top of the page when opened.

* Added setting and shortcode to include ratings at the bottom of each answer for logged in users.

* Added setting and shortcode to sort questions on the FAQ homepage by menu order, or ratings (if ratings are enabled).

* Added setting and shortcode to have a horizontal rule placed at the end of each answer.

* Added setting to change the category header (H1, H2, H3, H4).

* Added setting to customize the question title CSS.

* Added links back to FAQ homepage in single post and category views.

* Added support for post tags when used with our Easy Taxonomy Support plugin.

* Minified front end CSS and JavaScript and optimized some of the code for better performance.