<?php
function smarty_compiler_substring($params, $compiler)
{
	$nofitler = intval($params['nofilter']);
	if ($nofitler)
	{
		return '<?php echo substring(strval('.$params["str"].'), intval('.$params["len"].'), strval('.$params["ext"].'));?>';
	}
	else
	{
		return '<?php echo htmlspecialchars(substring(strval('.$params["str"].'), intval('.$params["len"].'), strval('.$params["ext"].')));?>';
	}
}
?>