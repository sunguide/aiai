<?php

class ContactsAction extends Action {


	
//	public function add(){
//		if($_POST['name']){
//			$contacts = M('contacts');
//			$contacts = M('contacts');
//			$contacts->create();
//			$contacts->create_time = time();
//			$contacts->update_time = time();
//			if($contacts->add()){
//				if($_POST['redirect'] == 'customer'){
//					alert('success','添加成功!',U('customer/view') . "?id=" . $_POST['redirect_id']);
//				}else{
//					if($_POST['submit'] == '保存'){
//						alert('success','添加成功!',U('contacts/index'));
//					}else{
//						alert('success','添加成功!',U('contacts/add'));
//					}
//					
//				}
//			}else{
//				alert('error','添加失败!',$_SERVER['HTTP_REFERER']);
//			}
//		}else{
//			if($_GET['redirect']){
//				$this->redirect_id = $_GET['redirect_id'];
//				$this->redirect = $_GET['redirect'];
//			}
//			$customer = M('customer');
//			$this->customer = $customer->where('customer_id =' . $_GET['redirect_id'])->find();
//			$this->alert = parseAlert();
//			$this->display();
//		}
//	}
	
	public function add(){
		if ($_GET['r'] && $_GET['module'] && $_GET['id']) {
			$this -> r = $_GET['r'];
			$this -> module = $_GET['module'];
			$this -> id = $_GET['id'];
			$this->display('Contacts:add_dialog');
		}elseif($_POST['submit']){
			$name = trim($_POST['name']);
			if ($name == '' || $name == null) {
				alert('error','联系人姓名不能为空!',$_SERVER['HTTP_REFERER']);
			}
			$contacts = M('contacts');
			
			$contacts->create();
			$contacts->create_time = time();
			$contacts->update_time = time();
			$contacts->creator_role_id = session('role_id');
			if($contacts_id = $contacts->add()){
				if($_POST['customer_id']){
					$rContactsCustomer['contacts_id'] =  $contacts_id;
					$rContactsCustomer['customer_id'] =  $_POST['customer_id'];
					M('rContactsCustomer') ->add($rContactsCustomer);
				}
				
				if($_POST['redirect'] == 'customer'){
					alert('success','添加成功!',U('customer/view') . "?id=" . $_POST['redirect_id']);
				}else{
					if($_POST['submit'] == '保存'){
						alert('success','添加成功!',U('contacts/index'));
					}else{
						alert('success','添加成功!',U('contacts/add'));
					}
					
				}
			}else{
				alert('error','添加失败!',$_SERVER['HTTP_REFERER']);
			}		
		}else{
			if($_GET['redirect']){
				$this->redirect_id = $_GET['redirect_id'];
				$this->redirect = $_GET['redirect'];
			}
			$customer = M('customer');
			$this->customer = $customer->where('customer_id =' . $_GET['redirect_id'])->find();
			
			$this->display();
		}
	}
	
	public function edit(){
		$m_contacts = M('contacts');
		$rContactsCustomer = M('rContactsCustomer');
		if ($_GET['id']) {
			$contacts = $m_contacts->where('contacts_id = %d', $_GET['id'])->find();
			$customer_id = $rContactsCustomer->where('contacts_id = %d', $_GET['id'])->getField('customer_id');
			$contacts['customer'] = M('customer')->where('customer_id = %d' , $customer_id)->find();
			$contacts['owner'] = D('RoleView')->where('role.role_id = %d' , $contacts['owner_role_id'])->find();
			$this->contacts = $contacts;
			$this->customer = $customer;
			$this->alert = parseAlert();
			$this->display();
		}elseif($_POST['submit']){
			$m_contacts->create();
			$m_contacts->update_time = time();
			$name = trim($_POST['name']);
			if ($name == '' || $name == null) {
				alert('error','联系人姓名不能为空!',$_SERVER['HTTP_REFERER']);
			}
			if (!empty($_POST['customer_id'])) {
				$customer_id = M('contacts') -> where('contacts_id = %d' , $_POST['contacts_id']) -> getField('customer_id');
				if (empty($customer_id)) {
					$data['contacts_id'] = $_POST['contacts_id'];
					$data['customer_id'] = $_POST['customer_id'];
					M('rContactsCustomer') ->where('contacts_id = %d', $_POST['contacts_id'])->delete();
					M('rContactsCustomer') -> add($data);
				}elseif ($_POST['customer_id'] != $customer_id) {
					M('rContactsCustomer') -> where('contacts_id = %d' , $_POST['contacts_id']) -> setField('customer_id',$_POST['customer_id']);
				}	
			}
			if ($m_contacts->save()) {
				alert('success','联系人信息成功!',U('contacts/view') . "&id=" . $_POST['contacts_id']);
			} else {
				alert('error','联系人信息修改失败！',$_SERVER['HTTP_REFERER']);
			}
		}
	}
	
	public function view(){
		$contacts_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		if (0 == $contacts_id) {
			alert('error', '参数错误！', U('contacts/index'));
		} else {
			$contacts = M('contacts')->where('contacts_id = %d' , $contacts_id)->find();
			
			$contacts['owner'] = D('RoleView')->where('role.role_id = %d' , $contacts['owner_role_id'])->find();
			$customer_id = M('rContactsCustomer')->where('contacts_id = %d', $contacts['contacts_id'])->getField('customer_id');
			$contacts['customer'] = M('customer')->where('customer_id = %d' ,$customer_id)->find();
			
			$log_ids = M('rContactsLog')->where('contacts_id = %d', $contacts_id)->getField('log_id', true);
			$contacts['log'] = M('log')->where('log_id in (%s)', implode(',', $log_ids))->select();
			$log_count = 0;
			foreach ($contacts['log'] as $key=>$value) {
				$contacts['log'][$key]['owner'] = D('RoleView')->where('role.role_id = %d', $value['role_id'])->find();
				$log_count++;
			}
			$contacts['log_count'] = $log_count;
			
			$file_ids = M('rContactsFile')->where('contacts_id = %d', $contacts_id)->getField('file_id', true);
			$contacts['file'] = M('file')->where('file_id in (%s)', implode(',', $file_ids))->select();
			$file_count = 0;
			foreach ($contacts['file'] as $key=>$value) {
				$contacts['file'][$key]['owner'] = D('RoleView')->where('role.role_id = %d', $value['role_id'])->find();
				$file_count++;
			}
			$contacts['file_count'] = $file_count;
			
			$this->contacts = $contacts;		
			$this->alert = parseAlert();
			$this->display();
		}		
	}

	public function index(){
		$m_contacts = M('contacts');
		$m_customer = M('customer');
		$p = isset($_GET['p']) ? intval($_GET['p']) : 1 ;
		$by = isset($_GET['by']) ? trim($_GET['by']) : '';
		$below_ids = getSubRoleId(false);
		$where = array();
		$params = array();
		$order = "";	
		switch ($by) {
			case 'sub' : $where['owner_role_id'] = array('in',implode(',', $below_ids)); break;
			case 'today' : $where['create_time'] =  array('gt',strtotime(date('Y-m-d', time()))); break;
			case 'week' : $where['create_time'] =  array('gt',(strtotime(date('Y-m-d', time())) - (date('N', time()) - 1) * 86400)); break;
			case 'month' : $where['create_time'] = array('gt',strtotime(date('Y-m-01', time()))); break;
			case 'add' : $order = 'create_time desc'; break;
			case 'update' : $order = 'update_time desc'; break;
			case 'deleted' : $where['is_deleted'] = 1; break;
			default : $where['owner_role_id'] = session('role_id'); break;
		}
		if (!isset($where['owner_role_id'])) {
			$where['owner_role_id'] = array('in',implode(',', getSubRoleId(true)));
		}
		if (!isset($where['is_deleted'])) {
			$where['is_deleted'] = 0;
		}
		if ($_REQUEST["field"]) {
			$field = trim($_REQUEST['field']) == 'all' ? 'name|telephone|email|address|post|department|description' : $_REQUEST['field'];
			$search = empty($_REQUEST['search']) ? '' : trim($_REQUEST['search']);
			$condition = empty($_REQUEST['condition']) ? 'is' : trim($_REQUEST['condition']);
			if	('create_time' == $field || 'update_time' == $field) {
				$search = is_numeric($search)?$search:strtotime($search);
			}
			switch ($condition) {
				case "is" : $where[$field] = array('eq',$search);break;
				case "isnot" :  $where[$field] = array('neq',$search);break;
				case "contains" :  $where[$field] = array('like','%'.$search.'%');break;
				case "not_contain" :  $where[$field] = array('notlike','%'.$search.'%');break;
				case "start_with" :  $where[$field] = array('like',$search.'%');break;
				case "end_with" :  $where[$field] = array('like','%'.$search);break;
				case "is_empty" :  $where[$field] = array('eq','');break;
				case "is_not_empty" :  $where[$field] = array('neq','');break;
				case "gt" :  $where[$field] = array('gt',$search);break;
				case "egt" :  $where[$field] = array('egt',$search);break;
				case "lt" :  $where[$field] = array('lt',$search);break;
				case "elt" :  $where[$field] = array('elt',$search);break;
				case "eq" : $where[$field] = array('eq',$search);break;
				case "neq" : $where[$field] = array('neq',$search);break;
				case "between" : $where[$field] = array('between',array($search-1,$search+86400));break;
				case "nbetween" : $where[$field] = array('not between',array($search,$search+86399));break;
				case "tgt" :  $where[$field] = array('gt',$search+86400);break;
				default : $where[$field] = array('eq',$search);
			}
			$params = array('field='.$field, 'condition='.$condition, 'search='.$_REQUEST["search"]);
		}
		if ($order) {
			$contactsList = $m_contacts->where($where)->order($order)->limit(15)->select();
		} else{
			$contactsList = $m_contacts->where($where)->order('create_time desc')->page($p.',10')->select();
			$count = $m_contacts->where($where)->count();
			import("@.ORG.Page");
			$Page = new Page($count,10);
			if (!empty($_GET['by'])) {
				$params[] = "by=".trim($_GET['by']);
			}
			$Page->parameter = implode('&', $params);
			$this->assign('page',$Page->show());
		}
		if($by == 'deleted') {
			foreach ($contactsList as $k => $v) {
				$contactsList[$k]["delete_role"] = getUserByRoleId($v['delete_role_id']);
				$contactsList[$k]["owner"] = getUserByRoleId($v['owner_role_id']);
				$contactsList[$k]["creator"] = getUserByRoleId($v['creator_role_id']);
				$customer_id = M('rContactsCustomer')->where('contacts_id = %d', $v['contacts_id'])->getField('customer_id');
				$contactsList[$k]['customer'] = $m_customer->where('customer_id = %d' ,$customer_id)->find();
			}
		}else{
			foreach ($contactsList as $k => $v) {		
				$contactsList[$k]["owner"] = getUserByRoleId($v['owner_role_id']);
				$contactsList[$k]["creator"] = getUserByRoleId($v['creator_role_id']);
				$contactsList[$k]['customer'] = $m_customer->where('customer_id = %d' ,$v['customer_id'])->find();
				$customer_id = M('rContactsCustomer')->where('contacts_id = %d', $v['contacts_id'])->getField('customer_id');
				$contactsList[$k]['customer'] = $m_customer->where('customer_id = %d' ,$customer_id)->find();
			}
		}
		
		//获取下级和自己的岗位列表,搜索用
		$d_role_view = D('RoleView');
		$this->role_list = $d_role_view->where('role.role_id in (%s)', implode(',', $below_ids))->select();
		$this->assign('contactsList',$contactsList);
		$this->alert = parseAlert();
		$this->display();
	}

	public function completeDelete(){
		$m_contacts = M('contacts');
		$r_module = array('File'=>'RContactsFile', 'Log'=>'RContactsLog', 'RContactsCustomer', 'RContactsTask','RContactsEvent');
		if ($_POST['contacts_id']) {
			if (!session('?admin')) {
				foreach ($_POST['contacts_id'] as $value) {
					if($m_contacts->where('contacts_id = %d', $value)->getField('owner_role_id')!=session('role_id')){
						alert('error', '您没有全部的权限', $_SERVER['HTTP_REFERER']);
					}
				}
			}
			if ($m_contacts->where('contacts_id in (%s)', join($_POST['contacts_id'],','))->delete()) {
				foreach ($_POST['contacts_list'] as $value) {
					foreach ($r_module as $key2=>$value2) {
						$module_ids = M($value2)->where('contacts_id = %d', $value)->getField($key2 . '_id',true);
						M($value2)->where('contacts_id = %d', $value) -> delete();
						if(!is_int($key2)){
							M($key2)->where($key2 . '_id in (%s)', implode(',', $module_ids))->delete();
						}
					}
				}
				alert('success', '删除成功!',$_SERVER['HTTP_REFERER']);
			} else {
				$this->error('删除失败，联系管理员！');
			}
		}elseif($_GET['id']){
			$contacts_id = intval($_GET['id']);
			$contacts = $m_contacts->where('contacts_id = %d', $contacts_id)->find();
			if (is_array($contacts)) {
				if (session('?admin') || $contacts['owner_role_id'] == session('role_id')) {
					if($m_contacts->where('contacts_id = %d', $contacts_id)->delete()){
						foreach ($r_module as $key2=>$value2) {
							if(!is_int($key2)){
								$module_ids = M($value2)->where('contacts_id = %d', $contacts_id)->getField($key2 . '_id',true);
								M($value2)->where('contacts_id = %d', $contacts_id)->delete();
								$m_key = M($key2);
								$m_key->where($key2 . '_id in (%s)', implode(',', $module_ids))->delete();
							}
						}
						alert('success', '删除成功!',U('contacts/index'));
					} else {
						alert('error', '删除失败！', U('contacts/index'));
					}
				} else {
					alert('error', '您没有权限！', U('contacts/index'));
				}
			} else {
				alert('error', '您要删除的纪录不存在！', $_SERVER['HTTP_REFERER']);
			}
		}else{
			alert('error','请选择要删除的联系人!',$_SERVER['HTTP_REFERER']);
		}
	}
	
	public function delete(){
		$m_contacts = M('contacts');
		if ($_POST['contacts_id']) {
			if (!session('?admin')) {
				foreach ($_POST['contacts_id'] as $value) {
					if($m_contacts->where('contacts_id = %d', $value)->getField('owner_role_id')!=session('role_id')){
						alert('error', '您没有全部的权限', $_SERVER['HTTP_REFERER']);
					}
				}
			}
			$data = array('is_deleted'=>1, 'delete_role_id'=>session('role_id'), 'delete_time'=>time());
			if ($m_contacts->where('contacts_id in (%s)', implode(',', $_POST['contacts_id']))->setField($data)) {
				alert('success', '删除成功!',$_SERVER['HTTP_REFERER']);
			} else {
				echo $m_contacts->getLastSql(); die();
				alert('error', '删除失败,请联系管理员！', U('contacts/index'));
			}
		}elseif($_GET['id']){
			$contacts_id = intval($_GET['id']);
			$contacts = $m_contacts->where('contacts_id = %d', $contacts_id)->find();
			if (is_array($contacts)) {
				if (session('?admin') || $contacts['owner_role_id'] == session('role_id')) {
					$data = array('is_deleted'=>1, 'delete_role_id'=>session('role_id'), 'delete_time'=>time());
					if($m_contacts->where('contacts_id = %d', $contacts_id)->setField($data)){
						alert('success', '删除成功!',U('contacts/index'));
					} else {
						alert('error', '删除失败！', U('contacts/index'));
					}
				} else {
					alert('error', '您没有权限！', U('contacts/index'));
				}
			} else {
				alert('error', '您要删除的纪录不存在！', $_SERVER['HTTP_REFERER']);
			}
		}else{
			alert('error','请选择要删除的联系人!',$_SERVER['HTTP_REFERER']);
		}
	}
	
	public function mDelete(){
		if($_GET['r'] && $_GET['id'] && $_GET['module_id']){
			$m_r = M($_GET['r']);
			if($m_r->where("contacts_id = %d and customer_id", $_GET['id'], $_GET['module_id'])->delete()){
				alert('success','删除成功',$_SERVER['HTTP_REFERER']);
			} else {
				alert('error','删除失败',$_SERVER['HTTP_REFERER']);
			}
		} else {
			alert('error','参数错误！',$_SERVER['HTTP_REFERER']);
		}
	}
	
	public function checkListDialog(){
		if($this->isPost()){
			$r = $_POST['r'];
			$model_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
			$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
			$m_r = M($r);
			$m_id = $_POST['module'] . '_id';  //对应模块的id字段
			
			$data[$m_id] = $model_id;
			foreach ($_POST['contacts_id'] as $value) {
				$data['contacts_id'] = $value;
				if ($m_r -> add($data) <= 0) {
					alert('error', '选择联系人失败！',$_SERVER['HTTP_REFERER']);
				}
			}
			alert('success', '选择联系人成功！',$_SERVER['HTTP_REFERER']);
		}elseif ($_GET['r'] && $_GET['module'] && $_GET['id']) {
			$list = M($_GET['r']) -> getField('contacts_id', true);
			$m_contacts = M('Contacts');
			$underling_ids = getSubRoleId();
			$list[] = 0;
			$this->contactsList = $m_contacts->where('contacts_id not in (%s) and owner_role_id in (%s) and is_deleted = 0', implode(',',$list), implode(',', $underling_ids))->select();
			$this -> r = $_GET['r'];
			$this -> module = $_GET['module'];
			$this -> model_id = $_GET['id'];
			$this->display();
		}else{
			alert('error', '参数错误！',$_SERVER['HTTP_REFERER']);
		}
	}
public function test(){
		$input = "10101010101010101010";
		$input = str_split($input);
		dump($input);
}
	public function excelExport(){
		import("ORG.PHPExcel.PHPExcel");
		$objPHPExcel = new PHPExcel();    
		$objProps = $objPHPExcel->getProperties();    
		$objProps->setCreator("十目科技");
		$objProps->setLastModifiedBy("十目科技");    
		$objProps->setTitle("十目科技通讯录");    
		$objProps->setSubject("十目科技通讯录");    
		$objProps->setDescription("十目科技通讯录");    
		$objProps->setKeywords("十目科技通讯录");    
		$objProps->setCategory("十目科技通讯录");
		$objPHPExcel->setActiveSheetIndex(0);     
		$objActSheet = $objPHPExcel->getActiveSheet(); 
	    $matchCn = array('姓名','手机','身份证号','生日','性别','公司名称','公司电话','公司传真','公司部门','公司职务','公司邮箱','公司网址','公司邮编','公司地址','昵称','私人QQ','私人邮箱','私人邮编','私人网址','家庭地址');
		$matchEn = array('name','mobile','identification_card','birthday','gender','company','comtel','comfax','comdepartment','job','comemail','comwebsite','comzip','comaddress','nickname','privateqq','privateemail','privatezip','privatewebsite','address');  
		$input = "11111111111111111111";
		$icount = strlen($input);
		
		
		
		$mcount = count($matchCn);
		$input = str_split($input);
		if($icount != $mcount){
			$error['msg'] = "参数出错";
			
		}else{
			$objActSheet->setTitle('Sheet1');
			$A = 'A';
			$k = 0;
			foreach($input as $idata){
				if($idata){
					$objActSheet->setCellValue($A.'1', $matchCn[$k]);
					$MC[] = $matchEn[$k];
					$A++;
				}
				$k++;
			}
			/**
			$objActSheet->setCellValue('A1', '姓名');
			$objActSheet->setCellValue('B1', '尊称');
			$objActSheet->setCellValue('C1', '部门');
			$objActSheet->setCellValue('D1', '职位');
			$objActSheet->setCellValue('E1', '电话');
			$objActSheet->setCellValue('F1', 'Email');
			$objActSheet->setCellValue('G1', '地址');
			$objActSheet->setCellValue('H1', '邮编');
			$objActSheet->setCellValue('I1', '备注');
			$objActSheet->setCellValue('J1', '所属客户');
			$objActSheet->setCellValue('K1', '负责人');
			$objActSheet->setCellValue('L1', '创建人');
			$objActSheet->setCellValue('M1', '创建时间');
			**/

			//$where['owner_role_id'] = array('in',implode(',', getSubRoleId()));
			$list = M('contacts')->where($where)->select();
			$i = 1;
			foreach ($list as $k => $v) {
				$i++;
				$owner = D('RoleView')->where('role.role_id = %d', $v['owner_role_id'])->find();
				$creator = D('RoleView')->where('role.role_id = %d', $v['creator_role_id'])->find();
				//整理数据输出
				$imc = 0;
				for($ik = 'A';$ik <= $A;$ik++){
					$name = $MC[$imc];
					$objActSheet->setCellValue($ik.$i, $v[$name]);
					$imc++;
				}
				/*
				$objActSheet->setCellValue('A'.$i, $v['name']);
				$objActSheet->setCellValue('B'.$i, $v['mobile']);
				$objActSheet->setCellValue('C'.$i, $v['email']);
				$objActSheet->setCellValue('D'.$i, $v['address']);
				$objActSheet->setCellValue('E'.$i, $v['company']);
				$objActSheet->setCellValue('F'.$i, $v['department']);
				$objActSheet->setCellValue('G'.$i, $v['description']);
				*/
				
			}
			/*输出一些其他信息
			$customer_id = M('rContactsCustomer')->where('contacts_id = %d', $v['contacts_id'])->getField('customer_id');
				$customer_name = M('customer')->where('customer_id = %d' ,$customer_id)->getField('name');
				$objActSheet->setCellValue('J'.$i, $customer_name);
				$objActSheet->setCellValue('K'.$i, $owner['user_name'] .'['.$owner['department_name'].'-'.$owner['role_name'].']');
				$objActSheet->setCellValue('L'.$i, $creator['user_name'].'['.$creator['department_name'].'-'.$creator['role_name'].']');
				$objActSheet->setCellValue('M'.$i, date("Y-m-d H:i:s", $v['create_time']));
			*/
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			header("Content-Type: application/vnd.ms-excel;");
			header("Content-Disposition:attachment;filename=sm_contacts_".date('Y-m-d',mktime()).".xls");
			header("Pragma:no-cache");
			header("Expires:0");
			$objWriter->save('php://output'); 
		
		}
		
	}
	
	public function excelImport(){
		$m_contacts = M('Contacts');
		if (isset($_FILES['excel']['size']) && $_FILES['excel']['size'] != null) {
			import('ORG.Net.UploadFile');
			$upload = new UploadFile();// 实例化上传类
			$upload->maxSize  = 100000000 ;// 设置附件上传大小
			$upload->allowExts  = array('xls');// 设置附件上传类型
			$upload->savePath =  './Uploads/contacts/';// 设置附件上传目录
			$upload->saveRule = 'time';
			$upload->autoSub = true; //开启子目录保存
			$upload->subType = 'date';//子目录以日期格式保存
			$upload->dateFormat = 'Ym/d';//子目录日期模式下保存格式
			$upload->autoCheck = true;
			$dirname = './Uploads/contacts/';// . date('Ym', time()).'/'.date('d', time()).'/'
			if (!is_dir($dirname) && !mkdir($dirname, 0777, true)) {
				$error['msg'] = "附件上传目录不可写";
			}
			if(!$upload->upload()) {// 上传错误提示错误信息
			$this->error($upload->getErrorMsg());
			}else{// 上传成功 获取上传文件信息
			$info =  $upload->getUploadFileInfo();   //$info[0]['savename'];
			}	
			$filename = $info[0]['savename'];
		}
		
		if(is_array($info[0]) && !empty($info[0])){
			$savePath = $dirname . $info[0]['savename'];
		}else{
			$error['msg'] .= "上传失败";
		}
		import("ORG.PHPExcel.PHPExcel");
		$PHPExcel = new PHPExcel();
		$PHPReader = new PHPExcel_Reader_Excel2007();
		if(!$PHPReader->canRead($savePath)){
			$PHPReader = new PHPExcel_Reader_Excel5();
		}
		$PHPExcel = $PHPReader->load($savePath);
		$currentSheet = $PHPExcel->getSheet(0);
		$currentSheet->getStyle()->getFont()->applyFromArray(
	 		array(
	 			'name'	  => 'Arial'
	 			)
	 	);
	    
		$allRow = $currentSheet->getHighestRow();
		
		$matchCn = array('姓名','手机','身份证号','生日','性别','公司名称','公司电话','公司传真','公司部门','公司职务','公司邮箱','公司网址','公司邮编','公司地址','昵称','私人QQ','私人邮箱','私人邮编','私人网址','家庭地址');
		$matchEn = array('name','mobile','identification_card','birthday','gender','company','comtel','comfax','comdepartment','job','comemail','comwebsite','comzip','comaddress','nickname','privateqq','privateemail','privatezip','privatewebsite','address');
	//--------------------------------------
	//整理要导入的具体项目
		$flag = 1;
		$AZ = 'A';
		$MT = array();
		$error['msg'] .= "以下格式不对:";
		while($flag){
			
			$value = $currentSheet->getCell($AZ.'1')->getValue();
			if($value == '') {
				$flag = 0;
				break;
			}
		
			echo $value;
			$k = 0;
			$is = false;
			foreach($matchCn as $m){
				if($value == $m){
					$iflag = $flag - 1;
					
					$MT[] = array(
						'position' => $AZ,
						'en'       => $matchEn[$k]
					);
					$is = true;
					echo $matchEn[$k];
					break;
				}
				$k++;
			}
			if(!$is){
				$error = $error.$value."--";
			}
			$AZ++;
			$flag++;	
		}
//--------------------------------------
		for ($currentRow = 2;$currentRow <= $allRow;$currentRow++) {
			$data = array();
			$data['uid'] = session('uid');
			$data['create_time'] = date('Y-m-d H:i:s');
			$data['update_time'] = date('Y-m-d H:i:s');
			$data['owner_uid'] = trim($_POST['owner_role_id']);
//------------------------------------------
foreach($MT as $mt){
	$currentSheet->getStyle($mt['position'].$currentRow)->getFont()->applyFromArray(array('name' => 'Arial'));
	//dump($currentSheet->getStyle($mt['position'].$currentRow)->getFont());
	//echo $mt['position'].$currentRow;
	$objStyleAZ = $currentSheet->getStyle($mt['position'].$currentRow);  
  
	//设置单元格内容的数字格式。  
	//  
	//如果使用了 PHPExcel_Writer_Excel5 来生成内容的话，  
	//这里需要注意，在 PHPExcel_Style_NumberFormat 类的 const 变量定义的  
	//各种自定义格式化方式中，其它类型都可以正常使用，但当setFormatCode  
	//为 FORMAT_NUMBER 的时候，实际出来的效果被没有把格式设置为"0"。需要  
	//修改 PHPExcel_Writer_Excel5_Format 类源代码中的 getXf($style) 方法，  
	//在 if ($this->_BIFF_version == 0x0500) { （第363行附近）前面增加一  
	//行代码:   
	//if($ifmt === '0') $ifmt = 1;  
	//  
	//设置格式为PHPExcel_Style_NumberFormat::FORMAT_NUMBER，避免某些大数字  
	//被使用科学记数方式显示，配合下面的 setAutoSize 方法可以让每一行的内容  
	//都按原始内容全部显示出来。  
	$objStyleAZ->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER);    
	//设置字体  
	$objFontA5 = $objStyleAZ->getFont();  
	$objFontA5->setName('新宋体');  
	$cv = $currentSheet->getCell($mt['position'].$currentRow)->getValue();
	$var = $mt['en'];
	$cv != '' && $cv != null ? $data[$var]= $cv : ''; ; 
}
//------------------------------------------
/*			$name = $currentSheet->getCell('A'.$currentRow)->getValue();
			$name != '' && $name != null ? 
			$mobile = $currentSheet->getCell('B'.$currentRow)->getValue();
			$mobile != '' && $mobile != null ? $data['mobile'] = $mobile : '';
			$gender = $currentSheet->getCell('C'.$currentRow)->getValue();
			$gender != '' && $gender != null ? $data['gender'] = $gender : '';
			$email = $currentSheet->getCell('D'.$currentRow)->getValue();
			$email != '' && $email != null ? $data['email'] = $email : '';
			$address = $currentSheet->getCell('E'.$currentRow)->getValue();
			$address != '' && $address != null ? $data['address'] = $address : '';
			$company = $currentSheet->getCell('F'.$currentRow)->getValue();
			$company != '' && $company != null ? $data['company'] = $company : '';				
			$department = $currentSheet->getCell('G'.$currentRow)->getValue();
			$department != '' && $department != null ? $data['department'] = $department : '';
			$description = $currentSheet->getCell('H'.$currentRow)->getValue();
			$description != '' && $description != null ? $data['description'] = $description : '';
			dump($data);

		*/
			if(!$m_contacts->add($data)) {
				alert('error', '导入至第' . $currentRow . '行出错', U('leads/index'));
				$error['msg'] .= '导入至第' . $currentRow . '行出错';
				break;
			}
		
			
		} 
	}
	
	public function revert(){
		$contacts_id = isset($_GET['id']) ? intval(trim($_GET['id'])) : 0;
		if ($contacts_id > 0) {
			$m_contacts = M('contacts');
			$contacts = $m_contacts->where('contacts_id = %d', $contacts_id)->find();
			if ($contacts['delete_role_id'] == session('role_id') || session('?admin')) {
				if (isset($contacts['is_deleted']) || $contacts['is_deleted'] == 1) {
					if ($m_contacts->where('contacts_id = %d', $contacts_id)->setField('is_deleted', 0)) {
						alert('success', '还原成功！', $_SERVER['HTTP_REFERER']);
					} else {
						alert('error', '还原失败！', $_SERVER['HTTP_REFERER']);
					}
				} else {
					alert('error', '已经还原！', $_SERVER['HTTP_REFERER']);
				}
			} else {
				alert('error', '您没有权限还原！', $_SERVER['HTTP_REFERER']);
			}
		} else {
			alert('error', '参数错误！', $_SERVER['HTTP_REFERER']);
		}
	}
	
	public function radioListDialog(){
		$rcc =  M('RContactsCustomer');
		$m_contacts = M('contacts');
		$where['owner_role_id'] = array('in', implode(',', getSubRoleId()));
		$where['is_deleted'] = 0;
		if($_GET['customer_id']){
			$contacts_id = $rcc->where('customer_id = %d', $_GET['customer_id'])->getField('contacts_id', true);
			$where['contacts_id'] = array('in', implode(',', $contacts_id));
		}
		$list = $m_contacts->where($where)->select();
		foreach ($list as $k=>$value) {
			$customer_id = $rcc->where('contacts_id = %d', $value['contacts_id'])->getField('customer_id');
			$list[$k]['customer'] = M('customer')->where('customer_id = %d', $customer_id)->find();
		}
		$this->contactsList = $list;
		$this->display();
	}
}