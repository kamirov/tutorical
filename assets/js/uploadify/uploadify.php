<?php

require('../../php/SimpleImage.php');

/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/

// Max values for image
$max_width = 600;
$max_height = 600;
$min_width = 200;
$min_height = 200;

// Define a destination
$target_path = '../../uploads/tmp';	// Yuck! But this is needed until we get a different AJAX upload script

if (!empty($_FILES)) 
{
	$temp_file = $_FILES['Filedata']['tmp_name'];
	$file_parts = pathinfo($_FILES['Filedata']['name']);
	$ext = (isset($file_parts['extension']) ? $file_parts['extension'] : NULL);

	//	$targetFile = rtrim($targetPath,'/') . '/' . $_FILES['Filedata']['name'];

	//	Randomize name
	//	$filename = md5($_SERVER['REQUEST_TIME']) . '.' . $ext;

	// We get the $ext above so we can confirm that it is an image, but we still convert to jpg
	$filename = md5($_SERVER['REQUEST_TIME']) . '.jpg';

	$target_file = rtrim($target_path,'/') . '/' . $filename;
	
	// Validate the file type
	$file_types = array('jpg','jpeg','gif','png'); // File extensions

	if ($ext && in_arrayi($ext,$file_types)) 
	{	
		$image = new SimpleImage();
		$image->load($temp_file);

		if ($image->failed)
		{
			$data = array(
				'error' => 'bad image'
			);
			echo json_encode($data);

			return;
		}

		// Resize width if needed
		if ($image->getWidth() > $max_width)
			$image->resizeToWidth($max_width);
		elseif ($image->getWidth() < $min_width)
			$image->resizeToWidth($min_width);

		// Resize height if needed
		if ($image->getHeight() > $max_height)
			$image->resizeToHeight($max_height);
		elseif ($image->getHeight() < $min_height)
			$image->resizeToHeight($min_height);
		
		$image->save($target_file, IMAGETYPE_JPEG, 100);

		$data = array(
			'name' => $filename,
			'trueWidth' => $image->getWidth(),
			'trueHeight' => $image->getHeight(),
			'error' => ''
		);

		echo json_encode($data);
	} 
	else 
	{
		$data = array(
			'error' => 'bad filetype'
		);

		echo json_encode($data);
	}
}

function in_arrayi($needle, $haystack) {
    return in_array(strtolower($needle), array_map('strtolower', $haystack));
}

?>