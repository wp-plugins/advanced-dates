Advanced Dates (WordPress Plugin)
==============

Allows publishers to easily customize the publication year of posts and pages.

**DESCRIPTION**

Extending the literary, documentary, and archival potential of WordPress, this plugin allows publishers to easily customize the publication year of posts and pages.

Tags: dates, get_the_date, get_the_time, historical timestamp, history, old dates, pre-1969 timestamp, pre-1970 timestamp, the_date, the_time, year

**INSTALLATION**

After installing and activating the plugin (http://codex.wordpress.org/Managing_Plugins), visit Settings > Advanced Dates. There, publishers can chose to either adjust dates site-wide or on a page-by-page and post-by-post basis. If a publisher choses the latter, s/he'll be able to selectively (de)activate the plugin, customize the annual differential, and (un)freeze dates on each page/post's editing screen.

Please note that the plugin will not take effect, even after activation, until a publisher chooses one of the two options in the Settings > Advanced Dates admin screen.

**LINKS**

For feedback and help, visit: http://getsatisfaction.com/studio_hyperset/products/studio_hyperset_wordpress_plugins

To learn about other Studio Hyperset WordPress plugins, visit http://studiohyperset.com/#solutions

**DEVELOPER NOTES**

SH is very interested in the ways in which publishers use the Advanced Dates plugin. Please share ideas and usage notes in the comments section of the plugin URI: http://studiohyperset.com/wordpress-advanced-dates-plugin/4016

Also, the WordPress functions get_the_time and the_time work only on pages while get_the_date and the_date work only on posts. This is largely meaningless since developers can display the same date, time, and publication information using all four. Nevertheless, when developing a theme that builds on the Advanced Dates plugin, or trying to integrate the plugin into an existing theme, developers will want to keep these WordPress boundaries in mind.

Moreover, if a publisher or developer finds the plugin isn't working for a particular theme, it's likely the result of improper usage of one or more of the four date-oriented WordPress functions referenced above. For the Advanced Date plugin to work, developers need to use the get_the_time and the_time functions on pages and the get_the_date and the_date functions on posts.

**FUTURE BUILDS**

- integrate "creative" date framework for sci-fi and speculative fiction and other create-your-own calendar and date systems

**CHANGELOG**

- 1.0 (8/31/11) - Initial release