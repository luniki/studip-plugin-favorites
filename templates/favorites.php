<?
$infobox_content = array(array(
    'kategorie' => _('Hinweise:'),
    'eintrag'   => array(array(
        'icon' => 'ausruf_small.gif',
        'text' => _('Dies ist das Demo-Plugin.')
    ))
));
$infobox = array('picture' => 'browse.jpg', 'content' => $infobox_content);
?>

<? ob_start() ?>
<link href="<?= $plugin->getPluginURL() ?>/stylesheets/style.css"
      media="screen, print" rel="stylesheet" type="text/css" />
<? $GLOBALS['_include_additional_header'] .= ob_get_clean() ?>

<h1>
  <?= _('Favoriten') ?>
</h1>


<ol class="favorites">
<? foreach ($favorites as $value) :
    $link = URLHelper::getLink('seminar_main.php#anker', array(
                               'auswahl' => $value['Seminar_id'],
                               'redirect_to' => 'forum.php',
                               'open' => $value['topic_id'],
                               'view' => 'tree'));
    ?>
    <li>
        <div class="favorite">
            <div class="author">
                <a href="<?= URLHelper::getLink('about.php', array('username' => $value['username']))?>">
                    <?= Avatar::getAvatar($value["user_id"])->getImageTag(Avatar::MEDIUM) ?>
                    <?= htmlReady($value['author']) ?>
                </a>
            </div>

            <h4>
                <a href="<?= $link ?>">
                    <?= htmlReady($value['name']) ?>
                </a>
            </h4>
            <span class="date"><?= _("vor") ?> <?= $plugin->distance_of_time_in_words($value["chdate"]) ?></span>

            <div class="description">
                <?= formatReady(forum_parse_edit($value["description"])) ?>
            </div>
            <a class="more" href="<?= $link ?>"><img src="<?= $plugin->getPluginURL() ?>/images/plus.png" alt="click here for full content"></a>
        </div>
        <div class="clear"></div>
    </li>
<? endforeach ?>
</ol>

<script src="<?= $plugin->getPluginURL() ?>/javascripts/plugin.js"
        type="text/javascript" language="javascript" charset="utf-8"></script>

