Developer:

Steve Callan

4/29/11

-----------------------------------

Description:

Access a users YouTube feed easily through ExpressionEngine without needing to use OAuth.

-----------------------------------

Installation Instructions:

- Upload the /youtubee folder to your system/expressionengine/third_party folder

-----------------------------------

User Guide

{exp:youtubee:entries user="YOUR_YOUTUBE_USERNAME" limit="HOW_MANY_ENTRIES_TO_SHOW" key="YOU_TUBE_VIDEO_ID"}

<article>

<h3>{title}</h3>

{short_description}

</article>

{/exp:youtubee:entries}
		
Variable Support:

title, short_description, image, views, time 

Parameters:

user: the username of the feed you would like to pull (required)

limit: Integer of how many items to show (not required)

key: If you would like to filter the results by a certain video user this parameter.  To retreive multiple videos separate by | (not required)