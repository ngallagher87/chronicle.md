# Chronicle.md

*Version: proof of concept*

A micro blogging and website tool for publishing Markdown, PHP, and HTML files. 

How does it work?

	home.md
	index.php
	blog/
		index.php
		2012/some-post.md

You write blog posts and pages in plaintext files, either in Markdown, text, or HTML. Your site templates are in plain old PHP, loaded based on the request, defaulting to the root template. No database. Simple setup. And no magic.

Your templates display site settings and content with simple PHP.

A full example is available at: https://github.com/robotpony/warpedvisions.org

1. Print out a page's content:

    print $site;

2. Display some configuration:

    print $site->settings->name;


## Requirements

* PHP 5.3+ (w/json)
* Apache with rewrite rules
* PrestoPHP 1.1+
* Markdown extra

## Installation

1. Install `PrestoPHP`, `Markdown Extra`, and `Chronicle` to the `lib` folder in a web root.
2. Symlink `combobulate.php` to the web root.
3. Copy the example `htaccess` file to the web root.

Your web root will now look something like:

	combobulate.php	-> lib/chronicle.md/combobulate.php
	.htaccess
	index.php (Main template)
	site.json (Settings)
	blog/     (Markdown blog posts)
	pages/    (More markdown things)
	home.md   (More markdown)
	js
	lib
	styles

## Features
	
1. Templates are standard PHP files arranged in folders as if they were your site. One per type of thing you would like to have on your site.
2. Listings are available for any given root (not yet available).
3. Caching is not yet available, but planned for things like listings, Markdown files, and so on.

   