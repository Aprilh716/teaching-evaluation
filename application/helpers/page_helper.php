<?php
if ( ! function_exists('getPageHtml'))
{
    /**
     * @param style
                'slash' - /app/{start}
                'normal' - /app?start={start}
     */
    function getPageHtml($start, $num, $total, $app, $style = 'slash', $showFirstLast = false)
	{
		$pagehtml = '';

		$pagenum = 5;
		$curpage = floor($start / $num) + 1;
		$totalpage = ceil($total / $num);
		$minpage = max($curpage - round($pagenum / 2) + 1, 1);
		$maxpage = min($minpage + $pagenum - 1, $totalpage);
		$minpage = max($maxpage - $pagenum + 1, 1);

        if($style == 'slash') {
            $app = $app . '/';
        } else {
            $app = strpos($app, '?') ? ($app . '&start=') : ($app . '?start=');
        }
        
		if ($totalpage <= 1) {
			return '';
		}

		if ($curpage > 1) {
			if ($totalpage > 5 && $showFirstLast) {
				$pagehtml .= '<li><a href="' . $app . '0">第一页</a></li>';
			}
			$pagehtml .= '<li><a href="' . $app . (($curpage-2) * $num) . '">上一页</a></li>';
		} else {
            $pagehtml .= '<li class="disabled"><a href="javascript:void(0);">上一页</a></li>';
        }

		for ($i = $minpage; $i <= $maxpage; $i++) {
			if ($i != $curpage) {
				$pagehtml .= '<li><a href="' . $app . (($i-1) * $num) . '">' . $i . '</a></li>';
			} else {
				$pagehtml .= '<li class="active"><a href="javascript:void(0);">' . $i . '</a></li>';
			}
		}

		if ($curpage < $totalpage) {
			$pagehtml .= '<li><a href="' . $app . ($curpage * $num) . '">下一页</a></li>';
			if ($totalpage > 5 && $showFirstLast) {
                $pagehtml .= '<li><a href="' . $app . (($totalpage-1) * $num) . '">最后一页</a></li>';
			}
		} else {
            $pagehtml .= '<li class="disabled"><a href="javascript:void(0);">下一页</a></li>';
        }

		return '<ul class="pagination">' . $pagehtml . '</ul>';
	}
}