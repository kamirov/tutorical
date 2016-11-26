<?
    $count = count($subjects_array);
    sort($subjects_array);

    if (!isset($usage))
    {
    	$usage = NULL;
    }

    // Null usage -> default subject table shown
    if (!$usage)
    {
	    $subjects = "<table>";
	    for ($i = 0; $i < $count; $i += 2)
	    {
	        $subjects .= '<tr>';
	                
				$subjects .= '<td>';
				    $subjects .= $subjects_array[$i];
				$subjects .= '</td>';

				if (isset($subjects_array[$i+1]))
				{
				    $subjects .= '<td>';                              
				          $subjects .= $subjects_array[$i+1];
				    $subjects .= '</td>';
				}
				else
				{
				      $subjects .= '<td></td>';
				}
	        $subjects .= '</tr>';
	    }
	    $subjects .= "</table>";
    }
    elseif ($usage == 'classifieds')
	{
		$inline_styles = 'padding: 7px 10px 7px 0; width: 50%;';
		$inline_row_styles = 'style="border-bottom: 1px solid #e6e7e7"';
		$inline_table_attributes = 'width="100%"';
		$font_size = "2";

	    $subjects = '<table '.$inline_table_attributes.'>';
	    for ($i = 0; $i < $count; $i += 2)
	    {
	        if ($i < $count - 2)
	        {
		        $subjects .= '<tr '.$inline_row_styles.'>';
	        }
	        else
	        {
		        $subjects .= '<tr>';
	        }

	        if ($i == 0)
	        {
				$subjects .= '<td style="'.$inline_styles.' padding-top: 0;">';
	        }
	        else
	        {
				$subjects .= '<td style="'.$inline_styles.'">';	        	
	        }
			    $subjects .= "<font size='$font_size'>".$subjects_array[$i]."</font>";
			$subjects .= '</td>';

			if (isset($subjects_array[$i+1]))
			{
		        if ($i == 0)
		        {
					$subjects .= '<td style="'.$inline_styles.' padding-top: 0;">';
		        }
		        else
		        {
					$subjects .= '<td '.$inline_styles.'>';	        	
		        }

		        $subjects .= "<font size='$font_size'>".$subjects_array[$i+1]."</font>";
			    $subjects .= '</td>';
			}
			else
			{
			      $subjects .= '<td '.$inline_styles.'></td>';
			}

	        $subjects .= '</tr>';

//			var_dump($subjects);
	    }
	    $subjects .= '</table>';
    }
    elseif ($usage == 'kijiji')
	{
		$inline_styles = 'padding: 7px 10px 7px 0; width: 50%;';
		$inline_row_styles = 'style="border-bottom: 1px solid #e6e7e7"';
		$inline_table_attributes = 'width="100%"';
		$font_size = "2";

	    $subjects = '<table '.$inline_table_attributes.'>';
	    for ($i = 0; $i < $count; $i += 2)
	    {
	        if ($i < $count - 2)
	        {
		        $subjects .= '<tr '.$inline_row_styles.'>';
	        }
	        else
	        {
		        $subjects .= '<tr>';
	        }

	        if ($i == 0)
	        {
				$subjects .= '<td style="'.$inline_styles.' padding-top: 0;">';
	        }
	        else
	        {
				$subjects .= '<td style="'.$inline_styles.'">';	        	
	        }
			    $subjects .= "<font size='$font_size'>".$subjects_array[$i]."</font>";
			$subjects .= '</td>';

			if (isset($subjects_array[$i+1]))
			{
		        if ($i == 0)
		        {
					$subjects .= '<td style="'.$inline_styles.' padding-top: 0;">';
		        }
		        else
		        {
					$subjects .= '<td '.$inline_styles.'>';	        	
		        }

		        $subjects .= "<font size='$font_size'>".$subjects_array[$i+1]."</font>";
			    $subjects .= '</td>';
			}
			else
			{
			      $subjects .= '<td '.$inline_styles.'></td>';
			}

	        $subjects .= '</tr>';

//			var_dump($subjects);
	    }
	    $subjects .= '</table>';
    }

    echo $subjects;
?>