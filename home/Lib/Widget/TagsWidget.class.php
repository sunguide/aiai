<?php
//热门搜索
class TagsWidget extends Action {
	
	public function hotTag($param=''){	
		 //热门搜索
		 $SearchTrack = M('SearchTrack');
		 $searchResults = $SearchTrack->alias('A')->field('A.*,count(*) count')->group('A.search_keywords')->order('count DESC')->limit(10)->select();
		 $this->assign('hotSearch',$searchResults);
		 $this->display('Articles/hotTag');
	}
}