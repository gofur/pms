<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * [HTML5 Form Number Field]
 * @param  string $data  [description]
 * @param  string $value [description]
 * @param  string $extra [description]
 * @return [type]        [description]
 */
if( ! function_exists('form_number')) 
{
	function form_number($data = '', $value = '', $extra = '')
	{
		if ( ! is_array($data)){
			$data = array('name' => $data);
		}
		$data['type'] = 'number';
		return form_input($data, $value, $extra);
	}
}

if ( ! function_exists('form_hidden'))
{
	function form_hidden($name, $value = '', $recursing = FALSE)
	{
		static $form;

		if ($recursing === FALSE)
		{
			$form = "\n";
		}

		if (is_array($name))
		{
			foreach ($name as $key => $val)
			{
				form_hidden($key, $val, TRUE);
			}
			return $form;
		}

		if ( ! is_array($value))
		{
			$form .= '<input type="hidden" name="'.$name.'" id="'.$name.'" value="'.form_prep($value, $name).'" />'."\n";
		}
		else
		{
			foreach ($value as $k => $v)
			{
				$k = (is_int($k)) ? '' : $k; 
				form_hidden($name.'['.$k.']', $v, TRUE);
			}
		}

		return $form;
	}
}