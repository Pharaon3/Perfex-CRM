<?php defined('BASEPATH') or exit('No direct script access allowed');
$data = '<div class="row">';
foreach($attachments as $attachment) {
    $attachment_url = site_url('download/file/estimate_request_attachment/'.$attachment['id']);
    if(!empty($attachment['external'])){
        $attachment_url = $attachment['external_link'];
    }
    $data .= '<div class="display-block estimate_request-attachment-wrapper">';
    $data .= '<div class="col-md-10">';
    $data .= '<div class="pull-left"><i class="'.get_mime_class($attachment['filetype']).'"></i></div>';
    $data .= '<a href="'.$attachment_url.'" target="_blank">'.$attachment['file_name'].'</a>';
    $data .= '<p class="text-muted">'.$attachment["filetype"].'</p>';
    $data .= '</div>';
    $data .= '<div class="col-md-2 text-right">';
    $data .= '</div>';
    $data .= '<div class="clearfix"></div>';
    $data .= '</div>';
}
$data .= '</div>';
echo $data;
