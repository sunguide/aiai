<?php
// SMS
class SMSAction extends Action {
    public function index(){
		
		//$this->display("login");
    }
	public function smsSend(){
		$Post = $this->_Post();
		$smsContent = "你知道吗？我中大奖了。";
		$mobileArray = explode(",",'15223169211,15226196261,');
		foreach($mobileArray as $m){
			$send = array(
					'mobile' => $m,
					'sms_content' => $smsContent,
				    'send_way' =>$sendWay,
					'send'
			);
		}
		
		$this->display();
	}
	public function blackList(){
	    $get = $this->_get();
		$BL = M('Blacklist');
		
		$content = $get['sms'];
		$keywords = $BL->where('status = 1')->select();
		$warn = array();
		$i = 0;
		foreach($keywords as $v){
			if(substr_count($content,$v['keyword']) > 0){
				$warn[$i] = $v['keyword'];
			}
		}
		if(empty($warn)){
			$this->ajaxReturn(0,"没有敏感词汇",0);
		}else{
			$this->ajaxReturn($warn,"含有敏感词汇",1);
		}
	}
	//sms category generate

	public function smsCategory(){
	$ST = M('TemplateCategory');
	$data['category_content'] = "";
	$data['parent_id'] = 1;
	$data['create_time'] = date("Y-m-d H:i:s");
	$data['status'] =1;
	$a = array('春节','元宵','元旦','中秋节','清明节','妇女节','情人节','父亲节','母亲节','劳动节','七夕节','教师节','端午节','日常祝福','幽默');
	foreach($a as $v){
		$data['category_content'] = $v;
		//$ST->add($data);
	}
	}
	//导入常用短信
	public function smsTemplateImport(){
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 100000000 ;// 设置附件上传大小
		$upload->allowExts  = array('txt', 'xls');// 设置附件上传类型
		$upload->savePath =  './Uploads/files/';// 设置附件上传目录
		$upload->saveRule = 'time';
		$upload->autoCheck = true;
		if(!$upload->upload()) {// 上传错误提示错误信息
		$this->error($upload->getErrorMsg());
		}else{// 上传成功 获取上传文件信息
		$info =  $upload->getUploadFileInfo();   //$info[0]['savename'];
		}
		$filename = $info[0]['savename'];
//进行处理
		$file = $upload->savePath.$info[0]['savename'];//'words.txt';//10W条记录的TXT源文件
		$lines = file_get_contents($file);
		ini_set('memory_limit', '-1');//不要限制Mem大小，否则会报错
		$line=explode("\n",$lines);
		$all_count = count($line);//记录导入的数据的总号码数据量
		$all_count = $all_count?$all_count:0;echo $all_count;
		//如果文件过大，先对文件进行切割 ，每个文件10万行数据
		$category_id = 16;
		$PublicTemplate = M('PublicTemplate');
		foreach($line as $v){
			$sql="INSERT INTO sms_public_template(sms_content,category_id,create_time,update_time,status) VALUES('$v',$category_id,now(),now(),1)";
			if(trim($v)){
				if(!$PublicTemplate->execute($sql)){
					echo "this have a error!";
				}	
			}
		}
		
	}
	//查看黑字典
	public function blackWords(){
		$Blackwords = M('Blackwords');
		import('ORG.Util.Page');// 导入分页类
		$uid = $this->_uid;
		$where = array(
			'uid'     => $uid,
			'status'  => 0
		);
		$where = 1;
		$count      = $Blackwords->where($where)->count();// 查询满足要求的总记录数
		$Page       = new Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
	
		$list =$Blackwords->where($where)->order('login_time')->limit($Page->firstRow.','.$Page->listRows)->select();
	

		$list = $Model->query($sql);
		//dump($voList);
		/*
		$icount = count($list);
		for($i = 0; $i < $icount ;$i++){
			$list[$i]['region']?1:$list[$i]['region'] = "等待确认";
			$list[$i]['user_remark']?1:$list[$i]['user_remark'] = "无";	
		}
		*/
		//dump($list);
		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$this->display();
	}
	//导入黑字典
	public function blackWordsImport(){
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 100000000 ;// 设置附件上传大小
		$upload->allowExts  = array('txt', 'xls');// 设置附件上传类型
		$upload->savePath =  './Uploads/files/';// 设置附件上传目录
		$upload->saveRule = 'time';
		$upload->autoCheck = true;
		if(!$upload->upload()) {// 上传错误提示错误信息
		$this->error($upload->getErrorMsg());
		}else{// 上传成功 获取上传文件信息
		$info =  $upload->getUploadFileInfo();   //$info[0]['savename'];
		}
		$filename = $info[0]['savename'];
//进行处理
		$file = $upload->savePath.$info[0]['savename'];//'words.txt';//10W条记录的TXT源文件
		$lines = file_get_contents($file);
	
		ini_set('memory_limit', '-1');//不要限制Mem大小，否则会报错
		$line=explode("\n",$lines);dump($line);
		$all_count = count($line);//记录导入的数据的总号码数据量
		$all_count = $all_count?$all_count:0;echo $all_count;
		//如果文件过大，先对文件进行切割 ，每个文件10万行数据
		$Blackwords = M('Blackwords');
		
		foreach($line as $v){
			$sql="INSERT INTO sms_blackwords(keyword,status) VALUES('$v',1)";
			echo $sql;
			if(trim($v)){
				$m = $Blackwords->execute($sql);
				if($m){
					echo "this have a error!";
				}	
			}
		}
		
	}
	//添加常用短信
	public function smsTemplateAdd(){
		$PT = M('PrivateTemplate');
		$uid = $this->uid;
		$category_id = $this->_get('category_id');
		$sms_content = $this->_get('sms_content');
		if($category_id && $sms_content){
			$data = array(
					'uid' => $uid,
					'sms_content' => $sms_content,
					'category_id' => $category_id,
					'create_time' => 'now()',
					'status' => 1
			);
			$v = $CATE->where('uid = $uid')->select();
			if($v){
				$this->ajaxReturn($v,"短信分类如下",1);
			}else{
				$this->ajaxReturn(0,"短信分类为空",0);
			}
		}else{
			$this->ajaxReturn(0,"信息不全",0);
		}
		
	}
	//编辑常用短信
	public function smsTemplateAddEdit(){
		$PT = M('PrivateTemplate');
		$uid = $this->uid;
		$category_id = $this->_get('category_id');
		$sms_content = $this->_get('sms_content');
		if($category_id && $sms_content){
			$data = array(
					'uid' => $uid,
					'sms_content' => $sms_content,
					'category_id' => $category_id,
					'update_time' => 'now()',
					'status' => 1
			);
			$v = $CATE->where('uid = $uid')->save($data);
			if($v){
				$this->ajaxReturn($v,"短信分类如下",1);
			}else{
				$this->ajaxReturn(0,"短信分类为空",0);
			}
		}else{
			$this->ajaxReturn(0,"信息不全",0);
		}
		
	}
	//短信分类查询
	public function smsTemplateCate(){
		$CATE = M('TemplateCategory');
		$uid = $this->uid;
	
		$v = $CATE->where('uid = $uid')->select();
		if($v){
			$this->ajaxReturn($v,"短信分类如下",1);
		}else{
			$this->ajaxReturn(0,"短信分类为空",0);
		}
	}
	//短信分类修改
	public function smsTemplateCateEdit(){
		$cat = $this->_get("sms_category");
		$CATE = M('TemplateCategory');
		$uid = $this->uid;
		$cat = trim($cat);
		if($cat == ''){
			$this->ajaxReturn(0,"修改分类不能为空",0);
		}else{
			$data = array(
					'uid' => $uid,
					'category_content' => $cat,
					'create_time' => 'now()',
					'status' => 1
			);
			$v = $CATE->where('uid = $uid')->save($data);
			if($v){
				$this->ajaxReturn(1,"修改成功",1);
			}else{
				$this->ajaxReturn(0,"修改失败",0);
			}
		}
	}
	//短信分类删除
	public function smsTemplateCateDelete(){
		$cat = $this->_get("sms_category");
		$CATE = M('TemplateCategory');
		$uid = $this->uid;
		if($cat != ''){
			$tempCate = $CATE->where("uid = $uid AND category_content = '$cat'")->delete();
			if($v){
				$this->ajaxReturn(1,"删除成功",1);
			}else{
				$this->ajaxReturn(0,"删除失败",0);
			}
		}else{
			$this->ajaxReturn(0,"获取分类出错，导致删除失败",0);
		}
	}
	//短信分类添加
	public function smsTemplateCateAdd(){
		$cat = $this->_get("sms_category");
		$CATE = M('TemplateCategory');
		$uid = $this->uid;
		if($cat == ''){
			$is = $CATE->where("uid = $uid AND category_content = '$cat'")->find();
			if($is){
				$this->ajaxReturn(0,"该分类已经存在！",0);
			}
			$data = array(
					'uid' => $uid,
					'category_content' => $cat,
					'create_time' => 'now()',
					'status' => 1
			);
			$v = $CATE->where('uid = $uid')->add($data);
			if($v){
				$this->ajaxReturn(1,"添加成功",1);
			}else{
				$this->ajaxReturn(0,"添加失败",0);
			}
		}else{
			$this->ajaxReturn(0,"分类为空",0);
		}
	}
	public function excelExport(){
		import("ORG.PHPExcel.PHPExcel");
		$objPHPExcel = new PHPExcel();
		$objProps = $objPHPExcel->getProperties();
		$objProps->setCreator("5kcrm");
		$objProps->setLastModifiedBy("5kcrm");
		$objProps->setTitle("5kcrm Product");
		$objProps->setSubject("5kcrm Product Data");
		$objProps->setDescription("5kcrm Product Data");
		$objProps->setKeywords("5kcrm Product");
		$objProps->setCategory("5kcrm");
		$objPHPExcel->setActiveSheetIndex(0);
		$objActSheet = $objPHPExcel->getActiveSheet();

		$objActSheet->setTitle('Sheet1');
		$objActSheet->setCellValue('A1', '产品名');
		$objActSheet->setCellValue('B1', '产品类别');
		$objActSheet->setCellValue('C1', '成本');
		$objActSheet->setCellValue('D1', '建议报价');
		$objActSheet->setCellValue('E1', '开发团队');
		$objActSheet->setCellValue('F1', '开发时间');
		$objActSheet->setCellValue('G1', '上市时间');
		$objActSheet->setCellValue('H1', '预售');
		$objActSheet->setCellValue('I1', '库存');
		$objActSheet->setCellValue('J1', '详情链接');
		$objActSheet->setCellValue('K1', '描述');
		$objActSheet->setCellValue('L1', '创建人');
		$objActSheet->setCellValue('M1', '创建时间');

		$list = D('ProductView')->select();
		$i = 1;
		foreach ($list as $k => $v) {
			$i++;
			$creator = D('RoleView')->where('role.role_id = %d', $v['creator_role_id'])->find();
			$objActSheet->setCellValue('A'.$i, $v['name']);
			$objActSheet->setCellValue('B'.$i, $v['category_name']);
			$objActSheet->setCellValue('C'.$i, $v['cost_price']);
			$sug_price = $v['suggested_price'] > 0 ? $v['suggested_price'] : '';
			$objActSheet->setCellValue('D'.$i, $sug_price);
			$objActSheet->setCellValue('E'.$i, $v['development_team']);
			$v['development_time'] > 0 ? $objActSheet->setCellValue('F'.$i, date("Y-m-d", $v['development_time'])) : '';
			$v['listing_time'] > 0 ? $objActSheet->setCellValue('G'.$i, date("Y-m-d", $v['listing_time'])) : '';
			$pre_count = $v['pre_sale_count'] > 0 ? $v['pre_sale_count'] : '';
			$objActSheet->setCellValue('H'.$i, $pre_count);
			$stock_count = $v['stock_count'] > 0 ? $v['stock_count'] : '';
			$objActSheet->setCellValue('I'.$i, $stock_count);
			$objActSheet->setCellValue('J'.$i, $v['link']);
			$objActSheet->setCellValue('K'.$i, $v['description']);
			$objActSheet->setCellValue('L'.$i, $creator['user_name'].'['.$creator['department_name'] .'-'. $creator['role_name'] .']' );
			$objActSheet->setCellValue('M'.$i, date("Y-m-d H:i:s", $v['create_date']));
		}
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		header("Content-Type: application/vnd.ms-excel;");
        header("Content-Disposition:attachment;filename=5kcrm_product_".date('Y-m-d',mktime()).".xls");
        header("Pragma:no-cache");
        header("Expires:0");
        $objWriter->save('php://output');
	}

	public function excelImport(){
		$m_product = M('product');
		if($_POST['submit']){
			if (isset($_FILES['excel']['size']) && $_FILES['excel']['size'] != null) {
				import('@.ORG.UploadFile');
				$upload = new UploadFile();
				$upload->maxSize = 20000000;
				$upload->allowExts  = array('xls');
				$dirname = './Uploads/' . date('Ym', time()).'/'.date('d', time()).'/';
				if (!is_dir($dirname) && !mkdir($dirname, 0777, true)) {
					alert('error', '附件上传目录不可写', U('product/index'));
				}
				$upload->savePath = $dirname;
				if(!$upload->upload()) {
					alert('error', $upload->getErrorMsg(), U('product/index'));
				}else{
					$info =  $upload->getUploadFileInfo();
				}
			}
			if(is_array($info[0]) && !empty($info[0])){
				$savePath = $dirname . $info[0]['savename'];
			}else{
				alert('error', '上传失败', U('product/index'));
			};
			import("ORG.PHPExcel.PHPExcel");
			$PHPExcel = new PHPExcel();
			$PHPReader = new PHPExcel_Reader_Excel2007();
			if(!$PHPReader->canRead($savePath)){
				$PHPReader = new PHPExcel_Reader_Excel5();
			}
			$PHPExcel = $PHPReader->load($savePath);
			$currentSheet = $PHPExcel->getSheet(0);
			$allRow = $currentSheet->getHighestRow();
			for($currentRow = 2;$currentRow <= $allRow;$currentRow++){
				$data = array();
				$data['create_date'] = time();
				$data['creator_role_id'] = session('role_id');
				$name = $currentSheet->getCell('A'.$currentRow)->getValue();
				if ($m_product->where('name = "%s"', $name)->find()) {
					alert('error', '导入至第' . $currentRow . '行出错, 原因："'.$name.'"产品名已存在', U('product/index'));
					break;
				} elseif($name == '' || $name == null) {
					alert('error', '导入至第' . $currentRow . '行出错, 原因：产品名不能为空！', U('product/index'));
				} else {
					$data['name'] = $name;
				}
				$category = $currentSheet->getCell('B'.$currentRow)->getValue();
				$category_id = M('ProductCategory')->where('name = "%s"', $category)->getField('category_id');
				if($category_id > 0){
					$data['category_id'] = $category_id;
				}else{
					$data['category_id'] = 0;
				}
				$cost_price =  $currentSheet->getCell('C'.$currentRow)->getValue();
				if($cost_price != '' && $cost_price != null) $data['cost_price'] = $cost_price;
				$suggested_price =  $currentSheet->getCell('D'.$currentRow)->getValue();
				if($suggested_price != '' && $suggested_price != null) $data['suggested_price'] = $suggested_price;
				$development_team =  $currentSheet->getCell('E'.$currentRow)->getValue();
				if($development_team != '' && $development_team != null) $data['development_team'] = $development_team;

				$development_time = $currentSheet->getCell('F'.$currentRow)->getValue();
				if($development_time > 0) $data['development_time'] = intval(PHPExcel_Shared_Date::ExcelToPHP($development_time))-8*60*60;
				$listing_time = $currentSheet->getCell('G'.$currentRow)->getValue();
				if($listing_time > 0) $data['listing_time'] = intval(PHPExcel_Shared_Date::ExcelToPHP($listing_time))-8*60*60;
				$pre_sale_count =  $currentSheet->getCell('H'.$currentRow)->getValue();
				if($pre_sale_count != '' && $pre_sale_count != null) $data['pre_sale_count'] = $pre_sale_count;
				$stock_count = $currentSheet->getCell('I'.$currentRow)->getValue();
				if($stock_count != '' && $stock_count != null) $data['stock_count'] = $stock_count;
				$link = $currentSheet->getCell('J'.$currentRow)->getValue();
				if($link != '' && $link != null) $data['link'] = $link;
				$description = $currentSheet->getCell('K'.$currentRow)->getValue();
				if($description != '' && $description != null) $data['description'] = $description;
				if (!$m_product->add($data)) {
					alert('error', $m_product->getLastSql() . '导入至第' . $currentRow . '行添加数据出错', U('business/index'));
				}
			}
			alert('success', '导入成功', U('product/index'));
		}else{
			$this->display();
		}
	}
	public function importFile(){
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 100000000 ;// 设置附件上传大小
		$upload->allowExts  = array('txt', 'xls');// 设置附件上传类型
		$upload->savePath =  './Uploads/files/';// 设置附件上传目录
		$upload->saveRule = 'time';
		$upload->autoCheck = true;
		if(!$upload->upload()) {// 上传错误提示错误信息
		$this->error($upload->getErrorMsg());
		}else{// 上传成功 获取上传文件信息
		$info =  $upload->getUploadFileInfo();   //$info[0]['savename'];
		}
		$filename = $info[0]['savename'];
//进行处理
		$file = $upload->savePath.$info[0]['savename'];//'words.txt';//10W条记录的TXT源文件
		$lines = file_get_contents($file);
		ini_set('memory_limit', '-1');//不要限制Mem大小，否则会报错
		$line=explode("\n",$lines);
		$all_count = count($line);//记录导入的数据的总号码数据量
		$all_count = $all_count?$all_count:0;echo $all_count;
		//如果文件过大，先对文件进行切割 ，每个文件10万行数据
		$prefixFile = date("YmdHis");
		if($all_count > 100000){
			$cut_count = ceil($all_count/100000);//总共输出的文件数
			$fn_in = $file;//你要切割的大文件
			$fn_out = $prefixFile.'_';//输出文件的前缀
			$i=0;//当前文件已经输出的行数
			$n=1;
			$fp_in=fopen($fn_in,'r');	

			while (!feof($fp_in)){//not end
				if($i==0){
					echo './Uploads/files/'.$fn_out.$n.'.txt';
					$fp_out=fopen('./Uploads/files/'.$fn_out.$n.'.txt','w');
				}
			    $outLine=fgets($fp_in);
				
				if(fputs($fp_out,$outLine)){echo "success!";}
			    $i++;
				if($i==100000){
				  fclose($fp_out); 
				  $i=0; 
				  $n++;
				}
			}
			if($i == $cut_count){
				fclose($fp_in); 
				$i=0;
			}
			
		}

		$success_count = 0;
		$fail_count = 0;
		$notMobile = '';
		$isMobile = '';
		$INBOX = M('Inbox');
		foreach($line as $key =>$li)
		{	
			$li = trim($li);
			if(isMobile($li)){
				$isMobile .= $li.',';
				$success_count++;
			}else{
				$notMobile .= $li.',';
				$fail_count++;
			}
		}
		echo 's'.$success_count."s";echo $fail_count;
		echo $notMobile;
		$sql="INSERT INTO sms_inbox (filename,text,all_count,success,success_count,fail,fail_count,create_time) VALUES('$filename','$lines','$all_count','$isMobile','$success_count','$notMobile','$fail_count',now())";
	
		//if($INBOX->execute($sql)){
			echo "总共发现[$all_count]个手机号码，成功导入[$success_count]个到数据库中,失败[$fail_count]个";//$this->success();
		//}
		
//		if($i>=20000&&$i<25000)//分批次导入，避免失败
//		{
//		$mm=explode(" ",$arr[4]);
//		foreach($mm as $m) //【adductive#1 adducting#1 adducent#1】这一个TXT记录要转换为3个SQL记录 {
//		$nn=explode("#",$m);
//		$word=$nn[0];
//		$sql.="(\"$word\",1,$senti_value,2),";//这个地方要注意到是 word有可能包含单引号（如jack's），因此我们要用双引号来包含word（注意转义）
//		}
//		}
//		$i++;
//		}
//
//		//echo $i;
//		$sql= substr($sql,0,-1);//去掉最后一个逗号
//		//echo $sql;
//		file_put_contents('20000-25000.txt', $sql); //批量导入数据库，5000条一次，大概需要40秒的样子；一次导入太多max_execution_time会不够，导致失败
	}
}