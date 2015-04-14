<h2><?php _e('Table of Contents', 'qa-focus-plus'); ?></h2>

<ul style="margin-bottom:20px">
	<li><a href="#general"><?php _e('Introduction', 'qa-focus-plus'); ?></a></li>
	<li><a href="#shortcodes"><?php _e('Shortcodes', 'qa-focus-plus'); ?></a></li>
    <li><a href="#widget"><?php _e('The Recent FAQs Widget', 'qa-focus-plus'); ?></a></li>
    <li><a href="#taxonomy"><?php _e('Tag Support', 'qa-focus-plus'); ?></a></li>
</ul>


<h2 id="general"><?php _e('Introduction', 'qa-focus-plus'); ?></h2>	

<p><?php _e('Q &amp; A Focus Plus FAQ is based on the popular Q &amp; A Plus FAQ and Knowledge Base by Raygun. It adds new features and enhancements to make it easy to create an even better full-featured, fully searchable FAQ on your WordPress site. You can add an unlimited number of entries and group them by category, bring questions into focus (similar to anchor links), add post tags, allow comments and let users rate your FAQs.', 'qa-focus-plus'); ?></p>

<p><?php _e('To get started, click on the', 'qa-focus-plus'); ?> <strong><?php _e('Q &amp; A Focus Plus', 'qa-focus-plus'); ?></strong> <?php _e('section of the', 'qa-focus-plus'); ?> <strong><?php _e('Settings', 'qa-focus-plus'); ?></strong> <?php _e('menu of your WordPress Dashboard. The first thing you&#8217;ll want to do is create a FAQ homepage, this is where visitors will be able to view your FAQs. This can be a page that already exists, or the plugin can automatically create the page and add the shortcode for you. By default, the FAQ homepage is &#8220;faqs&#8221;, so if that works for you, go ahead and click the &#8220;Create Page&#8221; button to add a new page to your site.', 'qa-focus-plus'); ?></p>

<p><?php _e('To use a page that already exists on your site, enter the page slug in the', 'qa-focus-plus'); ?> <strong><?php _e('FAQ homepage', 'qa-focus-plus'); ?></strong> <?php _e('field. For example, the page slug of your &#8220;About&#8221; page is', 'qa-focus-plus'); ?> <strong><?php _e('about', 'qa-focus-plus'); ?></strong>. <?php _e('If you&#8217;d like your FAQs to be on a sub-page on your site, you can use a slash, so a page called &#8220;FAQs&#8221; that is a child page of &#8220;About&#8221; would have the slug', 'qa-focus-plus'); ?> <strong><?php _e('about/faqs', 'qa-focus-plus'); ?></strong>. <?php _e('You will then need to add the', 'qa-focus-plus'); ?> <code>[qafp]</code> <?php _e('or', 'qa-focus-plus'); ?> <code>[qa]</code> <?php _e('shortcode to that page.', 'qa-focus-plus'); ?></p>

<p><?php _e('The default options should work for most sites, so let&#8217;s create a few FAQs and see how they look. From the WordPress Dashboard, look for the', 'qa-focus-plus'); ?> <strong><?php _e('FAQs', 'qa-focus-plus'); ?></strong> <?php _e('menu, and then click', 'qa-focus-plus'); ?> <strong><?php _e('Add New', 'qa-focus-plus'); ?></strong>. <?php _e('Just like a typical WordPress post, you&#8217;ll be able to add a title and body content, as well as set your category, add tags and enable comments. The title is the &#8220;Question&#8221; part of the FAQ and will be displayed on the FAQ page. The content section is hidden by default and will be displayed when the visitor clicks on the title. The category section allows you to organize your FAQs into multiple categories which are displayed on the homepage and on their own individual category pages. A FAQ can belong to multiple categories.', 'qa-focus-plus'); ?></p>

<p><?php _e('Add your FAQ like you would any normal WordPress post. Once you&#8217;ve added some FAQs, visit your site and take a look. The FAQ homepage will be at yoursite.com/faqs by default, or wherever you set the FAQs homepage slug in the plugin settings.', 'qa-focus-plus'); ?></p>

<p><?php _e('Take a look at the options on the &#8220;Plugin Settings&#8221; tab and try them out. You can add a search box on the FAQ homepage, category pages, and control the position of the search box. You can also customimze the animations and other aspects of the FAQ show/hide behavior. You can also enable ratings, change the sort order, change the size of the category headings and apply custom CSS to the question titles. Each option has a small question mark like this next to it.', 'qa-focus-plus'); ?> <span class="vtip" title="<?php _e('This is a contextual tooltip. Hover over these to find out what a particular setting does.', 'qa-focus-plus'); ?>">?</span> <?php _e('Hover over this mark for a tooltip with more information about that option.', 'qa-focus-plus'); ?></p>

<h2 id="shortcodes"><?php _e('Shortcodes', 'qa-focus-plus'); ?></h2>

<h3><?php _e('The [qafp] and [qa] Shortcodes', 'qa-focus-plus'); ?></h3>	

<p><?php _e('The', 'qa-focus-plus'); ?> <code>[qafp]</code> <?php _e('and', 'qa-focus-plus'); ?> <code>[qa]</code> <?php _e('(for backwards compatibility) shortcodes allow you to put your FAQs on any page on your site, and has quite a few options. If you need to create a new FAQ page, just use the shortcode without any options. You can also use the shortcode to place individual FAQs, single FAQ categories, and FAQ pages with custom options anywhere on your site. You can combine most shortcode attributes in any combination you want. Here are the basic Q &amp; A Focus Plus shortcode options:', 'qa-focus-plus'); ?></p>

<p><strong><?php _e('FAQ homepage', 'qa-focus-plus'); ?></strong>: <code>[qafp]</code> <?php _e('and', 'qa-focus-plus'); ?> <code>[qafp]</code>. <?php _e('Will insert the entire FAQ homepage anywhere on your site.', 'qa-focus-plus'); ?></p>

<p><strong><?php _e('Single category page', 'qa-focus-plus'); ?></strong>: <code>[qafp cat=dogs]</code> <?php _e('and', 'qa-focus-plus'); ?> <code>[qa cat=dogs]</code>. <?php _e('By specifying a category slug, you can add an entire category of FAQ entries anywhere on your site. You can find the category slug on the', 'qa-focus-plus'); ?> <strong><?php _e('FAQs &rarr; FAQ Categories', 'qa-focus-plus'); ?></strong> <?php _e('page.','qa-focus-plus'); ?></p>

<p><strong><?php _e('Single FAQ page', 'qa-focus-plus'); ?></strong>: <code>[qafp id=123]</code> <?php _e('and', 'qa-focus-plus'); ?> <code>[qa id=123]</code>. <?php _e('By specifying an ID, you can insert an individual FAQ entry anywhere on your site.', 'qa-focus-plus'); ?></p>

<h3><?php _e('Hompage Specific Shortcodes', 'qa-focus-plus'); ?></h3>

<p><strong><?php _e('Limit', 'qa-focus-plus'); ?></strong>: <code>[qafp limit=5]</code> <?php _e('and', 'qa-focus-plus'); ?> <code>[qa limit=5]</code>. <?php _e('Controls the number of FAQs returned on the FAQ homepage. Use', 'qa-focus-plus'); ?> <strong>-1</strong> <?php _e('to return all FAQ entries.', 'qa-focus-plus'); ?></p>

<p><strong><?php _e('Enable excerpts', 'qa-focus-plus'); ?></strong>: <code>[qafp excerpts=true]</code> <?php _e('and', 'qa-focus-plus'); ?> <code>[qa excerpts=true]</code>. <?php _e('Whether to limit posts length on the homepage. Entries that are longer than 40 words will be shorted and a &#8220;Continue reading&#8221; link will be added. Possible values are', 'qa-focus-plus'); ?> <strong>true<?php //_e('true', 'qa-focus-plus'); ?></strong> <?php _e('or', 'qa-focus-plus'); ?> <strong>false<?php //_e('false', 'qa-focus-plus'); ?></strong>.</p>

<p><strong><?php _e('Show category links', 'qa-focus-plus'); ?></strong>: <code>[qafp catlink=true]</code> <?php _e('and', 'qa-focus-plus'); ?> <code>[qa catlink=true]</code>. <?php _e('Show links to the single category page below each category title. Works well in conjunction with the limit setting to condense your FAQ homepage. Possible values are', 'qa-focus-plus'); ?> <strong>true<?php //_e('true', 'qa-focus-plus'); ?></strong> <?php _e('or', 'qa-focus-plus'); ?> <strong>false<?php //_e('false', 'qa-focus-plus'); ?></strong>.</p>

<p><strong><?php _e('Category order', 'qa-focus-plus'); ?></strong>: <code>[qafp catorder=ID/name/slug/term_order]</code> <?php _e('and', 'qa-focus-plus'); ?> <code>[qa catorder=catorder=ID/name/slug/term_order]</code>. <?php _e('Sort the categories on the FAQ homepage by ID, name, slug, or term order. Possible values are', 'qa-focus-plus'); ?> <strong>ID<?php //_e('ID', 'qa-focus-plus'); ?></strong>, <strong>name<?php //_e('name', 'qa-focus-plus'); ?></strong>, <strong>slug<?php //_e('slug', 'qa-focus-plus'); ?></strong>, <?php _e('or', 'qa-focus-plus'); ?> <strong>term_order<?php //_e('term_order', 'qa-focus-plus'); ?></strong>.</p>

<p><strong><?php _e('Sort order', 'qa-focus-plus'); ?></strong>: <code>[qafp sort=menu_order/ratings]</code> <?php _e('and', 'qa-focus-plus'); ?> <code>[qa sort=menu_order/ratings]</code>. <?php _e('Sort the questions on the FAQ homepage by menu order, or ratings (ratings must be enabled first).', 'qa-focus-plus'); ?></p>

<h3><?php _e('General Shortcode Attributes', 'qa-focus-plus'); ?></h3>

<p><strong><?php _e('Show FAQ link on categories', 'qa-focus-plus'); ?></strong>: <code>[qafp homelink=above/below/both/none]</code> <?php _e('and', 'qa-focus-plus'); ?> <code>[qa homelink=above/below/both/none]</code>. <?php _e('Show link to the main FAQ page above, and/or below categories, or not at all. Possible values are', 'qa-focus-plus'); ?> <strong>above<?php //_e('above', 'qa-focus-plus'); ?></strong>, <strong>below<?php //_e('below', 'qa-focus-plus'); ?></strong>, <strong>both<?php //_e('both', 'qa-focus-plus'); ?></strong>, <?php _e('or', 'qa-focus-plus'); ?> <strong>none<?php //_e('none', 'qa-focus-plus'); ?></strong>.</p>

<p><strong><?php _e('Show category descriptions', 'qa-focus-plus'); ?></strong>: <code>[qafp catdesc=true]</code> <?php _e('and', 'qa-focus-plus'); ?> <code>[qa catdesc=true]</code>. <?php _e('Show FAQ category descriptions under the category titles. Possible values are', 'qa-focus-plus'); ?> <strong>true<?php //_e('true', 'qa-focus-plus'); ?></strong> <?php _e('or', 'qa-focus-plus'); ?> <strong>false<?php //_e('false', 'qa-focus-plus'); ?></strong>.</p>

<p><strong><?php _e('Search', 'qa-focus-plus'); ?></strong>: <code>[qafp search=home]</code> <?php _e('and', 'qa-focus-plus'); ?> <code>[qa search=home]</code>. <?php _e('Whether to show the search field. Possible values are', 'qa-focus-plus'); ?> <strong>home<?php //_e('home', 'qa-focus-plus'); ?></strong>, <strong>categories<?php //_e('categories', 'qa-focus-plus'); ?></strong>, <strong>both<?php //_e('both', 'qa-focus-plus'); ?></strong>, <?php _e('or', 'qa-focus-plus'); ?> <strong>none<?php //_e('none', 'qa-focus-plus'); ?></strong> <?php _e('to disable the search field.', 'qa-focus-plus'); ?></p>

<p><strong><?php _e('Search position', 'qa-focus-plus'); ?></strong>: <code>[qafp searchpos=top]</code> <?php _e('and', 'qa-focus-plus'); ?> <code>[qa searchpos=top]</code>. <?php _e('Position of the search box, if enabled. Possible values are', 'qa-focus-plus'); ?> <strong>top<?php //_e('top', 'qa-focus-plus'); ?></strong> <?php _e('or', 'qa-focus-plus'); ?> <strong>bottom<?php //_e('bottom', 'qa-focus-plus'); ?></strong>.</p>

<p><strong><?php _e('Permalinks', 'qa-focus-plus'); ?></strong>: <code>[qafp permalinks=true]</code> <?php _e('and', 'qa-focus-plus'); ?> <code>[qa permalinks=true]</code>. <?php _e('Whether to show permalinks for individual FAQs. This makes it easier for users to click through and bookmark your content. Possible values are', 'qa-focus-plus'); ?> <strong>true<?php //_e('true', 'qa-focus-plus'); ?></strong> <?php _e('or', 'qa-focus-plus'); ?> <strong>false<?php //_e('false', 'qa-focus-plus'); ?></strong>.</p>

<p><strong><?php _e('Animation', 'qa-focus-plus'); ?></strong>: <code>[qafp animation=fade]</code> <?php _e('and', 'qa-focus-plus'); ?> <code>[qa animation=fade]</code>. <?php _e('Customize the animation style when opening and closing FAQs. Possible values are', 'qa-focus-plus'); ?> <strong>fade<?php //_e('fade', 'qa-focus-plus'); ?></strong> <strong>slide<?php //_e('slide', 'qa-focus-plus'); ?></strong>, <?php _e('and', 'qa-focus-plus'); ?> <strong>none<?php //_e('none', 'qa-focus-plus'); ?></strong>.</p>

<p><strong><?php _e('Accordion', 'qa-focus-plus'); ?></strong>: <code>[qafp accordion=true]</code> <?php _e('and', 'qa-focus-plus'); ?> <code>[qa accordion=true]</code>. <?php _e('Clicking on one FAQ entry closes any other open FAQ entries on the page. Setting this to', 'qa-focus-plus'); ?> <strong>false<?php //_e('false', 'qa-focus-plus'); ?></strong> <?php _e('will allow multiple FAQs to be open and visible on the page at the same time.', 'qa-focus-plus'); ?></p>

<p><strong><?php _e('Collapsible', 'qa-focus-plus'); ?></strong>: <code>[qafp collapsible=true]</code> <?php _e('and', 'qa-focus-plus'); ?> <code>[qa collapsible=true]</code>. <?php _e('You can completely disable the show/hide behavior by setting this to', 'qa-focus-plus'); ?> <strong>false<?php //_e('false', 'qa-focus-plus'); ?></strong>.</p>

<p><strong><?php _e('Show expand and collapse symbol', 'qa-focus-plus'); ?></strong>: <code>[qafp plusminus=true]</code> <?php _e('and', 'qa-focus-plus'); ?> <code>[qa plusminus=true]</code>. <?php _e('Display the plus/minus (expand and collapse) signs beside the FAQ title when show/hide behavior is set to', 'qa-focus-plus'); ?> <strong>true<?php //_e('true', 'qa-focus-plus'); ?></strong>.</p>

<p><strong><?php _e('Focus', 'qa-focus-plus'); ?></strong>: <code>[qafp focus=true]</code> <?php _e('and', 'qa-focus-plus'); ?> <code>[qa focus=true]</code>. <?php _e('Adds a link anchor behavior. When set to', 'qa-focus-plus'); ?> <strong>true<?php //_e('true', 'qa-focus-plus'); ?></strong>, <?php _e('the current question will jump to the top of the browser window. You can completely disable the focus behavior by setting this to', 'qa-focus-plus'); ?> <strong>false<?php //_e('false', 'qa-focus-plus'); ?></strong>.</p>

<p><strong><?php _e('Horizontal Rule', 'qa-focus-plus'); ?></strong>: <code>[qafp hr=true]</code> <?php _e('and', 'qa-focus-plus'); ?> <code>[qa hr=true]</code>. <?php _e('Adds a horizontal rule after each answer. You can completely disable the horizontal rule by setting this to', 'qa-focus-plus'); ?> <strong>false<?php //_e('false', 'qa-focus-plus'); ?></strong>.</p>

<h3><?php _e('Miscellaneous Shortcodes', 'qa-focus-plus'); ?></h3>

<p><strong><?php _e('Show Home Link', 'qa-focus-plus'); ?></strong>: <code>[qafp_show_home_link]</code>. <?php _e('Adds a link to the FAQ home page anywhere on the site.', 'qa-focus-plus'); ?></p>

<p><strong><?php _e('Show last updated', 'qa-focus-plus'); ?></strong>: <code>[qafp_show_last_updated]</code>. <?php _e('Displays the date that the FAQ was last updated anywhere on the site, even when the option to display it on the FAQ home page is disabled. It only displays the date: no extra text or formatting.'); ?></p>

<h2 id="widget"><?php _e('Recent FAQs Widget', 'qa-focus-plus'); ?></h2>

<p><?php _e('Q &amp; A Focus Plus FAQ includes a recent FAQs widget. Add it by dragging the', 'qa-focus-plus'); ?> <strong>Recent FAQs</strong> <?php _e('widget from the available widgets to the location you want it to appear on your site. You can change the widget title and the number of FAQ entries to display.', 'qa-focus-plus'); ?></p>

<h2 id="taxonomy"><?php _e('Tag Support', 'qa-focus-plus'); ?></h2>

<p><?php _e('Q &amp; A Focus Plus supports post tags. You can add tags to each question in the editor. The tags will function like the tags used on standard posts and even show up in your tag cloud.', 'qa-focus-plus'); ?></p>

<p><?php _e('If you are already using a plugin that adds post tag taxonomy support for custom post types, you can disable the built-in support by checking the &#8220;disable built-in tag support&#8221; option. If you don&#8217;t have any other custom post type taxonomy support enabled, disabling the built-in taxonomy support will break any tags that you have added to your FAQs. Broken tags will produce &#8220;nothing found&#8221; or other errors when clicked on. If checking the &#8220;disable built-in tag support&#8221; option breaks your FAQ tags you will need to uncheck it. This option will be automatically disabled if you are using', 'qa-focus-plus'); ?> <a href="http://lanexatek.com/downloads/wordpress-plugins/easy-taxonomy-support" target="_blank"><?php _e('Easy Taxonomy Support', 'qa-focus-plus'); ?></a> <?php  _e('1.0.2, or higher', 'qa-focus-plus'); ?>.</p>

<p><?php _e('You can also hide the tags so that they do not appear on your FAQs. Tags should be hidden if you don&#8217;t have any other taxonomy support and your tags are producing &#8220;nothing found&#8221; errors.', 'qa-focus-plus'); ?></p>