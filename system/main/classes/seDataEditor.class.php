<?php
//error_reporting(E_ALL);
require_once (dirname(__FILE__).'/seMenuExecute.class.php');
require_once (dirname(__FILE__).'/seModule39.class.php');
require_once dirname(__FILE__)."/seImages.class.php";
require_once "lib/lib_images.php";
require_once SE_ROOT.'admin/helpers/modal.php';
//require_once SE_ROOT."admin/views/seLanguage.php";
//require_once dirname(__FILE__)."/../function.php";

		
//require_once (dirname(__FILE__).'/../function.php');
//error_reporting(E_ALL);


class seDataEditor {
private static $instance = null;
//public $menu = null;
private $data = null;
public $page = null;
public $pages = null;
public $prj = null;
public $adminlogin = '';
public $header = '';
public $adminpassw = '';
public $skin;
public $img;
public $files;
public $versionproduct;
public $gkeywords;
public $gdescription;
public $sections = null;
public $pagename;
public $mainmenu;
public $mainmeny_type = 0;
public $req;
public $error;
private $lang;
private $title_icon_edsection;
private $title_icon_addrecord;
private $title_icon_edrecord;
private $title_icon_addsection;
private $langmess;

private $unique;

  public function __construct($seData, $lang = 'ru')
  {
  	
  	$this->unique = isset($_POST['unique']) ? $_POST['unique'] : substr(md5(time().'unique'),0,10);
	if (!is_dir(SE_ROOT.'projects/' . SE_DIR . 'edit')) mkdir(SE_ROOT.'projects/' . SE_DIR . 'edit');
	$this->data = $seData;
	$this->lang = $lang;
	// Загружаем переводы
	$ymlfile = SE_ROOT . 'admin/i18n/' . $this->lang .'.yml';
    if (file_exists( $ymlfile ))
    { 
        $ymlres = seYAML::Load( $ymlfile );
        foreach($ymlres as $classname=>$this->langmess);
    }
	$this->title_icon_edsection = $this->getTextLanguage('edit', 'sec');
	$this->title_icon_addrecord = $this->getTextLanguage('add', 'rec');
	$this->title_icon_edrecord = $this->getTextLanguage('edit', 'rec');
	$this->title_icon_addsection = $this->getTextLanguage('add', 'sec');
	//$this->pagename = $this->data->getPageName();
  }
  public function getLang() {
      return $this->lang;
  }
  
// ###############################  EDITORS METHODS ###############################
  public function editorAccess($page = '', $section = null){ 
	$_SESSION['editor_images_access'] = seUserGroup();
	return (seUserGroup() == 3 && $_SESSION['EDITOR_ADMIN']);
  }


  
  private function setNodeMenu($node, $sort){
	//$sort[$name] = array_unique($sort[$name]);
	foreach($sort as $item) {
		foreach($this->data->pages as $page){
			if (strval($page['name']) == $item['id']){
				$menu = $node->addChild('item', '');
				$menu['name'] = $item['id'];
				$menu->visible = 1;
				$menu->name = $item['id'];
				$menu->title = $page->title;
				if (empty($page->url)) $page->url = $item['id'].'/';
				$menu->url = $page->url;
				$menu->target = $page->target;
				$menu->image = $page->addsimage;
				$menu->imageactive = $page->addsimageactive;
				$menu->imagehover = $page->addsimagehover;
				//echo 'sort_'.$item.'='.$sort['sort_'.$item];
				if (!empty($item['children'])){
					$this->setNodeMenu($menu, $item['children']);
				}
				break;						
			}
		}
	}
	return $node;
  }
  
  
  public function editContent(){
  	
	if (isset($_GET['confirmation'])) {
		$message = strval($_GET['confirmation']);
		$subject = (isset($_GET['subject'])) ? strval($_GET['subject']) : null;
		$status = (isset($_GET['status'])) ? strval($_GET['status']) : 'info';
		$hashcode = $_SESSION['confirmation'] = md5('removesection'.time().rand());
		include SE_ROOT.'admin/views/confirm_dialog.tpl';
		exit;
	}
	
	if (isset($_GET['getpagelist'])) {
		$pageslist = array();
		foreach ($this->data->pages as $page){
			$pagelist[] = array(
				'title'=>$page->title.' - '.strval($page['name']),
				'value'=>SE_MULTI_DIR.'/'.strval($page['name']).'/'
				);
			}
		echo json_clear_utf8(json_encode($pagelist));
		exit;
	}
	
	
	
	if (isset($_GET['filemanager']) && $this->editorAccess()) {
	    $default_language = $this->lang;
		$fm = $_GET['filemanager'];
		if (empty($fm)) $fm = 'dialog';
		if ($fm=='upload') {
			//file_put_contents(SE_ROOT.'admin/filemanager/post.txt',json_encode($_POST).json_encode($_FILES));
			include SE_ROOT.'admin/filemanager/upload.php';
		} elseif ($fm=='getframe') {
			$lang = $_GET['lang'];
			$field_id = $_GET['field_id'];
			include SE_ROOT.'admin/views/image_editor.tpl';
		} elseif ($fm=='uploader') {
			$fma = @$_GET['filemanageraction'];
			if (file_exists(SE_ROOT."admin/filemanager/uploader/{$fma}.php")) {
				include SE_ROOT."admin/filemanager/uploader/{$fma}.php";
			}
		} elseif (file_exists(SE_ROOT."admin/filemanager/{$fm}.php")) {
			include SE_ROOT."admin/filemanager/{$fm}.php";
		}
		exit;
	}
	
	$this->pagename = $this->data->pagename;
	$this->prj = $this->data->prj;
	$this->pages = $this->data->pages;
	$this->pagemenu = $this->data->pagemenu;
	$this->mainmenu = $this->data->mainmenu;
	$this->page = $this->data->page;
	$this->sections = $this->data->sections;
	

  if (!$this->editorAccess()) return;
	$this->setEditorProperty();
	
	
	if (isset($_GET['on_ajax_execute']))
	{
		/*if ($_GET['on_ajax_execute'] == 'sitevars'){
			$valuename = $_POST['name'];
			include_once SE_ROOT."admin/views/site_editor_".$valuename.".tpl";
			exit;
		}*/
		if (isset($_GET['partparams'])){
			$typename = $_POST['name'];
			require_once SE_ROOT."admin/views/part_editor.php";
			editor_getPartCont(null, 'params', $typename);
			exit;
		}

		$this->deletePage(); // Удалить страницу
		$this->deleteSection();  // Удалить раздел
		$this->deleteRecord(); // Удалить запись
		
		$this->reposPages(); // Перемещение разделов
		//$this->reposRecords(); // Перемещение записей
		
		if ($_POST['name']=='pagesave') {
			$hashcode = @$_POST['hashcode'];
			$check = @$_SESSION['confirmation'];
			if ($hashcode!=$check) {
				echo 'wrong check!';
				exit;
			}
			unset($_SESSION['confirmation']);
			$this->data->storeProject();
            header('location: ?'.time());
            exit;
		}
        elseif ($_POST['name']=='pagecancel') {
			$hashcode = @$_POST['hashcode'];
			$check = @$_SESSION['confirmation'];
			if ($hashcode!=$check) {
				echo 'wrong check!';
				exit;
			}
			unset($_SESSION['confirmation']);
			$this->data->returnProject();
			header('location: ?'.time());
			exit;
		}
        elseif (preg_match("/(edit|add)page(?:_)?([\w]*)/", $_POST['name'], $m)) {
			//var_dump($m);
			$oper = $m[1];
			$tpl = 'content';
			$editable = (isset($_POST['value'])) ? strval($_POST['value']) : '';
			if ($editable!='mainmenu' && $editable!='pagemenu') {
				$editable = 'mainmenu';
			}
			if (!empty($m[2])) $tpl = $m[2];
			if ($oper == 'add') {
				$this->clearPage();
				$this->pagename = ''; 
				$tpl = 'content';
			} else {
				foreach ($this->data->pages as $page){
				  if ($page['name'] == $this->data->pagename) {
				    break;
				  }
				}
			}
				//echo SE_ROOT."admin/views/page_editor_{$tpl}.tpl";
			include SE_ROOT."admin/views/page_editor_{$tpl}.tpl";
			exit;
		}
		
        elseif ($_POST['name']=='editvar') {
			list($typefield, $namefield) = explode('_', $_POST['value']);

			if ($typefield == 'page' && ($namefield == 'enteringtext' || $namefield == 'closingtext' || $namefield == 'title')){
				$textfield = strval($this->page->$namefield);
			} elseif ($typefield == 'site' && $namefield == 'sitelogotype') {
				$textfield = strval($this->prj->vars->sitelogotype);
				include SE_ROOT."admin/views/image_logo_editor.tpl";
				exit;
			} 
			else {
				$textfield = strval($this->$typefield->vars->$namefield);
			}
			include SE_ROOT."admin/views/vars_editor.tpl";
			exit;
	    }
		
        elseif ($_POST['name']=='addsection') {
			unset($section);
			$section->id = $this->data->getMaxSection(intval($_POST['value'])) + 1;
			include SE_ROOT."admin/views/part_editor.tpl";
			exit;
	    }
        elseif ($_POST['name']=='repossection'){
			$id_content = intval($_POST['value']);
			$sections = $this->data->getSectionContent($id_content);
			include SE_ROOT."admin/views/part_editor_position.tpl";
			exit;
	    }
        elseif ($_POST['name']=='editsection'){
			$id_section = intval($_POST['value']);
			$section = $this->data->getSection($id_section);
			if (isset($_GET['recorder'])) {
				$recorder = getRequest('recorder',1);
				$this->reposSectionRecords($section, $recorder);
			}
			include SE_ROOT."admin/views/part_editor.tpl";
			exit;
	    }
        elseif ($_POST['name']=='addrecord'){
			$id_section = intval($_POST['value']);
			$section = $this->data->getSection($id_section);
			unset($record);
			$record->id = 0;
			include SE_ROOT."admin/views/record_editor.tpl";
			exit;
	    }
        elseif ($_POST['name']=='editrecord'){
			list($id_section, $id_object) = explode('_', $_POST['value']);
			$section = $this->data->getSection($id_section);
			$record = $this->data->getObject($section, $id_object);
			include SE_ROOT."admin/views/record_editor.tpl";
			exit;
	    }
		
        elseif (preg_match("/editrecord([\w]+)/", $_POST['name'], $recm)){
			list($id_section, $id_object) = explode('_', $_POST['value']);
			$section = $this->data->getSection($id_section);
			$record = $this->data->getObject($section, $id_object);
			include dirname(__FILE__).'/../editor/record_editor_'.strtolower($recm[1]).'.tpl';
			exit;
		}

        elseif (preg_match("/showrecord([\w]+)/", $_POST['name'], $m)){
			list($id_section, $id_object) = explode('_', $_POST['value']);
			$section = $this->data->getSection($id_section);
			$record = $this->data->getObject($section, $id_object);
			if ($m[1] == 'image'){
				echo '<img class="objectImage" src="/'.SE_DIR.$record->image.'" alt="'.htmlspecialchars($record->image_alt).'">';
			} else 
			if ($m[1] == 'image_prev'){
				list($fname,$ext)  = explode('.', $record->image);
				echo '<img class="objectImage" src="/'.SE_DIR.$fname.'_prev.'.$ext.'" alt="'.htmlspecialchars($record->image_alt).'">';
			} else {
				echo replace_link($record->$m[1]);
			}
			exit;
		}

        elseif (preg_match("/editsection([\w]+)/u", $_POST['name'], $recm)){
			$id_section = intval($_POST['value']);
			$section = $this->data->getSection($id_section);
			include dirname(__FILE__).'/../editor/part_editor_'.strtolower($recm[1]).'.tpl';
			exit;
		}

        elseif (preg_match("/showsection([\w]+)/", $_POST['name'], $recm)){
			$id_section = intval($_POST['value']);
			$section = $this->data->getSection($id_section);
			if ($recm[1] == 'image'){
				echo '<img class="contentImage" src="/'.SE_DIR.$section->image.'?'.time().'" alt="'.htmlspecialchars($section->image_alt).'">';
			} else {
				echo replace_link($section->$recm[1]);
			}
			exit;
		}
	}
	
	if (isset($_POST['GoToEditContent']) || isset($_GET['jqueryform'])) {
		// Группа доступа
		if (isset($_POST['pageaccesslevel'])){
				$this->data->page->vars->groupslevel = intval($_POST['pageaccesslevel']);
		}
		if (isset($_POST['pageaccess'])){
				$valuesaccname = '';
				if ($this->data->page->vars->groupslevel >0)
				foreach($_POST['pageaccess'] as $valaccess){
					list(,$valaccess) = explode('-', $valaccess);
					$valuesaccname .= $valaccess.';';
				}
				$this->data->page->vars->groupsname = strval($valuesaccname);
		}

	
			$this->AddStorePage(); // Сохранить или добавить страницу
			$this->storeVars(); // Сохранить переменные
			$this->storeThisPage(); //Сохранить текущую страницу
			$this->storeContacts(); //Сохранить текущую страницу

			if ($_GET['jqueryform'] == 'sitelogo') {
					if (1){
						$newlogo = $_POST['logoimage'];
						if (strpos($newlogo,'/'.SE_DIR)===0) {
							$newlogo = preg_replace('~/'.SE_DIR.'~', '', $newlogo, 1);
						}
						if (file_exists(SE_ROOT.SE_DIR.$newlogo)) {
							$img = new SimpleImage(SE_ROOT.SE_DIR.$newlogo);
							$newwid = intval($_POST['logo_width']);
							$newhei = intval($_POST['logo_height']);
							$wid = ($setwid = $newwid>0) ? $newwid : $img->get_width();
							$hei = ($sethei = $newhei>0) ? $newhei : $img->get_height();
							if ($setwid || $sethei) {
								$img->best_fit($wid, $hei);
								$namefile = explode('.',$newlogo);
								$ext = array_pop($namefile);
								$namefile = implode('.',$namefile).'_logo.'.$ext;
								if (file_exists(SE_ROOT.SE_DIR.$namefile)) {
									unlink(SE_ROOT.SE_DIR.$namefile);
								}
								$img->save(SE_ROOT.SE_DIR.$namefile);
							} else {
								$namefile = $newlogo;
							}
							
							$this->data->prj->vars->sitelogotype = $namefile;
							$this->data->savePrj();
							echo '/'.SE_DIR.$namefile.'?'.time();
							exit;
						}
					}
			} else
				
			// Редактируем раздел
			if (isset($_GET['jqueryform']) && 
			(preg_match("/part([\w]+)/", $_GET['jqueryform'], $m)
			|| preg_match("/record([\w]+)/", $_GET['jqueryform'], $m))){
			//$_GET['jqueryform'] == 'partedit'){
				if ($m[1]=='position') {
					$this->reposSections(); // Перемещение разделов
				} else {
					if (isset($_POST['partid'])) {
						$id_section = intval($_POST['partid']);
						list($section, $newsection) = $this->setSection($id_section);
					} elseif (isset($_POST['contentid']) && isset($_POST['contenttype'])) {
						$content_id = intval($_POST['contentid']);
						$type = $_POST['contenttype'];
						$this->addSection($content_id, $type);
					}
				}
				//if ($_GET['jqueryform'] == 'partedit'){				
					//$add = (isset($_POST['recid']) || $newsection) ? true : false;
					echo $this->data->showSection($section, $newsection);
					exit;
				//}
				
			}
		// Сохраняем заголовок раздела
				// Сохраняем заголовок раздела
		if (isset($_GET['jqueryform']) && preg_match("/part([\w]+)/", $_GET['jqueryform'], $m) 
		&& $m[1]!='editphotos')
		{
			if ($m[1] == 'image'){
				echo '<img class="contentImage" src="/'.SE_DIR.$section->image.'?'.time().'" alt="'.htmlspecialchars($section->image_alt).'">';
			} else {
				echo replace_values($section->$m[1]);
			}
			exit;
		}
		if (isset($_GET['jqueryform']) && preg_match("/record([\w]+)/", $_GET['jqueryform'], $m))
		{
			if ($m[1] == 'image'){
				echo '<img class="objectImage" src="/'.SE_DIR . $record->image.'" alt="'.htmlspecialchars($record->image_alt).'">';
			} else 
			if ($m[1] == 'image_prev'){
				list($fname, $ext)  = explode('.', $record->image);
				echo '<img class="objectImage" src="/' . SE_DIR . $fname . '_prev.' . $ext.'" alt="'.htmlspecialchars($record->image_alt).'">';
			} else {
				echo replace_values($record->$m[1]);
			}
			exit;
		}
		//header('location: '.seMultiDir().'/'.getRequest('page').'/');
		exit;
	}
  }

  private function addSection($content_id = 0, $type = 'mtext'){
		$section_id = $this->data->getMaxSection($content_id) + 1;
		list($section, $newsection) = $this->setSection($section_id, $type);
		echo $this->setEditorLinks($section, $this->data->showSection($section, false));
		exit;
  }
  
  private function delMultiLink($text) {
     $se_sed = str_replace('/', '\/', SE_DIR);
     return preg_replace("/([\"\'])\/{$se_sed}(images|skin|files)\//uim", "$1$2/", stripslashes($text));
  }
  
  private function setSection($id_section, $type = 'mtext'){
		$newsection = false;
		if ($id_section){
				$section = $this->data->getSection($id_section);
		}
		if (!$section){ // Добавляем
			if ($id_section > 10000){
				$section = $this->data->prj->addChild('sections', '');
			} else {
				$section = $this->data->page->addChild('sections', '');
			}
			
			$section['name'] = $id_section;
			$section->id = $id_section;
			$section->type = $type;
			$newsection = true;
		}
					// Группа доступа
		if (isset($_POST['partaccesslevel'])){
			$section->accessgroup = intval($_POST['partaccesslevel']);
		}
		if (isset($_POST['partaccess'])){
			$valuesaccname = '';
			if ($section->accessgroup >0)
				foreach($_POST['partaccess'] as $valaccess){
					list(,$valaccess) = explode('-', $valaccess);
					$valuesaccname .= $valaccess.';';
				}
			$section->accessname = strval($valuesaccname);
		}
		if (isset($_POST['parametr'])){
			foreach($_POST['parametr'] as $nameparam=>$valparam){
				$section->parametrs->$nameparam = $valparam;
			}
		}
		$section->title_tag = (isRequest('parttitle_tag')) ? getRequest('parttitle_tag') : 'h3';

		if (isset($_POST['parttitle'])){
			$section->title = $this->delMultiLink($_POST['parttitle']);
		}
		if (isset($_POST['parttype'])){
			$section->type = $this->delMultiLink($_POST['parttype']);
		}
		if (isset($_POST['parttext'])){
			$section->text = $this->delMultiLink($_POST['parttext']);
		}
		if (isset($_POST['partimage_alt'])){
			$section->image_alt = stripslashes(getRequest('partimage_alt', 5));
		}  
		/*if (isset($_FILES['addrecimages']['tmp_name'])){
			if (intval($section->rpwidth_img) == 0 || intval($section->rwidth_img) == 0) {
				$section->rpwidth_img = 150;
				$section->rwidth_img = 800;
			}
			require_once dirname(__FILE__)."/seImages.class.php";
			$im = new seImages(getcwd().'/'.SE_DIR.'images', 'addrecimages');
			foreach($_FILES['addrecimages']['tmp_name'] as $id=>$value){
				if (is_uploaded_file($_FILES['addrecimages']['tmp_name'][$id])){
					$record = $this->addRecord($section);
					$record->title = stripslashes($_POST['recimage_alt'][$id]);
					$record->image_alt = stripslashes($_POST['recimage_alt'][$id]);
					$image = $im->set_image_prev($this->pagename . '_rec' . $section->id . '_' .
						$record->id.'_'.time(), $section->rpwidth_img, $section->rwidth_img, $id);
					if ($im->error=='' && !empty($image)){
						$record->image = 'images/'.$image;
					} else $record->image = '';
				}
			}
			unset($im);
		}*/
		/*if (is_uploaded_file($_FILES['partimages']['tmp_name'][0])){
			if (strval($section->image)!='') unlink(getcwd().'/'.SE_DIR.$section->image);
			require_once dirname(__FILE__)."/seImages.class.php";
			$im = new seImages(getcwd().'/'.SE_DIR.'images', 'partimages');
			$image = $im->set_image($this->pagename.'_part'.$section->id.'_'.time(), 300);
			if ($im->error=='' && !empty($image)){
				$section->image = 'images/'.$image;
			} else $section->image = '';
		} else {*/
			$partimage = '';
			if (isset($_POST['partimage']) && !empty($_POST['partimage'])){
				$partimage = stripslashes(getRequest('partimage', 5));
				if (isset($_POST['partimagesize']) && !strpos($partimage, '/'.$this->pagename.'_part'.$section->id.'_')){
					$path = getcwd().'/'.SE_DIR;
					if ($section->image) unlink($path . $section->image);
					$newimage = 'images/'.$this->pagename.'_part'.$section->id.'_'.time().'.'.getExtFile($partimage);
					thumbCreate($path . $newimage, $path . $partimage, 's', intval($_POST['partimagesize']));
					$partimage = $newimage;
					if (substr($partimage, 0, 1) == '/') $partimage = substr($partimage, 1);
				}
			}
			$section->image = $partimage;
		//}
		if (isset($_POST['recorder'])) {	
			if (is_array($_POST['recorder'])){
				$recordgroup = getRequest('recorder',1);
				//$recordgroup = explode(',', trim($_POST['recordgroup']));
			} else {
				$recordgroup = array();
			}
			if (!empty($section->objects)){
				$this->reposSectionRecords($section, $recordgroup);
			}
		}
		if (isset($_POST['recid'])){
			if (intval($_POST['recid']) < 1) {
				$record = $this->addRecord($section);
			} else {
				$id_object = intval($_POST['recid']);
				$record = $this->data->getObject($section, $id_object);
			}
			$record->title_tag = (isRequest('rectitle_tag')) ? getRequest('rectitle_tag') : 'h4';

			if (isset($_POST['recimage_alt'])){
				$record->image_alt = stripslashes(getRequest('recimage_alt', 5));
			}
			$record->visible = ($_POST['recvisible']=='on')?'on':'off';
			$resize_width = false;
			if (isset($_POST['rpwidth_img'])){
				if (getRequest('rpwidth_img', 1) != $section->rpwidth_img){
					$resize_width = true;
				}
				$section->rpwidth_img = getRequest('rpwidth_img', 1);
				if ($section->rpwidth_img < 1){
					$section->rpwidth_img = 100;
				}
			}
			if (isset($_POST['rwidth_img'])){
				if (getRequest('rwidth_img', 1) != $section->rwidth_img){
					$resize_width = true;
				}
				$section->rwidth_img = getRequest('rwidth_img', 1);
				if ($section->rwidth_img < 1){
					$section->rwidth_img = 500;
				}
			}
			/*if (is_uploaded_file($_FILES['recimages']['tmp_name'][0])){
				if (strval($record->image)!=''){
					unlink(getcwd().'/'.SE_DIR . $record->image);
				}
				$im = new seImages(getcwd().'/'.SE_DIR . 'images', 'recimages');
				$image = $im->set_image_prev($this->pagename . '_rec' . $section->id . '_' . $record->id.'_'.time(), 100, 300);
				if ($im->error=='' && !empty($image)){
					$record->image = 'images/'.$image;
				} else {
					$record->image = '';
				}
			} else*/
			if (isset($_POST['recordimage'])) {
				$recimage = trim(stripslashes(getRequest('recordimage', 5)));
				if (!empty($recimage) && (strval($record->image)!=$recimage || $resize_width) && file_exists(SE_ROOT.SE_DIR.$recimage)) {
					$record->image = $recimage;
					$full = delExtFile($recimage).'_full.'.getExtFile($recimage);
					$img = new SimpleImage(SE_ROOT.SE_DIR.$recimage);
					$thumb = new SimpleImage(SE_ROOT.SE_DIR.$recimage);
					if ($img->get_width()>(int)$section->rwidth_img) {
						$img->fit_to_width((int)$section->rwidth_img);
						$img->save(SE_ROOT.SE_DIR.$full);
						$record->image = $recimage = $full;
					}
					$prev = delExtFile($recimage).'_prev.'.getExtFile($recimage);
					if ($thumb->get_width()>(int)$section->rpwidth_img) {
						$thumb->fit_to_width((int)$section->rpwidth_img);
					}
					$thumb->save(SE_ROOT.SE_DIR.$prev);
				} else {
				    $record->image = $recimage;
				}
			}
			if (isset($_POST['recfield'])){
				$record->field = $this->delMultiLink($_POST['recfield']);
			}
			if (isset($_POST['rectitle'])){
				$record->title = $this->delMultiLink($_POST['rectitle']);
			}
			if (isset($_POST['recnote'])){
				$record->note = $this->delMultiLink($_POST['recnote']);
			}
			if (isset($_POST['rectext'])){
				$record->text = $this->delMultiLink($_POST['rectext']);
			}
			for ($i=1; $i<7; $i++)
				if (isset($_POST['rectext'.$i])){
					$f = 'text'.$i;
					$record->$f = $this->delMultiLink($_POST['rectext'.$i]);
				}
		} 
		if (intval($section->id) < 10000){
			$this->data->savePage();
		} else {
			$this->data->savePrj();
		}
		return array($section, $newsection); 
  }
  
  // Новая запись в разделе
  private function addRecord($section) {
	$id_object = 0;
	foreach($section->objects as $rec) {
		if ($id_object < intval($rec->id)) $id_object = intval($rec->id);
	} 
	$id_object++;
	// Получить у модуля параметр как вести себя со ссписком (вставлять или добавлять?)
	$rec_insert = intval($this->getModuleProperty(strval($section->type),'objectbefore'));
	if (count($section->objects) == 0) {
		$rec_insert = false; // если записей пока еще нет
	}
							
	if ($rec_insert){
		$insert = new SimpleXMLElement("<objects name=\"{$id_object}\"><id>{$id_object}</id></objects>");
		simplexml_insert_before($section, $insert, $section->objects[0]);
		$record = $this->data->getObject($section, $id_object);
	} else {
		$record = $section->addChild('objects', '');
		$record['name'] = $id_object;
	}
	$record->id = $id_object;
	return $record;
  }

  
  private function deleteRecord() {
  
	if (isset($_GET['jqueryform']) && $_GET['jqueryform']=='recordremove') {
		$hashcode = @$_POST['hashcode'];
		$check = @$_SESSION['confirmation'];
		if ($hashcode!=$check) {
			echo 'wrong check!';
			exit;
		}
		unset($_SESSION['confirmation']);
		list($id_section,$id_object) = explode('_',$_POST['value']);
		$section = $this->data->getSection($id_section);
		if ($id_object){
		    $id = 0;
			foreach($section->objects as  $val){
				if (strval($val['name']) == $id_object){
					unset($section->objects[$id]); break;
				}
				$id++;
			}
		if (intval($section->id) < 10000)
		{
			$this->data->savePage();
		} else {
			$this->data->savePrj();
		}
		echo $this->data->showSection($section, false);
		}
		exit;
    }
  }
  
  private function deleteSection() {
  
	if (isset($_GET['jqueryform']) && $_GET['jqueryform']=='partremove') {
		//var_dump($_POST['value']);
		$hashcode = @$_POST['hashcode'];
		$check = @$_SESSION['confirmation'];
		if ($hashcode!=$check) {
			echo 'wrong check!';
			exit;
		}
		unset($_SESSION['confirmation']);
		$section_id = intval($_POST['value']);
		if ($section_id) {
		    $section = ($section_id < 10000) ? $this->data->page->sections : $this->data->prj->sections;
			$id = 0;
			foreach($section as  $val){
				if (strval($val['name']) == $section_id){
					unset($section[$id]); break;
				}
				$id++;
			}
			if (intval($section_id) < 10000)
			{
				$this->data->savePage();
			} else {
				$this->data->savePrj();
			}
			echo "ok";
		}
		exit;
    }
  }

  private function deletePage(){
  
	if (isset($_GET['on_ajax_execute']) && $_POST['name']=='removepage') {
		$page_name = $_POST['value'];
		if ($page_name == 'home') return;
		$id = 0;
		if ($page_name != ''){
			foreach($this->data->pages->page as  $val){
				if (strval($val['name']) == $page_name){
					unset($this->data->pages->page[$id]); break;
				}
				$id++;
			}
			$this->data->savePages();
			if (file_exists(SE_ROOT.'projects/' . SE_DIR . 'edit/pages/'.$page_name.'.xml')){
				unlink(SE_ROOT.'projects/' . SE_DIR . 'edit/pages/'.$page_name.'.xml');
			}
			if ($page_name != ''){
				$this->deleteMenus($this->pagemenu->item, $page_name);
				$this->deleteMenus($this->mainmenu->item, $page_name);
				file_put_contents(SE_ROOT.'projects/' . SE_DIR . 'edit/pagemenu.xml', preg_replace("/[\s]{1,}[\r\n]/","", $this->pagemenu->saveXML()));
				file_put_contents(SE_ROOT.'projects/' . SE_DIR . 'edit/mainmenu.xml', preg_replace("/[\s]{1,}[\r\n]/","", $this->mainmenu->saveXML()));
				$fp = fopen(SE_ROOT.'projects/' . SE_DIR . 'edit/pages/'.$page_name.'.del', "w+");
				fclose($fp);
			}

		}
		header('location: '.seMultiDir().'/home/');
		exit;
    }
  }

  private function CheckMenus(){
		if (isset($_POST['checkpagemenu']) && $_POST['checkpagemenu']=='on'){
				$this->setMenuItem($this->data->getPageName(), true, false);
		} else 	$this->setMenuItem($this->data->getPageName(), false, false);

		if (isset($_POST['checkmainmenu']) && $_POST['checkmainmenu']=='on'){
			$this->setMenuItem($this->data->getPageName(), true, true);
		} else 	$this->setMenuItem($this->data->getPageName(), false, true);
  
  }
  
  private function clearPage(){
			$page->indexes = 0;
			$this->data->page->title = '';
			$this->data->page->style = '';
            $this->data->page->description = '';
            $this->data->page->keywords = '';
            $this->data->page->head = '';

                //if (isset($_POST['css'])){
            	//    $this->data->page->css = stripslashes($_POST['css']);
                //}
            $this->data->page->vars->localjavascripthead = '';
            $this->data->page->titlepage = stripslashes($title);
            $this->data->page->enteringtext = '';
            $this->data->page->closingtext = '';
  
  }
  
  private function updatePage(){
		//if (isset($_POST['title'])){
        //    $this->data->page->title = stripslashes($_POST['title']);
        //}

		if (isset($_POST['style'])){
            $this->data->page->style = stripslashes($_POST['style']);
        }

        if (isset($_POST['description'])){
            $this->data->page->description = stripslashes($_POST['description']);
        }
        if (isset($_POST['keywords'])){
            $this->data->page->keywords = stripslashes($_POST['keywords']);
        }

        if (isset($_POST['pagehead'])){
            $this->data->page->head = stripslashes($_POST['pagehead']);
        }
        if (isset($_POST['css'])){
            $this->data->page->css = stripslashes($_POST['css']);
        }
        if (isset($_POST['localjavascripthead'])){
            $this->data->page->vars->localjavascripthead = stripslashes($_POST['localjavascripthead']);
        }
        if (isset($_POST['titlepage'])){
            $this->data->page->titlepage = stripslashes($_POST['titlepage']);
        }
        if (isset($_POST['enteringtext'])){
            $this->data->page->enteringtext = $this->delMultiLink($_POST['enteringtext']);
        }
        if (isset($_POST['closingtext'])){
            $this->data->page->closingtext = $this->delMultiLink($_POST['closingtext']);
        }
  
		if ($this->data->page->titlepage == '') { 
			$this->data->page->titlepage = stripslashes($_POST['title']);
		}
  }
  
  private function AddStorePage(){
    if (isset($_POST['namepage']) && preg_match("/^[a-z0-9\-]+$/", $_POST['namepage'])) {
        $newpage = getRequest('namepage');
        $urlpage = getRequest('urlpage', 3);
        $this->data->pagename = $newpage;
        if (isset($_POST['urlpage'])) {
            $this->data->page->url = $urlpage;
        }
        $oldpage = '';
        $title = stripslashes($_POST['title']);
        $this->data->page->title = $title;

        if (isset($_POST['domainname']) && $this->data->prj->domainname!= stripslashes($_POST['domainname'])){
            $this->data->prj->domainname = stripslashes($_POST['domainname']);
            $this->data->savePrj();
        }

        if (!empty($_POST['thisnamepage'])) {
            $this->CheckMenus();
            $this->updatePage();
            $oldpage = $_POST['thisnamepage'];
            foreach($this->data->pages->page as $pages) {
                if ($pages['name'] == $oldpage) {
                    $pages['name'] = $newpage;
                    $pages->title = $title;
                    if ($urlpage != '') {
                        $pages->url = $urlpage;
                    } else {
                        unset($pages->url);
                    }
                    $pages->indexes = ($_POST['indexes'] == 'on') ? '1' : '0';
                    $pages->priority = stripslashes($_POST['priority']);
                    $pages->addsimage = getRequest('addsimage', 4);
                    $pages->mainimage = getRequest('mainimage', 4);
                    $this->updateImageMenu($oldpage, $pages->addsimage, $this->pagemenu->item);
                    $this->updateImageMenu($oldpage, $pages->mainimage, $this->mainmenu->item);
                    $this->data->savePages();
                    if ($oldpage != $newpage) {
                        // Переименуем страницу
                        if (file_exists(SE_ROOT.'projects/' . SE_DIR . 'edit/pages/'.$newpage.'.del')) {
                             unlink(SE_ROOT.'projects/' . SE_DIR . 'edit/pages/'.$newpage.'.del');
                        }
                        rename(SE_ROOT.'projects/' . SE_DIR . 'edit/pages/'.$oldpage.'.xml', SE_ROOT.'projects/' . SE_DIR . 'edit/pages/'.$newpage.'.xml');
                        $fnew = fopen(SE_ROOT.'projects/' . SE_DIR . 'edit/pages/'.$oldpage.'.del',"w+");
                        fclose($fnew);
                        // нужно отметить, что страница была переименована и в дальнейшем, чтобы из рабочей папки удалить ее
                        if ($oldpage!='') {
                            $this->setMenus($this->pagemenu->item, $oldpage, $newpage, $title, trim($urlpage));
                            $this->setMenus($this->mainmenu->item, $oldpage, $newpage, $title, trim($urlpage));
                            break;
                        }
                    }
                }
            }
        } else {
            // Создаем страницу
            $pages = $this->data->pages->addChild('page', '');
            $pages['name'] = $newpage;
            $pages->title = $title;
            $pages->skin = 'default';
            $oldpage = $newpage;
            $this->CheckMenus(); // Добавить в меню
            $this->updatePage(true);
            // Группа доступа
            if (isset($_POST['pageaccesslevel'])){
                $pages->groupslevel = intval($_POST['pageaccesslevel']);
            }
            if (isset($_POST['pageaccess'])){
                $valuesaccname = '';
                if ($pages->groupslevel >0)
                    foreach($_POST['partaccess'] as $valaccess){
                        list(,$valaccess) = explode('-', $valaccess);
                        $valuesaccname .= $valaccess.';';
                    }
                    $pages->groupsname = strval($valuesaccname);
            }
            $pages->indexes = ($_POST['indexes'] == 'on') ? '1' : '0';
            $pages->priority = stripslashes($_POST['priority']);
            $pages->addsimage = getRequest('addsimage', 4);
            $pages->mainimage = getRequest('mainimage', 4);
            $this->updateImageMenu($oldpage, $pages->addsimage, $this->pagemenu->item);
            $this->updateImageMenu($oldpage, $pages->mainimage, $this->mainmenu->item);
            $pages->level = 1;
            $this->data->pagename = $pages['name'];
            if (file_exists(SE_ROOT.'projects/' . SE_DIR . 'edit/pages/'.$this->data->pagename.'.del')){
                unlink(SE_ROOT.'projects/' . SE_DIR . 'edit/pages/'.$newpage.'.del');
            }
            unset($this->data->page->sections);
            $this->data->savePages();
        }
        // Сохраняем меню
        if ($oldpage!='') {
            $this->setMenus($this->pagemenu->item, $oldpage, $newpage, $title, $urlpage);
            $this->setMenus($this->mainmenu->item, $oldpage, $newpage, $title, $urlpage);
        }
        file_put_contents(SE_ROOT.'projects/' . SE_DIR . 'edit/pagemenu.xml', preg_replace("/[\s]{1,}[\r\n]/","", $this->pagemenu->saveXML()));
        file_put_contents(SE_ROOT.'projects/' . SE_DIR . 'edit/mainmenu.xml', preg_replace("/[\s]{1,}[\r\n]/","", $this->mainmenu->saveXML()));
        $this->data->savePage(); // Сохраняем изменения в странице
        $_SESSION['editor_page'] = $newpage;
        header('location: '.seMultiDir().'/'.$newpage.'/');
        exit;
    }
  }

  
  //$this->pagemenu->item
  
  private function updateImageMenu($page, $image, $items) {
    foreach ($items as $menu) {
        if (strval($menu['name']) == $page) {
            $menu->image = $image;
            if ($menu->item != null) {
                $this->updateImageMenu($page, $image, $menu->item);
            }
        }
    }
  }
  
  private function setMenus($items, $oldpage, $newpage, $title, $url = ''){
	foreach($items as $menu){
		if (strval($menu['name']) == $oldpage){
			$menu['name'] = $newpage;
			$menu->name = $newpage;
			$menu->title = $title;
			if ($url=='') $menu->url = '/'.$newpage.'/';
			else $menu->url = $url;
		} elseif ($menu->item != null){
			$this->setMenus($menu->item, $oldpage, $newpage, $title, $url);
		}
	}
  }

  private function deleteMenus($items, $oldpage){
    $id = 0;
    foreach($items as $menu){
        if (strval($menu['name']) == trim($oldpage)){
            unset($items[$id]);
            break;
        } elseif ($menu->item != null){
            $this->deleteMenus($menu->item, $oldpage);
        }
        $id ++;
    }
  }
  
  // Сохраняем переменные
  private function storeVars(){
	if (isset($_GET['jqueryform']) && $_GET['jqueryform'] == 'sitevars'){
		list($typefield,$namefield) = explode('_', $_POST['value']);
		if (!empty($namefield) && !empty($typefield)){
			if ($namefield == 'enteringtext' || $namefield == 'closingtext' || $namefield == 'title'){
				$this->data->page->$namefield = $this->delMultiLink($_POST['newvalue']);
			} else {
				$this->data->$typefield->vars->$namefield = $this->delMultiLink($_POST['newvalue']);
			}
			if ($typefield != 'prj'){
				$this->data->savePage();
			} else {
				$this->data->savePrj();
			}
			if ($typefield == 'page' && ($namefield == 'enteringtext' || $namefield == 'closingtext' || $namefield == 'title')){
				echo replace_values($this->data->page->$namefield);
			} else {
				echo replace_values($this->data->$typefield->vars->$namefield);
			}
		}
		exit;
	}
  }
  
  private function storeContacts(){
	if (isset($_GET['jqueryform']) && $_GET['jqueryform'] == 'sitecontacts'){
		//var_dump($_POST);
  		$this->data->prj->vars->sitecompany = stripslashes($_POST['sitecompany']);
		$this->data->prj->vars->sitesmallcompany = stripslashes($_POST['sitesmallcompany']);
		$this->data->prj->vars->sitemail = stripslashes($_POST['sitemail']);
		$this->data->prj->vars->sitephone = stripslashes($_POST['sitephone']);
		$this->data->prj->vars->sitefax = stripslashes($_POST['sitefax']);
		$this->data->prj->vars->sitepostcode = stripslashes($_POST['sitepostcode']);
		$this->data->prj->vars->siteregion = stripslashes($_POST['siteregion']);
		$this->data->prj->vars->sitelocality = stripslashes($_POST['sitelocality']);
		$this->data->prj->vars->siteaddr = stripslashes($_POST['siteaddr']);
		$this->data->prj->vars->adminrefer = stripslashes($_POST['adminrefer']);		
		//$this->data->prj->sitetitle>Туризм в Испании</sitetitle>
		//$this->data->prj->sitesubtitle>Отдыхай и путешествуй</sitesubtitle>
		$this->data->prj->vars->adminmail = stripslashes($_POST['adminmail']);
		$this->data->prj->vars->sitelicense = stripslashes($_POST['sitelicense']);
		$this->data->savePrj();
	}
  }

  
  private function storeThisPage(){
	if (isset($_GET['jqueryform']) && $_GET['jqueryform'] == "editpage"){	
		
				// Группа доступа
				if (isset($_POST['pageaccesslevel'])){
					echo $this->data->page->vars->groupslevel = intval($_POST['pageaccesslevel']);
				}
				if (isset($_POST['pageaccess'])){
					$valuesaccname = '';
					if ($this->data->page->vars->groupslevel >0)
					foreach($_POST['partaccess'] as $valaccess){
						list(,$valaccess) = explode('-', $valaccess);
						$valuesaccname .= $valaccess.';';
					}
					$this->data->page->vars->groupsname = strval($valuesaccname);
				}

				if (isset($_POST['title'])){
            	    $this->data->page->title = stripslashes($_POST['title']);
                }

                if (isset($_POST['style'])){
            	    $this->data->page->style = stripslashes($_POST['style']);
                }

                if (isset($_POST['description'])){
            	    $this->data->page->description = stripslashes($_POST['description']);
                }
                if (isset($_POST['keywords'])){
            	    $this->data->page->keywords = stripslashes($_POST['keywords']);
                }

                if (isset($_POST['pagehead'])){
            	    $this->data->page->head = stripslashes($_POST['pagehead']);
                }
                if (isset($_POST['css'])){
            	    $this->data->page->css = stripslashes($_POST['css']);
                }
                if (isset($_POST['localjavascripthead'])){
            	    $this->data->page->vars->localjavascripthead = stripslashes($_POST['localjavascripthead']);
                }
                if (isset($_POST['titlepage'])){
            	    $this->data->page->titlepage = stripslashes($_POST['titlepage']);
                }
			
                if (isset($_POST['enteringtext'])){
            	    $this->data->page->enteringtext = stripslashes($_POST['enteringtext']);
                }
                if (isset($_POST['closingtext'])){
            	    $this->data->page->closingtext = stripslashes($_POST['closingtext']);
                }						
								
				$this->data->savePage();
				echo "ok";
				exit;
	}  
  }
  
  private function reposPages(){
		if (isset($_GET['sortablepagemenu'])){

			$arr1 = $_POST['pagemenu'];
			$arr2 = $_POST['mainmenu'];
			
			foreach($arr2 as $k=>$p) {
				if (strpos($p,'item_')===0) {
					$p = substr($p, 5);
				}
				$arr2[$k] = array('id'=>$p);
			}

			unset($this->pagemenu->item, $this->mainmenu->item);
			$this->pagemenu = $this->setNodeMenu($this->pagemenu, $arr1);
			$this->mainmenu = $this->setNodeMenu($this->mainmenu, $arr2);
			file_put_contents(SE_ROOT.'projects/' . SE_DIR . 'edit/pagemenu.xml', preg_replace("/[\s]{1,}[\r\n]/","", $this->pagemenu->saveXML()));
			file_put_contents(SE_ROOT.'projects/' . SE_DIR . 'edit/mainmenu.xml', preg_replace("/[\s]{1,}[\r\n]/","", $this->mainmenu->saveXML()));
			$menu = new seMenuExecute();
			echo $menu->getPageMenu().'>>!!<<'.$menu->getMainMenu();
			//print_r($this->pagemenu);
			exit;
		}
  }  
  
  private function setMenuItem($name, $mselect = true, $is_mainMenu = true){
	//error_reporting(E_ALL);
	$name_menu =($is_mainMenu) ? 'mainmenu' : 'pagemenu';
		$nodes = $this->data->$name_menu;
		if (!$mselect){
				$this->deleteMenus($nodes->item, $name);
				return;
		}
		
		foreach($nodes->item as $menu){
			if (strval($menu['name']) == $name){
				return;
			}
		}

		foreach($this->data->pages as $page){
			if (strval($page['name']) == trim($name)){
				$menu = $nodes->addChild('item', '');
				$menu['name'] = $name;
				$menu->visible = 1;
				$menu->name = $name;
				$menu->title = $page->title;
				if (empty($page->url)) $page->url = '/'.$name.'/';
				$menu->url = $page->url;
				$menu->target = $page->target;
				if ($is_mainMenu){
					$menu->image = $page->addsimage;
					$menu->imageactive = $page->addsimageactive;
					$menu->imagehover = $page->addsimagehover;
				} else {
					$menu->image = $page->mainimage;
					$menu->imageactive = $page->mainimageactive;
					$menu->imagehover = $page->mainimagehover;
				}
				break;						
			}
		}

		file_put_contents(SE_ROOT.'projects/' . SE_DIR . 'edit/'.$name_menu.'.xml', preg_replace("/[\s]{1,}[\r\n]/","", $nodes->saveXML()));  
  }
  
  
  // Перетаскивание записей
  private function reposRecords(){
		if (isset($_GET['sortablerec']) && !$_SESSION['siteediteditor']){
			$arr = explode('&', $_POST['data']);
			$rsort = $recarr = array();
			foreach($arr as $value){
				list($name,$value) = explode('=',$value);
				$sortname = str_replace('[]','', $name);
				if (strpos($name,'rsort') !== false) {
					$rsort[$sortname][] = $value;
					if (!in_array($sortname, $recarr)){
						$recarr[] = $sortname;
					}
				}
			}
			$isGlobal = $isLocal = false;
			foreach($recarr as $id_section){
				$id_section = substr($id_section, 5);
				if ($id_section > 10000) $isGlobal = true;
				else $isLocal = true;
				
				$section = $this->data->getSection($id_section);
				foreach($rsort as $name=>$sortlist){
				    if (substr($name,5) != $id_section) continue;
					$this->reposSectionRecords($section, $sortlist);
				}
			}
			if ($isLocal) $this->data->savePage();
			if ($isGlobal) $this->data->savePrj();
		}
  }

   // Перетаскивание записей
  private function reposSectionRecords($section, $records){
			$clonerecord = clone($section->objects);
			unset($section->objects);
			foreach($records as $newrec_id){
					$record = $section->addChild('objects', '');
					foreach($clonerecord as $pobj){
						if ($newrec_id == strval($pobj['name'])){
							$record['name'] = strval($newrec_id);
							@append_simplexml($record, $pobj);
							break;
						}
					}
			}
			unset($clonerecord);
  } 
  
  // Перетаскивание разделов
  private function reposSections(){
		if (isset($_POST['sectionorder']) && is_array($_POST['sectionorder'])) {
			$content_id = getRequest('value',1);
			$type = ($content_id>=100) ? 'prj' : 'page';
			
			$sort = getRequest('sectionorder',1);	
				
			$clonesection = clone($this->data->$type->sections);
            // Восстанавливаем недостающие секции
			foreach($this->data->$type->sections as $sect){
				$sect_id = intval($sect['name']);
				if (in_array($sect_id, $sort)) continue;
				$sort[] = $sect_id;
			}
	
			unset($this->data->$type->sections);

			foreach($sort as $sect_id) {
					$newsection = $this->data->$type->addChild('sections', '');
					foreach($clonesection as $psection){
						if ($sect_id == strval($psection['name'])){
							$newsection['name'] = strval($sect_id);
							@append_simplexml($newsection, $psection);
							break;
						}
					}
			}

			unset($clonesection);
			
			if ($type=='page') {
				$this->data->savePage();
			}
			else {
				$this->data->savePrj();
			}
			
			foreach ($this->data->getSectionContent($content_id, $this->data->$type->sections) as $section) {
				//var_dump($section->id);
				echo $this->data->showSection($section);
			}
			exit;
		}
  }
	
	/*protected $btn_defaults = array(
		'event'=>null,
		'subject'=>null,
		'id'=>null,
		'img'=>null,
		'alt'=>null,
		'add'=>null,
		'force_text'=>false,
		'size'=>'xs',
		'type'=>'default'
	);*/
	
public function getBtn($event = null, $subject = null, $id = null, $img = null, $alt = null, $add = null, $force_text = false, $size = 'xs', $type = 'default') {
	
	$event = 	(is_null($event)) 	? '' : " data-event=\"$event\"";
	$subject = 	(is_null($subject)) ? '' : " data-subject=\"$subject\"";
	$id = 		(is_null($id)) 		? '' : " data-id=\"$id\"";
	$content = 	(is_null($img))		? $alt : "<img class=\"glyphicon\" src=\"$img\" alt=\"$alt\">";
	(!is_null($img) && $force_text) ? $content.=$alt : null;
	$add = 		(is_null($add))		? '' : ' '.$add;
	return "<button class=\"btn btn-{$type} btn-{$size}\"{$event}{$subject}{$id}{$add}>{$content}</button>";
	
}

public function getBtnGroup($buttons, $add) {
	$add = 		(is_null($add))		? '' : ' '.$add;
	return "<div class=\"btn-group\"{$add}>".implode('',$buttons)."</div>";
}

  // Подключаем библиотеки javaScript редактора
  private function setEditorProperty(){
    if (!$this->editorAccess()) return;
	ob_start();
	include SE_ROOT.'admin/frameheader.php';
	$header = ob_get_contents();
	ob_end_clean();

	if (!empty($header)) {
		$this->data->getHeader($header, null);
	}
  }
  
  public function linkAddRecord($section_id){}

	public function hasHiddenTags($text) {
		return (strpos($text,'<script')!==false || strpos($text,'<link')!==false || strpos($text,'<style')!==false);
	}

  public function groupWrapper($content_id, $text){
	$wrapp = '';
	if (!$this->editorAccess() || !empty($_SESSION['siteediteditor']) || ($this->data->req->object || $this->data->req->sub)) {
		return $text;
	}
	$fl_count = ($this->data->getMaxSection($content_id) - ($content_id * 1000));
	
	$wrapcode = ($content_id < 100) ? 'content'.$content_id : 'global'.$content_id;
	
	$img = ($content_id < 100) ? '/admin/assets/icons/add_content.png' : '/admin/assets/icons/add_gcontent.gif';
	
	$buttons = array(
		$this->getBtn('frame_add','section',$content_id,$img,$this->title_icon_addsection, null ,false,'xs','warning'),
		$this->getBtn('frame_position','section',$content_id,'/admin/assets/icons/16x16/menu_item.png',$this->title_icon_addsection, null ,false,'xs','warning')
	);
	
	$btn = $this->getBtnGroup($buttons, 'data-toolbar="top" style="display:none"');
	$wrapp .= $btn;
	$result = $btn.$text;
	$test_text = strip_tags($text);

	if (utf8_strlen($test_text)>2500) {
		$lastbtn = $this->getBtn('frame_add','section',$content_id,$img,$this->title_icon_addsection, 'data-toolbar="bottom" style="display:none;"',false,'xs','warning');
		$result = $lastbtn.$result;
	}

	return "<div data-wrap=\"{$wrapcode}\">{$result}</div>";
  }

  public function sectWrapper($section_id, $text){
	if (!$this->editorAccess() || $_SESSION['siteediteditor']) return $text;
	    return "<div class=\"sectWrapper\" id=\"rsort{$section_id}\">".linkAddRecord($section_id).$text.'</div>';
  }
  
  private function getContentEditLink($section){
  	//$aimg = ($id_sect < 100000) ? '/admin/assets/icons/add_content.png' : '/admin/assets/icons/add_gcontent.gif';
    $img = ($section->id < 100000) ? "/admin/assets/icons/edit_content.png" : "/admin/assets/icons/edit_gcontent.gif";
	$rimg = '/admin/assets/icons/16x16/cross.png';
    $content_id = $section->id / 1000;
	$buttons = array(
		//$this->getBtn('frame_add','section',$content_id,$aimg,$this->title_icon_addsection),
		$this->getBtn('frame_edit','section',$section->id,$img,$this->title_icon_edsection,null,false,'xs','warning'),
		$this->getBtn('frame_remove','section',$section->id,$rimg,'remove',null,false,'xs','warning')
	);
	
	// Определяем, можно ли добавлять записи
	if (trim($section->type) == '') $section->type = 'mtext';
	if ($this->getModuleOption(trim($section->type))) {
		$buttons[] = $this->getBtn('frame_add','record',$section->id,'/admin/assets/icons/add_object.png',$this->title_icon_addrecord,null,false,'xs','success');
		//$buttons[] = $this->getBtn('frame_position','record',$section->id,'/admin/assets/icons/16x16/menu_item.png',$this->title_icon_addsection, null ,false,'xs','success');
	}
	
	return $this->getBtnGroup($buttons, 'data-toolbar="left" style="display:none;"');
	//return "<img class=\"cico-e\" src=\"{$img}\" alt=\"Изменить раздел\"> Изменить {$id_sect}";  
  }
  
  public function setEditorLinks($section, $text){
	if (!$this->editorAccess() || $_SESSION['siteediteditor']) return $text;
	//$text = "<div class=\"groupItem\" id=\"group_{$section->id}\">".$this->addClassSection($section, $text).'</div>';
	$text = $this->addClassSection($section, $text);
    return $text;
  }

  public function addClassSection($section, $text, $add = true)
  {
	if (!$this->editorAccess()) return $text;	
	//  ui-widget ui-widget-content ui-helper-clearfix ui-corner-all
	if ($add){
		$text = "<div data-content=\"$section->id\">". $this->getContentEditLink($section).$text.'</div>'; //<div class=\"bgcshadow\"></div>
	}	
	
	//$text = str_replace('class="contentTitle"', "class=\"contentTitle\" id=\"edit_contentTitle_{$section->id}\"", $text);
	//$text = str_replace('class="contentText"', "class=\"contentText\" id=\"edit_contentText_{$section->id}\"", $text);
	/*return  preg_replace("/(<img.+?class=\"contentImage\".+?>)/uim", "<div class=\"contentImage\" id=\"edit_contentImage_{$section->id}\">$1</div>", $text);*/
	return $text;
  }
  
  public function addEditSection($section, $text){
    if (!$this->editorAccess() || $_SESSION['siteediteditor']) return $text;
    return $text;//'<div class="groupItem" id="group_'.$section->id.'">'. $text . '</div>';
  }
 
  public function editItemRecord($section_id, $record_id){
      if (!$this->editorAccess() || $_SESSION['siteediteditor']) return '';
      //return " id=\"se-edit-record-{$record_id}\"";
      return '';
  }
  
  public function linkEditRecord($section_id, $record_id, $type){
      if (!$this->editorAccess() || $_SESSION['siteediteditor']) return '';
	  if ('' == $type) {
	  	$buttons = array(
			$this->getBtn('frame_edit','record',$section_id.'_'.$record_id,'/admin/assets/icons/edit_object.png',$this->title_icon_edrecord,null,false,'xs','success'),
		  	$this->getBtn('frame_remove','record',$section_id.'_'.$record_id,'/admin/assets/icons/16x16/cross.png','remove',null,false,'xs','success')
		);
		$btngr = $this->getBtnGroup($buttons, 'data-toolbar="left" style="display:none;"');
	  return $btngr;
	   
	   /*return "<div class=\"itemRecordHeader editallbox\">
	  <span class=\"se-edit-content-head\">
	  <span class=\"se-edit-record\" data-event=\"frame_edit\" data-subject=\"record\" data-id=\"{$section_id}_{$record_id}\">
	  	<img class=\"cico-e\" src=\"/admin/assets/icons/edit_object.png\" title=\"{$this->title_icon_edrecord}\" alt=\"{$this->title_icon_edrecord}\">
	  </span> 
	  
	  </div>";*/ //<u>Изменить</u>

	  //$text = "<div style=\"width:100%;\" class=\"itemRecordHeader\">";
	  //return $text."<img class=\"se-edit-record\" id=\"item-record-{$record_id}\" src=\"/admin/assets/icons/edit_object.png\"></div>";
	  /*return $text."<img id=\"edit_record_{$record_id}\" onClick=\"getEditWindow('editrecord','{$section_id}_{$record_id}');\" src=\"/admin/assets/icons/edit_object.png\">*/
	  } else {
        return " id=\"item-record{$type}-{$record_id}\"";
	  }
  }

  public function getModules(){
	$groups = array();
	if (!SE_ALL_SERVICES && file_exists(SE_ROOT.'/system/service.xml'))
	{
		$modules = simplexml_load_file(SE_ROOT.'/system/service.xml');	
		foreach($modules->module as $module){			
			if (strval($module) == '1'){
			  $root = getcwd() . $this->data->getFolderModule($module['name']) . '/' . strval($module['name']).'/property/types_'.$this->lang.'.xml';
			  if (file_exists($root)){
				$property = simplexml_load_file($root);
			    $groups[strval($property->group)][] = array('name'=>strval($module['name']),'title'=>strval($property->name));
			  } else
			    $groups['Base'][] = array('name'=>$module['name'],'title'=>$module['name']);
			}
		}
	} else {
		$d=opendir(SE_ROOT.'/lib/modules');
		$modules = array();
		while(($f=readdir($d))!==false) {
		if ($f=='.'||$f=='..') continue;
			if (strpos($f, 'mdl_')!==false && getExtFile($f) == 'php') {
				$f = delExtFile($f);
				$modules[] = substr($f, 4, strlen($f) - 4);
				
			}
		}
		closedir($d);
		foreach($modules as $module) {
			  $root = getcwd() . $this->data->getFolderModule($module) . '/' . strval($module).'/property/types_'.$this->lang.'.xml';
			  if (file_exists($root)) {
				$property = simplexml_load_file($root);
			    $groups[strval($property->group)][] = array('name'=>strval($module),'title'=>strval($property->name));
			  } else {
			    $groups['Base'][] = array('name'=>$module,'title'=>$module);
			  }
		}
	
	}
	return $groups;
  }
  
  // Получим список модулей
  public function getModuleList($type_name){

		foreach($this->getModules() as $gname=>$group){
			echo '<optgroup label="'.$gname.'">'."\r\n";
			foreach($group as $opt){
				$selected = ($type_name == $opt['name']) ? ' selected' : '';
				echo '<option value="'.$opt['name'].'"'.$selected.'>'.$opt['title']."</option>\r\n";
			}
			echo '</optgroup>'."\r\n";
		}
  }

  public function getModuleOption($type_name, $param = 'interface'){
        //$type_name = $section->type;
		$root = getcwd() . $this->data->getFolderModule($type_name) . '/' . strval($type_name).'/property/option.xml';
 		if (file_exists($root)){
				$res = simplexml_load_file($root);
				//echo '!'.$res->$param.'!';
				return intval($res->$param);
		}
 }
  
  public function getModuleInterface($type_name, $name, $type){
		$root = getcwd() . $this->data->getFolderModule($type_name) . '/' . strval($type_name).'/interface/structure.xml';
		$fields = array();
		if (file_exists($root)){
				$res = simplexml_load_file($root);
				
				$typelist = explode(',', $type);
				foreach ($res->objects->field as $field){
					if (strval($field['name']) == $name && in_array(strval($field['type']),$typelist))
						$fields[] = $field;
				}
				if (!empty($fields))
					return $fields;
				else return false;
		}
  }

  public function getModuleProperty($type_name, $name){
		$root = getcwd() . $this->data->getFolderModule($type_name) . '/' . strval($type_name).'/property/option.xml';
		$fields = array();
		if (file_exists($root)){
				$res = simplexml_load_file($root);
				return $res->$name;
		}
  }
  
  public function recordsWrapperStart($id){
    echo '<div data-content="records'.$id.'">';//.$this->getBtn('frame_add','record',$id,'/admin/assets/icons/add_object.png',$this->title_icon_addrecord,'data-toolbar="top" style="display:none;"');
  }

  public function recordsWrapperEnd(){
	echo '</div>';
  }

  public function editorAddPhotos($section){
	if (file_exists(SE_ROOT.'/admin/views/record_editor_images.tpl') && $this->editorAccess()){
	    include SE_ROOT.'/admin/views/record_editor_images.tpl';
	}
  }
  
  public function getTextLanguage($word, $key='ed', $key_only = false){
    if (!empty($this->langmess[$key.'_'.$word]))
		return $this->langmess[$key.'_'.$word];
    if (!$key_only && !empty($this->langmess[$word]))
        return $this->langmess[$word];
    else return $word;
  }
}
?>