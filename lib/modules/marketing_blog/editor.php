<?php

class BlogEditor {
     
     public function __construct($userFolder) {
         self::userFolder($userFolder);
         if (isset($_GET['filemanager']) && $_SESSION['editor_images_access']) {
                $default_language = substr(se_getLang(),0,2);
                $fm = $_GET['filemanager'];   
                if (empty($fm)) 
                    $fm = 'dialog';
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
     }

     public function editorAccess() {
         if ($_SESSION['editor_images_access']) {
             $_SESSION['editor_images_access'] = (seUserGroup() == 3) ? 'admin' : seUserLogin();
             return true;
         }
         return false; 
         
     }

     public function userFolder($folder = '') {
         if ($folder == '') return;
         if (!is_dir(getcwd().'/'.SE_DIR.'images/'.$folder.'/')) {
             mkdir(getcwd().'/'.SE_DIR.'images/'.$folder.'/');
         }
         return;
     }

 }