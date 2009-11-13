<? if (isset($message)): ?>
  <?= MessageBox::success($message) ?>
<? endif ?>

<h3>
  <?= _('Demo-Plugin') ?>
</h3>

<form action="<?= PluginEngine::getLink('demoplugin') ?>" method="POST">
  <?= makeButton('ok', 'input', false, 'demo') ?>
</form>

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
