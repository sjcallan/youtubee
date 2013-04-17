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

**User Guide**

    {exp:youtubee:entries user="YOUR_YOUTUBE_USERNAME" limit="HOW_MANY_ENTRIES_TO_SHOW" key="YOU_TUBE_VIDEO_ID"}

    <article>
    <h3>{title}</h3>
    <p>{short_description}</p>
    </article>

    {/exp:youtubee:entries}
		
Variable Support:

title, short_description, image, views, time 

**Parameters**

user: the username of the feed you would like to pull (required)

limit: Integer of how many items to show (not required)

key: If you would like to filter the results by a certain video user this parameter.  To retreive multiple videos separate by | (not required)

**Variables**

{title} - The title of the video

{short_description} - The short description of the video

{image} - thumbnail of the video

{views} - The number of views this video has

{time} - The upload date of the video

{url} - The YouTube Video Link of the video

{key} - The unique identifier for this video