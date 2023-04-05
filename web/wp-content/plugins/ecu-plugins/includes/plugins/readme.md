Things to know about shortcake that are stupid:

1.  Checkboxes cannot be defaulted to be checked!  If you want a true/false ui element that can be defaulted to true you must use a select.   A get_yes_no_options function is available in the abstract shortcode class, so if your shortcode extends that class then you can use it.  Since they have stated they will not change the behavior I have left as is.

https://github.com/wp-shortcake/shortcake/pull/413

2.  Selects will default the value to the first item in the options array IF the first item is not empty.  This works with selects, but not multi-selects.     So if you don't want your selects/multi selects to automatically select the first value by default then you must make the first option an empty value or set the default value in the ui array.

In my use case the multi-select is a list of filters to be used to select the data. If none are selected then all the data is returned from the API call i am using to get the data. Right now I have set the default as 0 and if that is the value then I ignore it. It works, but is not intuitive.  This keeps me from having an empty option at the top of the multi-select that is always selected.   You can see this in the wp-localist shortcode: ( when my fixes get to prod )

https://github.ecu.edu/WordPress/cms/blob/master/wp-content/plugins/wp-localist/includes/shortcode.php
https://github.com/wp-shortcake/shortcake/pull/673/commits/dc0822cd245f9f9b5324bfb156f4a18e9d0adf69

I have also asked if they would change this for multi- select but I am not going to hold my breath due to their response on stupid number 1.

3.  Their QA is garbage and they average about a release a month ..... so don't upgrade unless there is a specific reason to.   And if you do check all functionality of the shortcodes before going to prod.

4.  Right now the shortcode ui will not show the selected options for selects when editing a shortcode.   I have fixed this by grabbing the fix out of their repo and tweaking it to work with multi- selects.   Be sure that this is confirmed to be working for selects and multi-selects when the next upgrade to shortcake happens.

https://github.com/wp-shortcake/shortcake/pull/738
