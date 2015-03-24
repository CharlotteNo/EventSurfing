<?php
class View
{
	private $page;
	private $variables;
	private $loops;
	private $clear;
	
	function __construct($file) {
		if(empty($file) || !file_exists($file) || !is_readable($file))
		{
			exit();
		}
		$handle = fopen($file,'rb');
		$this->page = fread($handle,filesize($file));
		fclose($handle);
		$this->variables = array();
		$this->loops = array();
		$this->clear = false;
    }
	
	public function set($array = array())
	{
		if(empty($array)) exit();
		$this->variables = $array;
	}
	
	public function setLoop($array = array())
	{
		if(empty($array)) exit();
		$this->loops = $array;
	}
	
	public function display()
	{
		if (!$this->clear) $this->parse();
		echo $this->page;
	}
	
	public function clear()
	{
		$this->page = preg_replace('`{{.*}}`','',$this->page);
		$this->clear = true;
	}
	
	private function parse()
	{
		foreach($this->variables as $name => $value)
		{
			$this->page = preg_replace('`{{'.$name.'}}`',$value,$this->page);
		}
		foreach($this->loops as $name => $value)
		{
			$nb = count($value);
			$block = '';
			for ($i = 0; $i < $nb; $i++) {
				$tab_page = explode("\n", $this->page); //récuppération ligne par ligne
				for ($k = 0, $kmax = count($tab_page); $k < $kmax; $k++) $tab_page[$k] = trim($tab_page[$k]);
				$startTag = (array_search('{{BEGIN.'.$name.'}}', $tab_page)) + 1;
				$endTag = (array_search('{{END.'.$name.'}}', $tab_page)) - 1;
				$lengthTag = ($endTag - $startTag) + 1;
				$blockTag = array_slice($tab_page, $startTag, $lengthTag);
				$blockTag = implode("\n", $blockTag);
				foreach($value[$i] as $constant => $data) {
					$data = (file_exists($data)) ? $this->includeFile($data) : $data;;
					$blockTag = preg_replace('`{{'.$constant.'.'.$name.'}}`', $data, $blockTag);
				}
				$block = ($block == '') ? $blockTag : $block."\n".$blockTag;
			}
			$block = explode ("\n", $block);
			$firstPart = array_slice($tab_page, 0, $startTag - 1);
			$secondPart = array_slice($tab_page, $startTag + $lengthTag + 1);
			$this->page = array_merge($firstPart, $block, $secondPart);
			for ($i = 0, $imax = count($this->page); $i < $imax; $i++) $this->page[$i] = html_entity_decode($this->page[$i]);
			$this->page = implode("\n", $this->page);
		}
	}
}
?>