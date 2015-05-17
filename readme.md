Facebook Events Shortcode Plugin for Wordpress
==============================================

Usage:
------

This shortcode will get the events for a page from the Graph API from the most recent down. It is not efficient because the Graph API doesn't have the ability to order events, which is silly, but it works.

[fb_events_sc page_id="PAGE_ID" limit="NUMBER_OF_RESULTS" timezone="TIMEZONE_STRING"]

Where PAGE_ID is equal to the Facebook page id for the Graph API, NUMBER_OF_RESULTS is the number of listings you want to show, and TIMEZONE_STRING is the PHP formatted timezone string for the events to display their dates in. The limit and timezone parameters are optional.
