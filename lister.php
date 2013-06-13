<?php /* Chronicle.md - Copyright (C) 2013 Bruce Alderson */

/* Chronicle (Dave) Lister

	Lists files, possibly caching the results.

*/
class lister {

	static $files;
	static $in;

	/* List the files in a folder, including metadata */
	static function folder($in, $url, $page = 0, $pageSize = 0) {
	
		$at = 0;
		$files = '';
		$start = $page * $pageSize;
		$end = $start + $pageSize;

		$list = lister::files($in); // get the list of files in this root
		
		// generate the list of files in this follder, given the page and page size
		
		$c = count($list);
		$end = $c >= $end ? $end : $c;

		for ( $at = $start; $at < $end; $at++ )
			$files[] = $list[$at];

		// determine the paged links
		
		$prevLink = ($page != 0) ? "$url/page/" . sprintf('%d', $page - 1) : '';
		$nextLink = ($c >= $end) ? "$url/page/" . sprintf('%d', $page + 1) : '';

		// build a struct for the caller 
		
		$folder = (object) array(
			'in'	=> $in,
			'files' => $files,
			'prev'	=> preg_replace('/(\/+)/','/', $prevLink),
			'next'	=> preg_replace('/(\/+)/','/', $nextLink)
		);
		
		return $folder;
	}
	
	/* Get navigation relative to $url */
	static function relativeNav($in, $at, $base) {

		// scan posts and get next and prev URIs
		$path = lister::basefrom($at, $base); 	// get base folder
		$list = lister::files($path);			// scan from base folder
		$idx = array_search($at, $list); // find current post
		
		$prevLink = ''; $nextLink = '';
		
		if ($idx > 0)
			$prevLink = lister::urlize("$base/". $list[ $idx - 1 ], $base);
			
		if ($idx < count($list))
			$nextLink = lister::urlize("$base/". $list[ $idx + 1 ], $base);

		return (object) array(
			'in'	=> $in,
			'files'	=> $list,			
			'prev'	=> $prevLink,
			'next'	=> $nextLink
		);
		
	}
	
	/* Turn a path into a URL based on a pivot (root md file folder) */
	static function urlize($path, $pivot) {
		$parts = explode($pivot, $path);
		if (count($parts) < 3) return '';
		return preg_replace( '/(\/+)/', '/', $pivot.$parts[2] );
	}
	/* Turn a path into a base path based on a pivot (root md folder) */
	static function basefrom($path, $pivot) {
		$parts = explode($pivot, $path);
		return preg_replace( '/(\/+)/', '/', $parts[0].$pivot );		
	}
	
	/* File list access (cached) 
		
		Allows a file listing to be cached for a given root
	*/
	static function files($in) {
	
		if (empty(lister::$files) || (!empty(lister::$in) && lister::$in != $in)) {
			lister::$files = array_reverse(lister::directory($in));
		}
		
		return lister::$files;
	}
	
	/* Get a directory listing  (recursive) */
	static function directory($d, $g = "*") {
		$files = array();
		$scan  = glob(rtrim($d, '/') . '/' . $g);

		if (is_file($d))
			array_push($files, $d);
		elseif (is_dir($d)) foreach ($scan as $path)
			$files = array_merge($files, lister::directory($path, $g));

		return $files;
	}	
}