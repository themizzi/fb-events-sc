Facebook Events Shortcode Plugin for Wordpress
==============================================

Usage:
------

This shortcode will get the events for a page from the Graph API from the most recent down. It is not efficient because the Graph API doesn't have the ability to order events, which is silly, but it works.

[fb_events_sc page_id="PAGE_ID" limit="NUMBER_OF_RESULTS"]

Where PAGE_ID is equal to the Facebook page id for the Graph API and NUMBER_OF_RESULTS is the number of listings you want to show. The limit parameter is optional.
