<?php

class ArticlesAction extends CommonAction {
	private $_uid = '';
	
	public function _initialize(){
		$this->_uid = session('uid');
		$data = array(
			''	
		);
		//if(!$this->_uid) $this->ajaxReturn(404,"",1);
		//B('Authenticate', $action);
	}
    public function index(){
		$Articles = M('Articles');
		$SearchTrack = M('SearchTrack');
		import("ORG.Util.Page");
		
		$uid = $this->_uid;
		$keywords = trim($this->_param('keywords'));
		echo $keywords;
		if($keywords){
			$where = "status <> 0 AND title LIKE '%$keywords%'";
			//记录搜索历史	    
			$searchData = array(
				'owner_uid' => $uid,
				'search_keywords' => $keywords,
				'create_time' => datetime()
			);
			$SearchTrack->add($searchData);
		
		}else{
			$where = "status <> 0";
		}
		$relateWhere = "status <> 0";
		$count = $Articles->where($where)->count();
		$Page = new Page($count,20);
		$Page->setConfig('theme',"%upPage% %downPage%  %prePage% %linkPage% %nextPage% ");
		$show = $Page->show();
		$data = $Articles->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		
		foreach($data as $key=>$value){
			//$data[$key]['tags'] = $this->get_tags_arr(strip_tags($value['article_content']));
		}
		//相关推荐
		$relate = $Articles->where($relateWhere)->order('rand()')->limit(5)->select();
		//dump($relate);
		if(empty($data)) $data = $Articles->where($relateWhere)->order('rand()')->limit(5)->select();
		//dump($searchResults);
		//echo $SearchTrack->getLastSql();
		$this->getUserLocation();
		$this->assign('relate',$relate);
		$this->assign('list',$data);
		$this->assign('keywords',$keywords);
		$this->assign('page',$show);
		$this->display();
    }
	public function search(){
		$this->display();
	}
		//发布文章
	public function publish(){
		if($_POST['position_title'] == ''){
			$this->display();
		}else{
			$Position = M('Position');	
			$post = $this->_post();
			$uid = $this->_uid;
			$Position->create();
			$Position->uid = $uid;
			$Position->article_status = 'draft';
			$Position->create_time = datetime();
			$v = $Position->add();
			if($v){
				$this->ajaxReturn(1,"发布成功",1);
			}else{
				$this->ajaxReturn(0,"发布失败",0);
			}

		
			
		}	
	}
	//收藏
	public function favorites(){
		$get = $this->_get();
		$this->ajaxReturn(1,"已收藏",1);
	
	}
	
	//修改密码
	public function resetPassword(){
		$get = $this->_get();
		$uid = session('uid');
		if(!$uid) $this->redirect('Public:login');
		$Account = M('Account');
		if($get['password']==''){
			$this->error("请输入密码");
		}else if($get['password'] != $get['repassword']){
			$this->error("两次输入的密码不对!");			
		}else{
			
			$data = array(
					'uid'      => $uid,
					'password' => $get['password']
			);
			$Account->save($data);
		}
	}
	//文章详情
	public function detail(){
		$get = $this->_get();
		$Articles = M('Articles');
		if($get['id']==''){
			echo '404';
		}else{
			
			$data = array(
					'article_id' => trim($get['id'])
			);
		
			$v = $Articles->where($data)->find();
			//增加阅读次数
			$insert = array('view_times'=>$v['view_times']+1);
			$Articles->where($data)->save($insert);
			//dump($v);
			if($v){
				$this->assign('list',$v);
			}else{
				$this->assign('list',0);
			}
			
					
		}
		$id = $data['article_id'];
		//左右箭头跳转
		$pre = $Articles->field('article_id')->where("article_id < $id")->order('article_id desc')->find();
		$next = $Articles->field('article_id')->where("article_id > $id")->order('article_id asc')->find();
		$this->assign('pre',U('Articles/detail?id='.$pre['article_id']));
		$this->assign('next',U('Articles/detail?id='.$next['article_id']));
		//相关推荐
		$relate = $Articles->where("status <> 0")->order('rand()')->limit(5)->select();
		$this->assign('relate',$relate);
		if($v) $this->display();
		else $this->display('404');
		
	
	}
	//审核修改文章
	public function check(){
		$get = $this->_get();
		$Articles = M('Articles');
		if($get['id']==''){
			echo '404';
		}else{
			$id = intval($get['id']);
			$result = $Articles->where("article_id = $id")->find();
			$pre = $Articles->field('article_id')->where("article_id < $id")->order('article_id desc')->find();
			$next = $Articles->field('article_id')->where("article_id > $id")->order('article_id asc')->find();
			$this->assign('pre',U('Articles/check?id='.$pre['article_id']));
			$this->assign('next',U('Articles/check?id='.$next['article_id']));
			$this->assign('list',$result);
		}
		//相关推荐
		$relate = $Articles->where("status <> 0")->order('rand()')->limit(5)->select();
		$this->assign('relate',$relate);
		$this->display();

	}
	public function checkUpdate(){
		$Articles = M('Articles');
		$param = $this->_param();
		$article_id = intval($param['article_id']);
		$data = array(
			'article_content' => $param['article_content'],
			'update_time' => datetime()
		);
		//更新文章内容
		if($article_id && trim($param['article_content'])){
			$v = $Articles->where("article_id = $article_id")->save($data);
		}else{
		
		}
		if($v){
			$this->ajaxReturn(1,"修改成功",1);
			//$id = $param['article_id']+1;
			//$this->redirect('Index/check', array('id' => $id), 1, '更新成功页面跳转中...');
		}else{
			$this->ajaxReturn(0,"修改失败",0);
			//$id = $param['article_id'];		
			//$this->redirect('Index/check', array('id' => $id), 1, '更新失败页面跳转中...');
		}
	}
	public function checkErrorMark(){
		$Articles = M('Articles');
		$param = $this->_param();
		$article_id = intval($param['article_id']);
		$data = array(
			'article_status' => 'error',
			'update_time' => datetime()
		);
		//更新文章内容
		if($article_id){
			$v = $Articles->where("article_id = $article_id")->save($data);
		}
		if($v){
			$this->ajaxReturn(1,"标记成功",1);			
		}else{
			$this->ajaxReturn(0,"标记失败",0);
		}
	}
	//有错误的文章
	public function errorList(){
		$Articles = M('Articles');
		$ArticleError = M('ArticleError');
		
		
		$v = $Articles->where("article_status = 'error'")->select();
		dump($v);
		foreach($v as $o){
			//删除错误文章
			/*
			$aid = intval($o['article_id']);
			if($aid > 0){
				echo $Articles->where("article_id = $aid")->delete();
			}
			*/
			/*
			$data = array(
				'title'	=> $o['title'],
				'from_url'=> $o['url'],
				'create_time' => datetime()
			);
			if(!$ArticleError->add($data)) echo "error";
			*/
		}
	
	}
	//去掉特定字符  飞华
	public function checkArticleStr(){
		return;
		$Articles = M('Articles');
		$pregStr = 'BAIDU_CLB_fillSlot("379517");'; 
		//$pregStr2 = '更多内容请关注飞华健康网两性频道：http://sex.fh21.com.cn';
		$pregStr2 = '以上是对性爱技巧的详细介绍，然望能够帮助您，如果您还有其它更多问题，那么您可以点击飞华两性健康频道：http://sex.fh21.com.cn/sh/';//性爱:http://sex.fh21.com.cn/  
		
		$pregStr3 = '如有更多问题，请点击飞华两性健康网：http://sex.fh21.com.cn/sh/';
		$pregStr4 = '以上是对性爱技巧的详细介绍，希望能够帮助您，如果您还有其它更多问题，那么您可以点击飞华两性健康频道：http://sex.fh21.com.cn/sh/';
		$pregStr5 = '更多内容点击飞华健康两性专题：http://sex.fh21.com.cn/sh/';
		$pregStr6 = '以上是对性爱技巧的详细介绍，希望能够帮助您，如是您还有其它更多问题，那么您可以点击飞华两性健康网：http://sex.fh21.com.cn/sh/';
		$pregStr7 = '如有更多内容请点击飞华两性健康频道：http://sex.fh21.com.cn/sl/';
		$pregStr8 = '更多内容请点击飞华健康网：http://sex.fh21.com.cn/sh/';
		$pregStr9 = '如有更多内容请点击飞华两性健康频道：';
		$pregStr10 = '有您还想了解更多有关两性的知识，那么您可以点击飞华两性健康频道：http://sex.fh21.com.cn/sh/';
		$pregStr11 = '以上是对性爱技巧的详细介绍，如有其它更多问题，那么您可以点击飞华两性健康频道：http://sex.fh21.com.cn/qw/';
		$pregStr12 = '更多内容点击飞华健康两性专题：http://www.fh21.com.cn/sh';
		$pregStr13 = '更多内容点击飞华健康两性专题：http://sex.fh21.com.cn/sl/';
		$pregStr14 = '以上是对性爱技巧的详细介绍，希望能够帮助您，如有更多问题，那么您可以点击飞华两性健康频道：http://sex.fh21.com.cn/sh/';
		$pregStr15 = '以上是对性爱技巧的详细介绍，希望能够帮助您，如有更多问题请点击飞华两性健康频道：http://sex.fh21.com.cn/sh/';
		$pregStr16 = '以上是对于性爱技巧的详细介绍：希望能够帮助您，如有其它更多问题，那么您可以点击飞华两性健康频道：http://sex.fh21.com.cn/sh/';
		
		$pregStr17 = '以上是性爱技巧的详细介绍，如有更多问题请点击飞华两性健康频道：http://sex.fh21.com.cn/sh/';
		$pregStr18 = '性爱技巧 http://sex.fh21.com.cn/sh/';
		$pregStr19 = '如有更多问题请点击飞华两性健康频道：http://sex.fh21.com.cn/sh/';
		$pregStr20 = '更多内容点击飞华健康两性专题：http://www.fh21.com.cn/';
		$pregStr21 = '更多内容点击飞华健康两性专题：http://sex.fh21.com.cn/';
		$pregStr22 = '如还有其它问题请点击飞华两性健康频道：http://sex.fh21.com.cn/sh/';
		//274;
		$article_id = 13454;//3454
		$results = $Articles->where("article_id > $article_id")->order('article_id ASC')->limit(10)->select();
		foreach($results as $key => $v){
			//echo '<xmp>'.$v['match_html']."</xmp>";
			$match_html = str_replace($pregStr,'',$v['match_html']);
			$match_html = str_replace($v['title'],'',$match_html);//去掉文章中插入的标题
			$match_html = str_replace($pregStr2,'',$match_html);
			$match_html = str_replace($pregStr3,'',$match_html);
			$match_html = str_replace($pregStr4,'',$match_html);
			$match_html = str_replace($pregStr5,'',$match_html);
			$match_html = str_replace($pregStr6,'',$match_html);
			$match_html = str_replace($pregStr7,'',$match_html);
			$match_html = str_replace($pregStr8,'',$match_html);
			$match_html = str_replace($pregStr9,'',$match_html);
			$match_html = str_replace($pregStr10,'',$match_html);
			$match_html = str_replace($pregStr11,'',$match_html);
			$match_html = str_replace($pregStr12,'',$match_html);
			$match_html = str_replace($pregStr13,'',$match_html);
			$match_html = str_replace($pregStr14,'',$match_html);
			$match_html = str_replace($pregStr15,'',$match_html);
			$match_html = str_replace($pregStr16,'',$match_html);
			$match_html = str_replace($pregStr17,'',$match_html);
			$match_html = str_replace($pregStr18,'',$match_html);
			$match_html = str_replace($pregStr19,'',$match_html);
			$match_html = str_replace($pregStr20,'',$match_html);
			$match_html = str_replace($pregStr21,'',$match_html);
			$match_html = str_replace($pregStr22,'',$match_html);
			$pregArray = array(
				'更多性爱技巧内容点击飞华健康两性专题： http://sex.fh21.com.cn/sh/',
				'如果大家对其它性爱技巧感兴趣，可点击http://sex.fh21.com.cn/sh/',
				'如果大家对其它性爱技巧还感兴趣，可点击http://sex.fh21.com.cn/sh/',
				'如果大家对其它性爱技巧还有兴趣，可点击http://sex.fh21.com.cn/sh/'
			);
			foreach($pregArray as $py){
				if(trim($py))
				$match_html = str_replace($py,'',$match_html);
				//echo $match_html;
			}

			$str = $match_html;
			
			//转换,保留<p>
			$str = str_replace('<p>','[[[',$str);
			$str = str_replace('</p>',']]]',$str);
			
			
			
			$str = strip_tags($str);
			$str = str_replace('&nbsp;&nbsp;&nbsp; ','</p><p>　　',$str);
			$str = str_replace('[[[','<p>',$str);
			$str = str_replace(']]]','</p>',$str);
			echo $str;
			$data = array(
				'article_content' => $str,
				'update_time' => datetime()
			);
			//匹配内容为空时不能提交
			if(trim($str) && $v['article_id']){
				$o = $Articles->where("article_id = {$v['article_id']}")->save($data);
			}
			echo $v['article_id'].'<br>';
		}
		//dump($results);
	}
	//去掉特定字符 康路
	public function checkArticleStrKanglu(){
		return;
		$Articles = M('Articles');
		
		$article_id = 13455;//1074
		$results = $Articles->where("article_id > $article_id")->order('article_id ASC')->limit(100)->select();
		foreach($results as $key => $v){
			//echo '<xmp>'.$v['match_html']."</xmp>";
			$match_html = $v['match_html'];
			$pregArray = array(
				'<p>
	&nbsp;</p>'
			);
			foreach($pregArray as $py){
				if(trim($py))
				$match_html = str_replace($py,'',$match_html);
				//echo $match_html;
			}

			$str = $match_html;
			
			//转换,保留<p>
			$str = str_replace('<p>','[[[',$str);
			$str = str_replace('</p>',']]]',$str);
			
			
			
			//$str = strip_tags($str);
			$str = str_replace('[[[','<p>',$str);
			$str = str_replace(']]]','</p>',$str);
			echo $str;
			$data = array(
				'article_content' => $str,
				'update_time' => datetime()
			);
			//匹配内容为空时不能提交
			if(trim($str) && $v['article_id']){
				$o = $Articles->where("article_id = {$v['article_id']}")->save($data);
			}
			echo $v['article_id'].'<br>';
		}
		//dump($results);
	}
	//去掉文章标题中的特定字符
	public function checkArticleTitleStr(){
		return;
		$Articles = M('Articles');
		$pregStr = '性爱技巧：'; 
		$pregStr1 = '两性生活：'; 
		
		
		//274;
		$article_id = 1020;//1074
		$results = $Articles->where("article_id > $article_id")->order('article_id ASC')->limit(40)->select();
		foreach($results as $key => $v){
			$str = str_replace($pregStr1,'',$v['title']);
			//$str = str_replace($pregStr1,'',$str);
			echo $str;
			$data = array(
				'title' => $str,
				'update_time' => datetime()
			);
			//匹配内容为空时不能提交
			if(trim($str) && $v['article_id']){
				$o = $Articles->where("article_id = {$v['article_id']}")->save($data);
				if($o)
				echo $v['article_id'].'<br>';
			}
			
		}
		//dump($results);
	}
	//清洗数据
	public function clear(){
		$Articles = M('Articles');
		
		$c = $Articles->where("article_id  = 105")->find();//select();
		echo '<xmp>'.$c['match_html'].'</xmp>';//原型输出
		
		$str = $c['match_html']; 
		//转换,保留<p>
		$str = str_replace('<p>','[[[',$str);
		$str = str_replace('</p>',']]]',$str);
		
		
		
		$str = strip_tags($str);
		$str = str_replace('&nbsp;&nbsp;&nbsp;','</p><p>  ',$str);
		$str = str_replace('[[[','<p>',$str);
		$str = str_replace(']]]','</p>',$str);
		echo '<xmp>'.$str.'</xmp>';//原型输出
	//$str = str_replace('<div class="detail03"></div>','',$v['match_html']);
		



}
	////////////////////////////////////////////////////////////////////////////////////////////////////
public function test1(){
	
	dump($this->get_tags_arr("在古典文献中记载的性爱姿势有81种之多，有的简单易行，有的刺激感强、对身体素质要求高。其实每一种性交姿势都有它的好处，但是不同人有不同需求，哪种对你来说有用、好用才是可取的。某性研究中心总结了6种经久不衰、大受女性青睐的基本性爱姿势，供大家参考."));
}
function get_tags_arr($title){
	import("@.ORG.pscws4");
	$pscws = new PSCWS4();
	$pscws->set_dict(LIB_PATH.'ORG/scws/dict.utf8.xdb');
	$pscws->set_rule(LIB_PATH.'ORG/scws/rules.utf8.ini');
	$pscws->set_ignore(true);
	$pscws->send_text($title);
	$words = $pscws->get_tops(3);
	$tags = array();
	foreach ($words as $val) {
		$tags[] = $val['word'];
	}
	$pscws->close();
	return $tags;
}
   public function test(){
   //导入类库
      import("@.ORG.SplitWord");
      //记录下时间，调试下花了多少时间
       G('run');
      $str = "在古典文献中记载的性爱姿势有81种之多，有的简单易行，有的刺激感强、对身体素质要求高。其实每一种性交姿势都有它的好处，但是不同人有不同需求，哪种对你来说有用、好用才是可取的。某性研究中心总结了6种经久不衰、大受女性青睐的基本性爱姿势，供大家参考。";
      //丫的，开始分词啦
      $sp = new SplitWord();
      echo $sp->SplitRMM($str) . "<hr />";
       //析放资源
       $sp->Clear();
      //打印耗时
     echo '分词完成，耗时：'.G('run','end').'s';
   }


}