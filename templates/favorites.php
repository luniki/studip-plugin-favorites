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
<? $GLOBALS['_include_additional_header'] = ob_get_clean() ?>

<h1>
  <?= _('Favoriten') ?>
</h1>


<ol class="favorites">
<? foreach ($favorites as $value) : ?>
    <li class="container">
        <h4 class="title">
            <span class="course">
                <?= htmlReady($value['sem_name']) ?>:
            </span>
            <a href="<?= URLHelper::getLink('seminar_main.php', array(
                'auswahl' => $value['Seminar_id'],
                'redirect_to' => 'forum.php',
                'open' => $value['topic_id'],
                'view' => 'open'
            )) ?>">
                <?= htmlReady($value['name']) ?>
            </a>

            <span class="date"><?= _("vor") ?> <?= $plugin->distance_of_time_in_words($value["chdate"]) ?></span>
        </h4>

        <div class="author">
            <a href="<?= URLHelper::getLink('about.php', array('username' => $value['username']))?>">
                <?= Avatar::getAvatar($value["user_id"])->getImageTag(Avatar::MEDIUM) ?>
                <?= htmlReady($value['author']) ?>
            </a>
        </div>

        <div class="description">
            <?= formatReady(forum_parse_edit($value["description"])) ?>
            <a class="more" href="#">more</a>
        </div>
        <div class="clear"></div>
    </li>
<? endforeach ?>
</ol>

<script src="<?= $plugin->getPluginURL() ?>/javascripts/plugin.js"
        type="text/javascript" language="javascript" charset="utf-8"></script>

