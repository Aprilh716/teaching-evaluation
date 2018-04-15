<?php
if (!function_exists('list2map'))
{
    function list2map($arr, $kField = null, $vField = null)
    {
        if (!is_array($arr) || empty($arr)) {
            return array();
        }

        $ret = array();

        if($kField === null) {
            foreach($arr as $v) {
                $ret[$v] = 1;
            }
            return $ret;
        }

        if($vField === null) {
            foreach ($arr as $v) {
                $ret[$v[$kField]] = $v;
            }
        }
        else {
            foreach ($arr as $v) {
                $ret[$v[$kField]] = $v[$vField];
            }
        }
        return $ret;
    }
}

if (!function_exists('get_fields'))
{
	function get_fields($objs, $key)
	{
        if(empty($objs)) {
            return array();
        }

		$ids = array();
		if (is_array($objs)) {
			foreach($objs as $obj) {
				if (is_array($obj)) {
					$ids[] = $obj[$key];
				} else if (is_object($obj)) {
					$ids[] = $obj->$key;
				}
				else {
					$ids[] = $obj;
				}
			}
		}
		return $ids;
	}
}

if (!function_exists('array_assoc_slice'))
{
    function array_assoc_slice($arr, $start, $count)
    {
        $keys = array_slice(array_keys($arr), $start, $count);
        $result = array();
        foreach($keys as $key) {
            $result[$key] = $arr[$key];
        }
        return $result;
    }
}

if (!function_exists('sort_by_field'))
{
    function sort_by_field($arr, $field, $sort = SORT_DESC) {
        $keys = array();
        foreach($arr as $item) {
            $keys[] = $item[$field];
        }
        array_multisort($keys, $sort, $arr);
        return $arr;
    }
}

if (!function_exists('get_key_by_weight')) {
    function get_key_by_weight($arr, $field = 'weight')
    {
        if (!$arr) {
            return false;
        }
        $weight = 0;
        $result = array();
        foreach ($arr as $one) {
            $oneWeight = (int)$one[$field] ? $one[$field] : 1;
            $weight += $oneWeight;
            for ($i = 0; $i < $oneWeight; $i ++) {
                $result[] = $one;
            }
        }
        return $result[rand(0, $weight-1)];
    }
}