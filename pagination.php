<?php
/**
 * @package		pagination
 * @copyright	Copyright (C) 2012 Open Source Matters. All rights reserved.
 * pagination! is free software.
 * @author      Adesipe Oluwatosin
 * @email       adesipetosin@shypes.com (08037268261) 
 */
class pagination
{		
	var $start 				= 0; // current start pointer for database
	var $limit 				= 20; //current limit pointer for database
	var $total 				= 0; // estimated to total no to items in database
	var $type 				= "link"; // set to script if you want to parse a javascript navigation
	var $callscript			= "move"; // store javascript function to be called || default move		
	var $param 				= array(); //store javascript param	to be parsed
	var $num_links			= 2; // Number of 'digit' links to show before/after the currently viewed page
	var $pageLimit 			= 5; // store no of single page to display
	var $base_url 			= '#'; // store current page link
	var $next_link			= "&raquo;";
	var $prev_link			= "&laquo;";
	var $end_link			= "&raquo; &raquo;";
	var $begin_link			= "&laquo; &laquo;";		
	var $titlepage 			= false; // set to true if page title needed
	var $singlepage 		= true; // set to false if only navigation button needed
	var $forcenav 			= false; // set to true to show previous and next button even if button dont exist
	var $endpointnavigation = true; // set to false if only previous and next button wanted
	var $usepagelimit 		= true; // set to true to use page limit
	var $linkid 			= "gli";	// store no of single page to display
	var $start_url 			= "start"; // default is start, change to your pointer url name
	var $limit_url 			= "limit"; // default limit, change to your limit url name
	var $parselimit 		= true;// set to false if you dont want limit to show on url | parse limit to url	
	var $current_class		= "active disabled"; // store class name for current page
	var $anchor_class		= ""; // class for individual page displayed e.g class='pages'
	var $container_class	= "pagination"; // class name for page container
	var $pager				= "";// store pages after paging is done
	private $links 			= ''; // store single page link navigation
	private $endpage 		= 0;
	private $rawlink 		= array(); // store raw link pagination
	private $rawpage 		= array(); // store raw link pagination
	private $beginnav = "#", $begin_script = null	,$prenav = "#",$pre_script = null,$nextnav = "#",$next_script = null,$endnav = "#",$end_script = null; // cache for navigation buttons
	
	function __construct($options = array())
	{

		$this->setOptions($options);
	}	

	function setOptions($options) {

		if (isset ($options['total'])) {
			$this->total = (int) $options['total'];
		}
		if (isset ($options['start'])) {
			$this->start = (int) max($options['start'], 0);
		}
		if (isset ($options['limit'])) {
			$this->limit = (int) max($options['limit'], 0);
		}
		if (isset ($options['type'])) {
			$this->type = $options['type'];
		}
		if (isset ($options['callscript'])) {
			$this->callscript = !empty($options['callscript']) ? $options['callscript'] : $this->callscript;
		}
		if (isset ($options['param'])) {
			$this->param = is_array($options['param']) ? $options['param'] : array();
		}
		if (isset ($options['link']))
		{
			if(strstr($options['link'], "?") == false) 
				$this->base_url	=  $options['link'].'?';
			else 						
				$this->base_url	=  $options['link'].'&';
		}

		if (isset ($options['endpointnavigation'])) {
			$this->endpointnavigation = empty($options['endpointnavigation']) ? false : true;
		}

		if (isset ($options['singlepage'])) {
			$this->singlepage = empty($options['singlepage']) ? false : true;
		}

		if (isset ($options['forcenav'])) {
			$this->forcenav = empty($options['forcenav']) ? false : true;
		}

		if (isset ($options['next'])) {
			$this->next_link = $options['next'];
		}
		if (isset ($options['prev'])) {
			$this->prev_link = $options['prev'];
		}

		if (isset ($options['first'])) {
			$this->begin_link = $options['first'];
		}
		if (isset ($options['last'])) {
			$this->end_link = $options['last'];
		}

		$this->inti();
		return true;
	}
	function inti(){
		if ($this->limit > $this->total) 	$this->start = 0;
		if (!$this->limit || $this->limit == "all"){
			$this->limit = $this->total;
			$this->start = 0;
		}
		if ($this->start > $this->total) 	$this->start -= $this->start % $this->limit;
	}
	public function paging ()
	{					
		if($this->total > 0){// if our expected out put if greater dan zero			
			$this->nav_button(); // fix navigation button for pre, next, begin and end
			if($this->singlepage == true){ // show single item pages
				$page = ceil(($this->start) / $this->limit) + 1; // find current page				
				$this->endpage = $endpage = ceil($this->total / $this->limit);	// initialise start end page
				$startpage = 0; // set start pointer to 0
				if($this->usepagelimit == true)	{ // calculate display limit	
					$startpage = $page - ($this->num_links + 1); // fix page to show before current page
					if($startpage < 0)$startpage = 0; // set pointer back to zero if we hit a negative val
					elseif(( $startpage + $this->pageLimit) > $endpage){ // check if we are short of page to show in the page limit set
						$extra = ( $startpage + $this->pageLimit) - $endpage; // find extra pages left to fit page limit
						$startpage -= $extra; // remove extra pages start index
						if($startpage < 0)$startpage = 0; // set pointer back to zero if we hit a negative val just to be safe we dont have a negative value
					}					
					$endpage = (int) min($endpage,$this->pageLimit + $startpage); // fix end page since page limit is set
				}
				$move = $this->limit * $startpage;	// first pointer for database on current page
				if($endpage > 0 ){				
					for ($i= $startpage; $i < $endpage; $i++){					
						$currentpage = $i+1; // locate the cuurent page showing							
						if($this->titlepage == true) $title = ' title="page '.$currentpage.'" ';
						else $title = ''; 						
						if($page == $currentpage){ // handle current page we are in		
							$this->links .= '<li class="'.$this->current_class.'" id="'.$this->linkid.$currentpage.'" ><a href="#" '.$this->anchor_class.' '.$title.'  data-target-page="'.$currentpage.'"   >'.$currentpage.'</a></li>'; 
							$this->rawpage[$currentpage] = '<a href="#" '.$title.' '.$this->anchor_class.'  data-target-page="'.$currentpage.'"  >'.$currentpage.'</a>';
						}else{	// link for other pages displayed	
							$pagenav = $this->add_nav($move,$currentpage);	 // important to move to selected page							
							$onclick = $this->add_script($move);  // add script to link if set | parse database pointer $move								
							$this->links .= '<li  id="'.$this->linkid.$currentpage.'" ><a href="'.$pagenav.'" '.$this->anchor_class.'  data-target-page="'.$currentpage.'" '.$title.'  '.$onclick.'  >'.$currentpage.'</a></li>'; 
							$this->rawpage[$currentpage] = '<a href="'.$pagenav.'" '.$this->anchor_class.'  data-target-page="'.$currentpage.'" '.$title.'  '.$onclick.'  >'.$currentpage.'</a>';
						}$move +=$this->limit; // move the database pointer for current page 
					}	
				}			
			}
		}		
		$this->pager = '<ul class="'.$this->container_class.'" >';
		if((!empty($this->prenav) && $this->prenav != '#') || $this->forcenav == true){ // show if previous page exist
			if($this->endpointnavigation == true){
				$this->rawlink["begin"] = '<a href="'.$this->beginnav.'" '.$this->anchor_class.' data-target-page="1"  '.$this->begin_script.'  >'.$this->begin_link.'</a>';
				$this->pager .= '<li><a href="'.$this->beginnav.'" '.$this->anchor_class.' data-target-page="1" '.$this->begin_script.' >'.$this->begin_link.'</a></li>';	
			}
			$this->rawlink["previous"] = '<a href="'.$this->prenav.'" '.$this->anchor_class.'  data-target-page="prev" '.$this->pre_script.' >'.$this->prev_link.'</a>';
			$this->pager .= '<li><a href="'.$this->prenav.'"  data-target-page="prev" '.$this->pre_script.' >'.$this->prev_link.'</a></li>';		
		}
		if(!empty($this->links) && $this->singlepage == true){
			$this->rawlink["pages"] = $this->rawpage;
			$this->pager .= $this->links;	
		}
		if((!empty($this->nextnav) && $this->nextnav != '#') || $this->forcenav == true){ // show if next page exist
			$this->rawlink["next"] = '<a href="'.$this->nextnav.'" '.$this->anchor_class.'  data-target-page="next" '.$this->next_script.' >'.$this->next_link.'</a>';
			$this->pager .= '<li><a href="'.$this->nextnav.'"  '.$this->anchor_class.'   data-target-page="next" '.$this->next_script.' >  '.$this->next_link.' </a></li>';	
			if($this->endpointnavigation == true){
				$this->rawlink["end"] = '<a href="'.$this->endnav.'"  '.$this->anchor_class.'  data-target-page="'.$this->endpage.'" '.$this->end_script.' >'.$this->end_link.'</a>';
				$this->pager .= '<li><a href="'.$this->endnav.'"   '.$this->anchor_class.'   data-target-page="'.$this->endpage.'" '.$this->end_script.' >'.$this->end_link.'</a></li>';	
			}
		}$this->pager .='<input type="hidden" name="'.$this->start_url.'" value="'.$this->start.'" />';
		$this->pager .='<div class="clr"></div>';
		$this->pager .='</ul>';		
		return $this->pager;
	}
	private function nav_button(){
		 // previous button
		$pre = $this->start - $this->limit;
		if($pre < 0)	$pre = 0;											
		$this->prenav = $this->add_nav($pre);	 // important to move to selected page
		if($this->start == $pre) $this->prenav = '#';	
		$this->pre_script = $this->add_script($pre,"pre");  // add script to link if set	
		// begin button
		$begin = 0;	
		$this->beginnav = $this->add_nav($begin);	 // important to move to selected page
		$this->begin_script = $this->add_script($begin,"pre");  // add script to link if set	
		// next button
		$next = $this->start + $this->limit; 
		if($next > $this->total) $next = $next - $this->limit;						
		$this->nextnav = $this->add_nav($next);	 // important to move to selected page
		if(($this->total - $this->start) <= $this->limit  ) $this->nextnav = '#';	
		$this->next_script = $this->add_script($next,"next");  // add script to link if set	
		 // end button
		$end = $this->total - $this->limit;
		if($end < 0)	$end = 0;			
		$this->endnav = $this->add_nav($end);	 // important to move to selected page
		$this->end_script = $this->add_script($end,"next");  // add script to link if set	
	}
	private function add_nav($move){
		if($this->type == "script"){ // add script to link	
			$link = 'javascript://';
		}else{
			if($this->base_url == "#") return $this->base_url;
			$link = rtrim($this->base_url);
			$link.="{$this->start_url}={$move}";
			if($this->parselimit == true)	 $link.= "&{$this->limit_url}={$this->limit}";	
		}return $link; 
	}
	private function add_script($page,$type = "")
	{
		$onclick = ''; // initialise no script
		if($this->type == "script"){ // add script to link	
			$n = count($this->param);			
			if($n > 0){				
				$a = 1;	$v = '';
				foreach($this->param as $param){ // load param passed
					$v .= '"'.$param.'"';
					if($a == $n) break;
					$v .= ',';
					$a++;
				}$onclick = " onclick='return {$this->callscript}(this,{$v},{$page},{$this->limit});' "; // add script with params
			}else{
				$onclick = " onclick='return {$this->callscript}(this,{$page},{$this->limit});' "; // add script with no params
			}
			if($type == "next"){
				if(($this->total - $this->start) <= $this->limit  ) $onclick  = '';
			}elseif($type == "pre"){
				$pre = $this->start - $this->limit;
				if($pre < 0)	$pre = 0;					
				if($this->start == $pre) $onclick  = '';
			}
		}return $onclick;	
	}
	public function get_pages()
	{
		return $this->pager;
	}
	public function get_array_pages()
	{
		return $this->rawlink;
	}
}
?>