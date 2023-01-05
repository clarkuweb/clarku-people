# People Tool

A tool that creates a custom post type for people and makes it easier to manage people records.

## Shortcode

The base syntax for the shortcode is as follows: `[clarku-people]`.  There are additional attributes that can be used to further customize the shortcode's output.

### Shortcode attributes

All of the options below are optional.

	# `group` expects the slug of a valid peoplegroup category.
	# `group_operator` Defaults to "OR" but can be set to "AND"
	# `posts_per_page` expects a whole number. It limits the results to the specified amount. Default is 200
	# `link` controls whether or not the card lists link to posts.  Set to "false" to not link (default: true)
	# `email` controls whether or not to display the email address on card lists (default: true)
	# `phone` controls whether or not to display the phone number on card lists (default: true)
	# `thumbnail` expects the name of an image format like "medium."  Set to "false" to hide thumbnails.
	# `before` allows HTML to be inserted before the list of people. It defaults to `<div class="uri-people-tool">`
	# `after` allows HTML to be inserted after the list of people. It defaults to `</div>`

## Examples

Display a list of people in a "faculty" group:

```[clarku-people group="faculty"]```

Display a list of all people without links to people posts:

```[clarku-people link="false"]```

## Slug

The plugin creates a new URL slug `/people` so that posts display as `/people/[post-slug]`.  By default, the `/people` URL shows the standard WP archive page, but you can create a page with the same slug (`/people`) to override the default archive page.


## Theming the output

This plugin includes default templates. The templates will likely require customization to match your site's theme. To customize the templates, copy one or both of `clarku-people/templates/person-card.php` and `clarku-people/templates/single-people.php` to your theme's directory -- either into your theme's root, or into a directory in your theme called `template-parts`. `person-card` handles the shortcode output, and `single-people` handles the page view of each person.

Edit the files in your theme to taste.