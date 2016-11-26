<? if (!defined('BASEPATH')) exit('No direct script access allowed');

class Paginator
{
	public $items_per_page;  
	public $items_total;  
	public $current_page;  
	public $num_pages;  
	public $mid_range;  
	public $low;  
	public $high;  
	public $return;  
	public $default_ipp = 10;		// Default number of items per page

	function __construct()
	{
		$this->ci =& get_instance();

        $this->current_page = (int) $this->ci->input->get('page'); // must be numeric > 0  
        $this->mid_range = 7;  
//        $this->items_per_page = $this->ci->input->get('ipp') ?: $this->default_ipp;
        $this->items_per_page = $this->default_ipp;
	}

	function paginate()
	{			
        // Validate ipp
        if (!is_numeric($this->items_per_page) || $this->items_per_page <= 0) 
        	$this->items_per_page = $this->default_ipp;  
        

        $this->num_pages = ceil($this->items_total/$this->items_per_page);  

		if ($this->current_page < 1 || !is_numeric($this->current_page)) 
			$this->current_page = 1;  
		elseif ($this->current_page > $this->num_pages)
			$this->current_page = $this->num_pages;  

		$prev_page = $this->current_page-1;
		$next_page = $this->current_page+1;

//        var_dump($this->current_page, $next_page, $prev_page, $this->num_pages);

		//If > 10 pages, show first and last, and a range of $this->mid_range between them (surrounded with ...)

        if ($this->current_page != 1 && $this->items_total >= 10)
        {
//                $url = this_url_with_query(array('page' => $prev_page, 'ipp' => $this->items_per_page));
            $url = this_url_with_query(array('page' => $prev_page));
            $this->return = anchor($url, "&laquo; Prev", "class='paginate buttons' data-page='$prev_page'");
        }
        else
        {
            $this->return = "<span class='inactive buttons paginate'>&laquo; Prev</span>";
        }

		if ($this->num_pages > 10)  
        {    	
            $this->start_range = $this->current_page - floor($this->mid_range/2);  
            $this->end_range = $this->current_page + floor($this->mid_range/2);  
  
            if ($this->start_range <= 0)  
            {  
                $this->end_range += abs($this->start_range) + 1;  
                $this->start_range = 1;  
            }  
            if ($this->end_range > $this->num_pages)  
            {  
                $this->start_range -= $this->end_range - $this->num_pages;  
                $this->end_range = $this->num_pages;  
            } 

            $this->range = range($this->start_range,$this->end_range);  
  
            for ($i = 1; $i <= $this->num_pages; $i++)  
            {  
                if ($this->range[0] > 2 && $i == $this->range[0])
                	$this->return .= " ... ";  
               
                if ($i == $this->current_page)
                {
                    $this->return .= "<span class='current paginate buttons' >$i</span>";
                }
                elseif ($i == 1 || $i == $this->num_pages || in_array($i, $this->range))  
                {
//                    $url = this_url_with_query(array('page' => $i, 'ipp' => $this->items_per_page));
                    $url = this_url_with_query(array('page' => $i));

        			$this->return .= anchor($url, "$i", "title='View page $i of $this->num_pages' class='paginate buttons' data-page='$i'");
                }  

                if ($this->range[$this->mid_range-1] < $this->num_pages-1 
                	&& $i == $this->range[$this->mid_range-1]) 
                {
					$this->return .= " ... ";
                }
            }  
        }  
        // If <= 10 pages
        else  
        {  
            for ($i=1; $i<=$this->num_pages; $i++)  
            {  
            	if ($i == $this->current_page)
            	{
	            	$this->return .= "<span class='current paginate buttons' >$i</span>";
            	}
            	else
            	{
//                    $url = this_url_with_query(array('page' => $i, 'ipp' => $this->items_per_page));
                    $url = this_url_with_query(array('page' => $i));
	            	$this->return .= anchor($url, "$i", "class='paginate buttons' title='View page $i of $this->num_pages' data-page='$i'");
            	}
            }  
        }

        if ($this->current_page != $this->num_pages 
            && $this->items_total >= 10)
        {
//                $url = this_url_with_query(array('page' => $next_page, 'ipp' => $this->items_per_page));
            $url = this_url_with_query(array('page' => $next_page));
            $this->return .= anchor($url, "Next &raquo;", "class='paginate buttons' data-page='$next_page'");
        }
        else
        {
            $this->return .= "<span class='inactive buttons paginate'>Next &raquo;</span>";
        }

        $this->low = ($this->current_page - 1) * $this->items_per_page;
		$this->high = ($this->current_page * $this->items_per_page)-1;  
	}

	function display_items_per_page()  
    {  
        $items = '';  
        $ipp_array = array(10,25,50,100,'All');  

        foreach ($ipp_array as $ipp_opt)
        {
            if ($ipp_opt == $this->items_per_page)
            {
                $items .= "<option selected value='$ipp_opt'>$ipp_opt</option>";
            }
            else
            {
                $items .= "<option value='$ipp_opt'>$ipp_opt</option>";
            }
        }

        return "<span class='paginate buttons'>Items per page:</span>
                <select class='paginate buttons'>$items</select>";  
    }
  
    function display_jump_menu()  
    {  
        for ($i = 1; $i <= $this->num_pages; $i++)  
        {  
            if ($i == $this->current_page)
            {
                $option .= "<option value='$i' selected>$i</option>";
            }
            else
            {
                $option .= "<option value='$i'>$i</option>";
            }  
        }  
        return "<span class='paginate buttons'>Page:</span>
                <select class='paginate buttons'>$option</select>";  
    }  
  
    function display_pages()  
    {  
        return $this->return;  
    }  
}

/* End of file Paginator.php */
/* Location: ./application/libraries/Paginator.php */