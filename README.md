# Moodle-filter_showtopiccontent
This is a simple Moodle filter plugin which will replace a formatted string with the list of activities within a lesson. This is useful to create a "list of contents" for a user to refer back to the activities in the lesson by clicking the relevant web page links.

To use, insert a Moodle "Label" after the lesson(s) in your module/unit and insert the search keyword into the Label Text in the following manner:

{show_module_pages nnn,nnn}

This assumes you have two lessons in the module whos IDs are nnn & nnn

The shortcut is replaced with an HTML DIV containing an unordered list with each of the activity titles as HREFs to the activities.
