<?php
defined('ABSPATH') || exit;
$snapshot = json_decode(file_get_contents(get_template_directory() . '/data/rankings.json'), true);
$disciplines = array(1 => 'Spacer', 2 => 'Bieg', 3 => 'Rower');
?>
<section class="container section ranking-section" id="ranking" aria-labelledby="ranking-heading">
    <div class="section-heading">
        <div><p class="eyebrow">Wyniki uczestników</p><h2 id="ranking-heading">Indywidualny Ranking Belfrów</h2></div>
    </div>
    <div class="ranking-card">
        <div class="ranking-controls" hidden>
            <div class="ranking-activities" role="group" aria-label="Aktywność">
                <?php foreach ($disciplines as $type => $label) : ?>
                <button type="button" data-activity="<?php echo esc_attr($type); ?>" aria-pressed="<?php echo $type === 1 ? 'true' : 'false'; ?>"><?php echo esc_html($label); ?></button>
                <?php endforeach; ?>
            </div>
            <label>Edycja<select id="ranking-edition"><?php foreach (array(7, 6, 5) as $edition) : ?><option value="<?php echo esc_attr($edition); ?>"><?php echo esc_html($edition . '. edycja BB'); ?></option><?php endforeach; ?></select></label>
        </div>
        <?php foreach (array(7, 6, 5) as $edition) : foreach ($snapshot['editions'][$edition] as $ranking) : ?>
        <div class="rank-panel" data-edition="<?php echo esc_attr($edition); ?>" data-activity="<?php echo esc_attr($ranking['activityType']); ?>" <?php if ($edition !== 7 || $ranking['activityType'] !== 1) echo 'hidden'; ?>>
            <div class="ranking-podium" aria-label="<?php echo esc_attr('Podium — ' . $disciplines[$ranking['activityType']] . ', ' . $edition . '. edycja'); ?>">
            <?php foreach ($ranking['table'] as $participant) : if ($participant['position'] > 3) continue; ?>
                <article class="podium-card podium-place-<?php echo esc_attr($participant['position']); ?>">
                    <div class="podium-top"><span class="podium-medal"><?php echo esc_html($participant['position']); ?></span><span><?php echo esc_html($participant['position'] === 1 ? 'Lider rankingu' : $participant['position'] . '. miejsce'); ?></span></div>
                    <span class="podium-number"><?php echo esc_html('Nr startowy ' . $participant['startNo']); ?></span>
                    <h3><?php echo esc_html($participant['nick']); ?></h3>
                    <p class="podium-group"><?php echo esc_html($participant['groupName']); ?></p>
                    <p class="podium-distance"><?php echo esc_html(number_format(floor($participant['distance'] / 100) / 10, 1, ',', ' ')); ?><span> km</span></p>
                </article>
            <?php endforeach; ?>
            <?php if (!$ranking['table']) : ?><p>Brak wyników w zapisanym rankingu.</p><?php endif; ?>
            </div>
            <div class="ranking-table-wrap" hidden>
            <table>
                <caption><?php echo esc_html($edition . '. edycja BB — ' . $disciplines[$ranking['activityType']]); ?></caption>
                <thead><tr><th scope="col">Miejsce</th><th scope="col">Uczestnik</th><th scope="col">Dystans</th></tr></thead>
                <tbody>
                <?php foreach ($ranking['table'] as $participant) : ?>
                    <tr data-position="<?php echo esc_attr($participant['position']); ?>">
                        <td><span class="rank-position"><?php echo esc_html($participant['position']); ?></span></td>
                        <td><span class="rank-start-number"><?php echo esc_html('#' . $participant['startNo']); ?></span><strong><?php echo esc_html($participant['nick']); ?></strong><?php if ($participant['groupName']) : ?><small><?php echo esc_html($participant['groupName']); ?></small><?php endif; ?></td>
                        <td class="rank-distance"><?php echo esc_html(number_format(floor($participant['distance'] / 100) / 10, 1, ',', ' ')); ?> <span>km</span></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$ranking['table']) : ?><tr><td colspan="3">Brak wyników w zapisanym rankingu.</td></tr><?php endif; ?>
                </tbody>
            </table>
            </div>
        </div>
        <?php endforeach; endforeach; ?>
        <button type="button" class="button button-outline ranking-more" aria-expanded="false" hidden>Pokaż pierwszą dziesiątkę</button>
        <p class="ranking-snapshot">Zapis wyników z <?php echo esc_html(wp_date('d.m.Y, H:i', strtotime($snapshot['fetchedAt']), new DateTimeZone('Europe/Warsaw'))); ?>. Dane nie aktualizują się automatycznie.</p>
    </div>
</section>
