<?php

namespace MyShopKitPopupSmartBarSlideIn\Insight\Shared\Yesterday;


use MyShopKitPopupSmartBarSlideIn\Insight\Shared\Query\QueryBuilder;
use MyShopKitPopupSmartBarSlideIn\Insight\Shared\TraitJoinPost;

class YesterdayQuery extends QueryBuilder {
	use TraitJoinPost;

	public function select(): YesterdayQuery {
		$this->setWhat();
		$this->setWhere();
		$this->setJoin();

		return $this;
	}

	public function setWhat(): QueryBuilder {
		$this->aSelectWhat[] = "DATE(createdDate) as date";

		return $this;
	}

	public function setWhere(): QueryBuilder {
		$this->aWhere[] = "(DATE(createdDate) = DATE(CURDATE() - INTERVAL 1 DAY))";

		return $this;
	}
}
