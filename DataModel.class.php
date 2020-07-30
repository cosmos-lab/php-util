<?php

/*
 * Created: July 24, 2020
 * 
 */

class DataModel extends stdClass
{

	public function __construct($o = null)
	{

		if ($o === null) {
			return;
		}

		if (is_string($o)) {
			$o = json_decode(utf8_encode($o));
		} else {
			if (is_object($o) || is_array($o)) {
				foreach ($o as $key => $val) {
					$this->$key = $this->cast($val);
				}
			}
		}
	}

	private function cast($val)
	{

		$obj = @json_decode(@utf8_encode($val));

		if (is_object($obj)) {
			return new DataModel($obj);
		}
		if (is_array($obj)) {
			return $obj;
		}
		if (is_object($val)) {
			return new DataModel($val);
		}
		if (is_array($val)) {
			$o = (isset($val[0]) || !count($val)) ? array() : new DataModel();
			foreach ($val as $k => $v) {
				if (is_numeric($k)) {
					if (is_object($o)) {
						continue;
					}
					$o[] = $this->cast($v);
				} else {
					$o->$k = $this->cast($v);
				}
			}
			return $o;
		}

		return $val;
	}

	public function __toString()
	{
		$output = str_replace("\/", "/", json_encode($this));
		return $output;
	}

	public function DataModel()
	{

		$output = str_replace("\/", "/", json_encode($this));

		if (!isset($_GET['debug'])) {
			header('Content-Type: application/json');
			header('Content-Length:' . mb_strlen($output));
			echo $output;
		}

		exit();
	}

	public function __get($name)
	{
		if (isset($this->$name)) {
			return $this->$name;
		}
		return null;
	}
}

function DataModel($arg = FALSE)
{
	return new DataModel($arg);
}
