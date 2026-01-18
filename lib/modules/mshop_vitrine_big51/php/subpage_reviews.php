<?php

$reviews_sort = isRequest('reviews_sort') ? getRequest('reviews_sort') : (string)$section->parametrs->param322;
$reviews_asc = isRequest('asc');
$count = (int)$section->parametrs->param321;
$offset = 0;
$review_list = $psg->getGoodsReviews($viewgoods, $offset, $count, $reviews_sort, $reviews_asc);
$count_reviews = $count_visible = 0;
if ($reviews = !empty($review_list)) {
    $count_visible = count($review_list);
    $count_reviews = $psg->getCountReviews($viewgoods);
    $count_next = min($count, ($count_reviews - $count_visible));
    $sort_list = array('date' => $section->language->lang085, 'mark' => $section->language->lang086, 'helpful' => $section->language->lang087, 'rating' => $section->language->lang093);
    if (!in_array($reviews_sort, array_keys($sort_list)))
        $reviews_sort = 'date';    
    $sort = array();
    foreach($sort_list as $key => $val) {
        $sort['name'] = $val;
        $sort['field'] = $key;
        $sort['link'] = '?reviews_sort=' . $key;
        $sort['direction'] = '';
        $sort['selected'] = false;
        if ($key == $reviews_sort) {
            $sort['selected'] = true;
            if ($reviews_asc)
                $sort['direction'] = '<i class="asc">↑</i>';
            else {
                $sort['link'] .= '&asc=1';
                $sort['direction'] = '<i class="desc">↓</i>';
            }
        }
  
        $__data->setItemList($section, 'reviews_sort', $sort);
    }
}

if ($user_group)
    $user_review = $psg->isUserReview($viewgoods, $user_id); 

?>