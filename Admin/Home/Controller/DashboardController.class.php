<?php
namespace Home\Controller;
use Common\Controller\BackendController;

class DashboardController extends BackendController {

	/**
	 * 后台首页
	 */
	public function index() {
		$sta_db = M('statistic');
		$sta_rs = $sta_db->order('date')->select();

		$tot_view = 0;
		$mon_view = 0;
		$mon_stat = array();
		$yes_view = 0;
		$today_view = 0;

		foreach ($sta_rs as $v) {
			$tot_view += $v['view_num'];
			if ($v['date'] >= date('Y-m-d', strtotime('-30 day'))) {
				$mon_stat[] = array(
					'date' => $v['date'],
					'view' => $v['view_num']
				);
				$mon_view += $v['view_num'];

				if ($v['date'] == date('Y-m-d', strtotime('-1 day')))
					$yes_view = $v['view_num'];

				if ($v['date'] == date('Y-m-d'))
					$today_view = $v['view_num'];
			}
		}

		$this->assign('tot_view', $tot_view);
		$this->assign('mon_view', $mon_view);
		$this->assign('yes_view', $yes_view);
		$this->assign('today_view', $today_view);
		$this->assign('mon_stat', $mon_stat);
		$this->display();
	}

}