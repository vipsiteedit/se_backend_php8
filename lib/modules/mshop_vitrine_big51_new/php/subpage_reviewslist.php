<?php

if (!empty($review_list)) {
    $mark_title = array('1' => $section->language->lang062, '2' => $section->language->lang063, '3' => $section->language->lang064, '4' => $section->language->lang065, '5' => $section->language->lang066);
    $period = array('1' => $section->language->lang074, '2' => $section->language->lang075, '3' => $section->language->lang076); 
    foreach($review_list as $val) {
        $val['mark_title'] = $mark_title[$val['mark']];
        $val['date'] = date('d.m.Y H:i', strtotime($val['date']));
        $val['date_iso'] = date('c', strtotime($val['date']));
        $val['use_time'] = $period[$val['use_time']];
        $val['rating'] = $val['likes'] - $val['dislikes'];
        $val['rating_type'] = '';
        if ($val['rating'] > 0)
            $val['rating_type'] = 'positive'; 
        elseif ($val['rating'] < 0)
            $val['rating_type'] = 'negative'; 
        $val['mark_value'] = '';
        for ($i = 1; $i <=5; $i++) {
            $val['mark_value'] .= '<span class="markItem' . ($i <= $val['mark'] ? ' selectedMark' : '') . '"></span>';    
        }
        if (!$user_id || $user_id == $val['user_id'])
            $val['disabled'] = true; 
        elseif (!empty($val['user_vote'])) {
            $val['disabled'] = true;
            if ($val['user_vote'] > 0)
                $val['user_like'] = true;
            else
                $val['user_dislike'] = true;     
        }
        $val['merits'] = nl2br($val['merits']);
        $val['demerits'] = nl2br($val['demerits']);
        $val['comment'] = nl2br($val['comment']);
        $__data->setItemList($section, 'reviews', $val);
    } 
}

?>