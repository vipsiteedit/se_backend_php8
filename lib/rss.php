<?php
if (!defined('SE_DB_ENABLE') || !SE_DB_ENABLE || !defined('SE_DIR')) return;
function rss_cenerator($len = 500, $lim = 30, $lng = 'rus')
{
	$siterss = $_SERVER['HTTP_HOST'];
	$timesave = 10;
	$nn = 0;

	//$thisdate = time() + 86400;  //(`news_category`.kod='$kod') AND (`news_category`.lang='$lng')
	$category = new seTable('news_category');
	$category->select('id,ident');
	$catlist = $category->getlist();
	foreach ($catlist as $cat)
		if (!file_exists(getcwd() . '/' . SE_DIR . 'rss/news.xml') || (filemtime(getcwd() . '/' . SE_DIR . 'rss/' . $cat['ident'] . '.xml') < time() - ($timesave * 60))) {
			$cat['title'] = (!empty($cat['title'])) ? $cat['title'] : '';
			$page = $cat['ident'];
			$xml = "<?xml version=\"1.0\" encoding=\"utf-8\" ?>\n";
			$xml .= "<rss version=\"2.0\">\n";
			$xml .= "\t<channel>\n";
			$xml .= "\t\t<title>{$cat['title']}</title>\n";
			//$xml .="\t\t<description></description>\n";
			$xml .= "\t\t<link>http://" . $_SERVER['HTTP_HOST'] . "/</link>\n";
			$xml .= "\t\t<language>$lng</language>\n";
			//$xml .="\t\t<image>\n";
			//$xml .="\t\t\t<title></title>\n";
			//$xml .="\t\t\t<url>".$_SERVER['HTTP_HOST']."[contentimg]</url>\n";
			//$xml .="\t\t</image>\n";


			$newss = new seTable('news');
			$newss->select("id, news_date, title, short_txt, LEFT(`text`,600) as `fulltext`, img");
			$newss->where('id_category=?', $cat['id']);
			$newss->andwhere("active='Y'");
			$newss->orderby('news_date', 1);
			$newslist = $newss->getList(0, $lim);
			unset($newss);
			if (!empty($newslist))
				foreach ($newslist as $news) {
					@$note = $news['short_text'];
					if (empty($note)) {
						$note = se_LimitString($news['fulltext'], $len);
					}

					$links = "http://" . $siterss . "/$page/?show_to=" . $news['id'];
					// ....... RSS

					$xml .= "\t\t<item>\n";
					$xml .= "\t\t\t<title>" . $news['title'] . "</title>\n";
					if (!empty($news['img'])) {
						$_imnames = se_getDImage('/images/' . $lng . '/newsimg/' . $news['img'], 200);
						$_image = "http://" . $_SERVER['HTTP_HOST'] . $_imnames;
						$xml .= "\t\t\t<image>" . $_image . "</image>\n";
					}
					if (!empty($links)) $xml .= "\t\t\t<link>" . htmlspecialchars($links) . "</link>\n";
					$xml .= "\t\t\t<description>" . htmlspecialchars($note) . "</description>\n";
					$xml .= "\t\t\t<pubDate>" . date("d.m.Y", $news['news_date']) . "</pubDate>\n";
					$xml .= "\t\t</item>\n";
					$nn++;
				}
			$xml .= "\t</channel>\n";
			$xml .= "</rss>\n";
			if (!is_dir(getcwd() . '/' . SE_DIR . 'rss')) {
				@mkdir(getcwd() . '/' . SE_DIR . 'rss');
			}

			if (is_dir(getcwd() . '/' . SE_DIR . 'rss')) {
				$pf = fopen(getcwd() . '/' . SE_DIR . 'rss/' . $cat['ident'] . '.xml', "w+");
				fwrite($pf, $xml);
				fclose($pf);
			}
		}
}
rss_cenerator();
