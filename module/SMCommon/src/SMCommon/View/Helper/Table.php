<?php

namespace SMCommon\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Symfony\Component\Console\Application;
use Doctrine\DBAL\Types\BooleanType;

class Table extends AbstractHelper
{
	/**
	 * An array containing objects. For each object is a row displayed.
	 * @var array 
	 */
	protected $data;
	
	/**
	 * An associative array containg all HTML attributes
	 * @var array 
	 */
	protected $attributes;
	
	/**
	 * A flag if number should be added to each row.
	 * @var boolean 
	 */
	protected $showNumberColumn = true;
	/**
	 * If you want don't want to start with 1 for test first row.
	 * @var integer
	 */
	protected $numberColumnOffset = 1;
	
	/**
	 * A method name as a string. This method will then be called 
	 * and the result will be set as the row id. Or a closure that returns a string.
	 * The closure receives the current rows object as a argument.
	 * 
	 * @var \Closure|String 
	 */
	protected $rowIdDataMethod = NULL;
	
	/**
	 * An array of arrays. Each array contains a column.
	 * 
	 * EASY:
	 * Just suplly an array with to objects. The first will be the 
	 * headTitle and the second (needs to be a string) is the name
	 * of a method on how to get that data. Example
	 * 
	 * 	array('Benutzernam', 'getUsername')
	 * 
	 * 
	 * ADVANCED:
	 * A column can have the following keys: 
	 * 
	 * 	headTitle : if $showHead is true this value will be shown in this 
	 * 				columns head.
	 * 
	 *  dataMethod: Tells the table where to get the cells data. Either a string
	 *  			containing a method name or a closure. The closure will receive
	 *  			the current rows object as the first argument and the TableViewHelper 
	 *  			as the second argument and should return a string. 
	 * 
	 * @var array
	 */
	protected $columns;
	
	/**
	 * Flag that defines if a head row should be shown.
	 * 
	 * @var boolean
	 */
	protected $showHeadRow = true;
	
	/**
	 * Either an associative array containg the attributes name as the key and the attributes value as the value or
	 * a closure that returns such an array.
	 * 
	 * @var \Closure|array
	 */
	protected $rowAttributes = NULL;
	
	public function __construct()
	{
		$this->attributes 	= array(
			'class' =>'table table-condensed table-bordered table-striped sortable'
		);
		$this->columns 		= array();
	}
	
	/**
	 * @see Application\View\Helper\Table#$data
	 */
	public function setData($data)
	{
		$this->data = $data;
	}
	
	public function getData()
	{
		return $this->data;
	}
	
	/**
	 * @see Application\View\Helper\Table#$attributes
	 */
	public function setAttribute($name, $value)
	{
		$this->attributes[$name] = $value;
	}
	
	public function getAttribute($name)
	{
		if (isset($this->attributes[$name])) {
			return $this->attributes[$name];
		}
		
		return NULL;
	}
	
	public function getAttributes()
	{
		return $this->attributes;
	}
	
	public function setShowNumberColumn($flag)
	{
		$this->showNumberColumn = $flag;
	}
	
	public function getShowNumberColumn()
	{
		return $this->showNumberColumn;
	}
	
	public function setNumberColumnOffset($offset)
	{
		$this->numberColumnOffset = $offset;
	}
	
	public function getNumberColumnOffset()
	{
		return $this->numberColumnOffset;
	}
	
	public function setRowIdDataMethod($rowId)
	{
		$this->rowIdDataMethod = $rowId;
	}
	
	public function getRowIdDataMethod()
	{
		return $this->rowIdDataMethod;
	}
	
	/**
	 * Add a column to the table
	 * 
	 * @param array $column	An array containing 2 keys: 'headTitle' and 'dataMethod'. The data method has to be the name of 
	 * 						method that is implemented by the data object or a closure that returns a string.
	 * 						As an alternative you can just supply this 2 things in a normal array. The first object needs to be
	 * 						the title and the second the method name or the closure.
	 */
	public function addColumn($column)
	{
		$this->columns[] = $column;
	}
	
	public function setRowAttributes($attributes)
	{
		$this->rowAttributes = $attributes;
	}
	
	public function getRowAttributes()
	{
		return $this->rowAttributes;
	}
	
	public function __toString()
	{
		// Render the table
		return $this->render();
	}
	
	/**
	 * Renders the form and returns the finished form.
	 * @return string The rendered HTML table
	 */
	public function render()
	{
		
		$table = "<table";
		
		// Add all attributes
		foreach ($this->attributes as $key => $value) {
			$table .= ' ' . $key . '="' . $value .'"';
		}
		
		$table .= ">"; // Close table
		
		// Add the head
		if ($this->showHeadRow) {
			$table .= "<thead><tr>";
				
			// Show the number header.
			if ($this->showNumberColumn) {
				$table .= "<th>#</th>";
			}
				
			foreach ($this->columns as $column) {
				$columnHeadTitle = isset($column['headTitle']) ? $column['headTitle'] : $column[0];
				$table .= "<th>" . $columnHeadTitle . "</th>";
			}
				
			$table .= "</tr></thead>"; // finish head
		}
		
		// Body
		$table .= "<tbody>";
		
		$row = $this->numberColumnOffset;
		
		if (count($this->data) == 0) {
			// Show a no data entry
			$colspan = count($this->columns) + ($this->showNumberColumn ? 1 : 0);
			$table .= '<tr><td colspan="'.$colspan.'"><p align="center" style="font-weight:bold;">Keine Einträge</p></td></tr>';
		}
		else {
			foreach ($this->data as $dataObject) {
		
				// Row attributes
				$attributes = $this->rowAttributes;
				if (!is_array($attributes)) {
					if (is_callable($this->rowAttributes)) {
						$callable = $this->rowAttributes;
						$attributes = $callable($dataObject);
					}
				}
					
				$attributeString = '';
				if ($attributes) {		// Add attributes if we have some
					foreach ($attributes as $attributeName => $attributeValue) {
						$attributeString .= $attributeName . '="' . $attributeValue . '" ' ;
					}
				}
				
				if ($this->rowIdDataMethod) {
					// method name or closure?
					$id = '';
					if (is_string($this->rowIdDataMethod)) {
						$id = $dataObject->{$this->rowIdDataMethod}(); // Call the row data method
					}
					else {
						$rf = new \ReflectionFunction($this->rowIdDataMethod);
						if ($rf->isClosure()) {
							// we got a closure
							$closure = $this->rowIdDataMethod;
							$id = $closure($dataObject);
						}
					}
					

					
					$table .= '<tr id="'. $id .'" ' . $attributeString . '>'; // Start row with id

				}
				else {
					$table .= "<tr ". $attributeString .">";	// Start row without id
				}
		
				// Number?
				if ($this->showNumberColumn) {
					$table .= "<td>" . $row . "</td>";
				}
					
				foreach ($this->columns as $column) {
					$dataMethod = isset($column['dataMethod']) ? $column['dataMethod'] : $column[1];
					if (is_string($dataMethod)) {
						// Just call the method an insert the return value into the table cell
						$table .= "<td>". $dataObject->$dataMethod() ."</td>";
					}
					else {
						$rf = new \ReflectionFunction($dataMethod);
						if ($rf->isClosure()) {
								
							// Call the closure and get the result
							$value = $dataMethod($dataObject, $this);
								
							$table .= "<td>". $value ."</td>";
						}
					}
				}
		
				$table .= "</tr>";	// End the table row
				$row++;
			}
		}
		
		$table .= "</tbody>";
		
		$table .= "</table>";
		return $table;
	}
	
	/**
	 * Directly get an instance.
	 */
	public function __invoke() 
	{
		// No options given. New strategy. Return a new object
		$newHelper = new Table();
		$newHelper->setView($this->getView());
		return $newHelper;
	}
}
?>