<?php

if ( isset( $_POST[ 'PHPSESSID' ] ) )  {
    session_id( $_POST[ 'PHPSESSID' ] );
}

session_start();
ini_set( 'html_errors', '0' );

$getcwd_dir = $_SERVER[ 'DOCUMENT_ROOT' ];

if ( !isset( $_FILES[ 'Filedata' ] ) || !is_uploaded_file( $_FILES[ 'Filedata' ][ 'tmp_name' ] ) || $_FILES[ 'Filedata' ][ 'error' ] != 0 ) {
    echo 'ERROR:invalid upload';
    exit( 0 );
}

$IMAGE_DIR = '/tmp/';
if ( !is_dir( $getcwd_dir . $IMAGE_DIR ) ) {
    mkdir( $getcwd_dir . $IMAGE_DIR );
}

require_once ( $getcwd_dir.'/lib/lib_images.php' );

if ( is_uploaded_file( $_FILES[ 'Filedata' ][ 'tmp_name' ] ) ) {
    $userfile = $_FILES[ 'Filedata' ][ 'tmp_name' ];
    $userfile_size = $_FILES[ 'Filedata' ][ 'size' ];
    $user = strtolower( htmlspecialchars( $_FILES[ 'Filedata' ][ 'name' ], ENT_QUOTES ) );
    $sz = GetImageSize( $userfile );

    if ( $userfile_size > 2*1024*1000 ) {
        $error .= 'Большой размер файла';
        $flag = false;
    }
    $file = true;
}

if ( $file ) {

    $imgname = 'mod_'.time().'_'.$_SESSION[ 'img_uploud_count' ];
    $fileextens     = substr( $user, -3 );
    $uploadfile     = $getcwd_dir.$IMAGE_DIR . $imgname . '.' . $fileextens;
    $uploadfileprev = $getcwd_dir.$IMAGE_DIR . $imgname . '_prev.' . $fileextens;
    $filename       = $imgname . '.' . $fileextens;

    $width = 1024;
    $thumbwdth = 250;

    if ( $sz[ 0 ]>$width ) {
        $uploadfiletmp  = $getcwd_dir.$IMAGE_DIR . $imgname . '.temp';
        move_uploaded_file( $userfile, $uploadfiletmp );
        ImgCreate( $uploadfileprev, $uploadfile, $uploadfiletmp, $fileextens, $width, $thumbwdth );
        @unlink( $uploadfiletmp );
    } else {
        move_uploaded_file( $userfile, $uploadfile );
        ThumbCreate( $uploadfileprev, $uploadfile, $fileextens, $thumbwdth );
    }
    $_SESSION[ 'img_uploud_count' ]++;
}

echo 'FILEID: http://'.$_SERVER[ 'HTTP_HOST' ]. $IMAGE_DIR . $imgname . '_prev.' . $fileextens;