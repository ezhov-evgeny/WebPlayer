<!--<div id="tmpPlayer" style="display: none !important;">-->
<div id="player">
	<div class="player_tabs">
		<a onclick="showTab(1);return false;" class="player_tab current" id="player_tab1" href="#">Минуса на продажу</a>
        <a onclick="showTab(2);return false;" id="player_tab2" href="#" class="player_tab">Минуса бесплатно</a>
        <a onclick="showTab(3);return false;" id="player_tab3" href="#" class="player_tab">Песни клиентов</a>
	</div>
	<div class="player_flash">
		<div id="jquery_jplayer"></div>

		<div id="player_container">
			<ul id="player_controls">
				<li id="player_play" onClick="$('#jquery_jplayer').jPlayer('play'); return false;"><a href="#" title="play"><span>play</span></a></li>
				<li id="player_pause" onClick="$('#jquery_jplayer').jPlayer('pause'); return false;"><a href="#" title="pause"><span>pause</span></a></li>
				<li id="player_stop" onClick="$('#jquery_jplayer').jPlayer('stop'); return false;"><a href="#" title="stop"><span>stop</span></a></li>
				<li id="player_prev" onClick="prevTrack();"><a href="#" title="stop"><span>Previus</span></a></li>
				<li id="player_next" onClick="nextTrack();"><a href="#" title="stop"><span>Next</span></a></li>
				<li id="player_volume_min" onClick="mute();"><a href="#" title="min volume"><span>min volume</span></a></li>
				<li id="player_volume_max" onClick="muteoff();"><a href="#" title="max volume"><span>max volume</span></a></li>
			</ul>

			<div id="player_progress">
				<div id="player_progress_load_bar">
					<div id="player_progress_play_bar"></div>
				</div>
			</div>

			<div id="player_volume_bar">
			</div>
			<div id="track_info"></div>
			<div id="play_time"></div>

            <div id="shopping_cart">
				<span id="track_count">
					Пусто
				</span> (
				<span id="track_price">
					0 руб
				</span>)
            </div>

			<div id="rate1" onclick="rate(1);">
				<div id="rate2" onclick="rate(2);">
					<div id="rate3" onclick="rate(3);">
						<div id="rate4" onclick="rate(4);">
							<div id="rate5" onclick="rate(5);">
							</div>
						</div>
					</div>
				</div>
				<div id="rateText">Проголосуйте за этот трек</div>
			</div>

            <div id="player_buy_now">
                <a href="http://vipbeat.ru/kontakty/" onclick="return urlGen();" title="Купить"></a>
            </div>
		</div>
	</div>
	<?php
		// Файлы плейлистов
		$playlist1 = 'player/playlist1.xml';
		$playlist2 = 'player/playlist2.xml';
		$playlist3 = 'player/playlist3.xml';

		// Файлы рейтингов
		$ratinglist1 = 'player/rating1.json';
		$ratinglist2 = 'player/rating2.json';
		$ratinglist3 = 'player/rating3.json';


		// Подключаем парсер
		require 'xmlparser.php';

		// Загружаем рейтинги
		$rating[] = jsonRead($ratinglist1);
		$rating[] = jsonRead($ratinglist2);
		$rating[] = jsonRead($ratinglist3);
		// Загружаем плейлисты
		$arTrackLists[] = readPlaylist($playlist1);
		$arTrackLists[] = readPlaylist($playlist2);
		$arTrackLists[] = readPlaylist($playlist3);
		
		// Берём первую компазицию из первого плейлиста для предварительной загрузки
		$num = $arTrackLists[0][0]['id'];
	?>
			<script type="text/javascript">
	// Делаем плеер
				$ = jQuery.noConflict();
				$(document).ready(function(){

					$("#jquery_jplayer").jPlayer({
						ready: function () {
							this.element.jPlayer('setFile', '<?php echo $arTrackLists[0][0]["location"]?>');
							$('#track_info').text('<?php echo $arTrackLists[0][0]["author"]?> - <?php echo $arTrackLists[0][0]["name"]?>');
						},
						cssPrefix: 'different_prefix_example',
						preload: 'none',
						swfPath: '/js'
					})
					.jPlayer('cssId', 'play', 'player_play')
					.jPlayer('cssId', 'pause', 'player_pause')
					.jPlayer('cssId', 'volumeMin', 'player_volume_min')
					.jPlayer('cssId', 'volumeMax', 'player_volume_max')
					.jPlayer('cssId', 'loadBar', 'player_progress_load_bar')
					.jPlayer('cssId', 'playBar', 'player_progress_play_bar')
					.jPlayer('onSoundComplete', function() {
						$('tr.current').next('tr').click();
					})
					.jPlayer('onProgressChange', function(lp,ppr,ppa,pt,tt) {
					  $('#play_time').text($.jPlayer.convertTime(pt)); // Default format of 'mm:ss'
					});

					$('#player_volume_bar').slider({
							value : 50,
							max: 100,
							range: 'min',
							animate: true,

							slide: function(event, ui) {
									$("#jquery_jplayer").jPlayer('volume', ui.value);
									$('#player_volume_bar').find('> div.ui-slider-range').css('width', ui.value + '%');
							}
					});
					
					// Берем первую компазицию
					eventTR = document.getElementById('<?php echo 'tab1_'.$num?>');
					
					chkbx = false;
					curTab = 'tab1';
					curSel = 0;
					curSum = 0;
					cart = [];
					rated = []
				});

			</script>
	<?php
	foreach($arTrackLists as $key => $arTrackList):
		$tabNum = $key + 1;
		if($tabNum == 1)
			$class = ' current';
		else
			$class = '';
	?>
	<div id="playlist<?php echo $tabNum; ?>" class="playlist_container<?php echo $class?>">
		<div>
			<table id="thead<?php echo $tabNum; ?>" style="widtd:590px;">
				<tr  class="playlist_header">
					<td class="p<?php echo $tabNum; ?>_col_1" onclick="sortPlaylist(this,<?php echo $tabNum; ?>,1);"><span>Название <div class="arrow"></div></span></td>
					<td class="p<?php echo $tabNum; ?>_col_2" onclick="sortPlaylist(this,<?php echo $tabNum; ?>,2,1,1);"><span>Рейтинг <div class="arrow"></div></span></td>
					<td class="p<?php echo $tabNum; ?>_col_3" onclick="sortPlaylist(this,<?php echo $tabNum; ?>,3);"><span>Автор <div class="arrow"></div></span></td>
					<td class="p<?php echo $tabNum; ?>_col_4" onclick="sortPlaylist(this,<?php echo $tabNum; ?>,4);"><span>Стиль <div class="arrow"></div></span></td>
					<td class="p<?php echo $tabNum; ?>_col_5" onclick="sortPlaylist(this,<?php echo $tabNum; ?>,5);"><span>Дата добав. <div class="arrow"></div></span></td>
					<td class="p<?php echo $tabNum; ?>_col_6" onclick="sortPlaylist(this,<?php echo $tabNum; ?>,6,1);"><span>Цена <div class="arrow"></div></span></td>
                    
				</tr>
			</table>
		</div>
		<div class="playlist_body">
			<table>
				<tbody id="tbody<?php echo $tabNum; ?>">
	<?php
		foreach($arTrackList as $elTrack){
			echo '<tr id="tab'.$tabNum.'_'.$elTrack['id'].'" class="';
			if(($num == $elTrack['id']) && ($tabNum == 1))
				echo 'current';
			echo '" onclick="playlistClick(\'tab'.$tabNum.'_'.$elTrack['id'].'\',\''.$elTrack["location"].'\');">';
			echo '	<td class="p'.$tabNum.'_col_1 first"><span>'.$elTrack['name'].'</span></td>';
			if(isset($rating[$key][$elTrack['id']]['rating'])){
				$rate = $rating[$key][$elTrack['id']]['rating'];
			}else{
				$rate = 0;
			}
			echo '	<td class="p'.$tabNum.'_col_2" customkey="'.$rate.'"><span>';
				for($i = 1; $i <= $rate;$i++){
					echo '<img src="/player/images/star-full.png" class="star" />';
				}
				if(($rate - round($rate)) != 0)
					echo '<img src="/player/images/star-half.png" class="star" />';
			echo '	</span></td>';
			echo '	<td class="p'.$tabNum.'_col_3" align="center"><span>'.$elTrack['author'].'</span></td>';
			echo '	<td class="p'.$tabNum.'_col_4" align="center"><span>'.$elTrack['style'].'</span></td>';
			echo '	<td class="p'.$tabNum.'_col_5" align="center"><span>'.$elTrack['date'].'</span></td>';
            if($tabNum == 1)
                echo '	<td class="p'.$tabNum.'_col_6" customkey="'.$elTrack['price'].'"><span>'.$elTrack['price'].' руб</span><div id="tab'.$tabNum.'_buy_'.$elTrack['id'].'" class="checkbox" customkey="'.$elTrack['price'].'" onclick="addToCart(\''.$elTrack['id'].'\', \''.$elTrack['price'].'\');"></div></td>';
            else
                echo '	<td class="p'.$tabNum.'_col_6"><span><a href="/player/download.php?id='.$elTrack["id"].'" title="скачать" target="_blank" onclick="chkbx = true;">скачать</a></span></div></td>';
			echo '</tr>';			
		}
	?>
				</tbody>
			</table>
		</div>
	</div>
	<?php endforeach;?>
</div>
