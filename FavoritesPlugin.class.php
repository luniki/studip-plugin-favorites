<?php

# Copyright (c)  2009 - Marcus Lunzenauer <mlunzena@uos.de>
#
# Permission is hereby granted, free of charge, to any person obtaining a copy
# of this software and associated documentation files (the "Software"), to deal
# in the Software without restriction, including without limitation the rights
# to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
# copies of the Software, and to permit persons to whom the Software is
# furnished to do so, subject to the following conditions:
#
# The above copyright notice and this permission notice shall be included in all
# copies or substantial portions of the Software.
#
# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
# IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
# FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
# AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
# LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
# OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
# SOFTWARE.

class FavoritesPlugin extends StudipPlugin implements SystemPlugin
{
    /**
     * plugin template factory
     */
    protected $template_factory;

    /**
     * Initialize a new instance of the plugin.
     */
    function __construct()
    {
        parent::__construct();

        // set up top navigation
        $top_nav = new Navigation('Favoriten');
        $top_nav->setImage($this->getPluginURL().'/images/icon.gif');

        // set up tab navigation
        $navigation = new Navigation('Favoriten');
        $navigation->setURL(PluginEngine::getURL('favoritesplugin'));

        Navigation::addItem('/favorites', $top_nav);
        Navigation::addItem('/favorites/favorites', $navigation);
        Navigation::insertItem('/start/favorites', 'search', $navigation);

        $template_path = $this->getPluginPath().'/templates';
        $this->template_factory = new Flexi_TemplateFactory($template_path);
    }

    /**
     * Display the plugin view template.
     */
    function show_action()
    {
        $template = $this->template_factory->open('favorites');
        $layout = $GLOBALS['template_factory']->open('layouts/base');
        $template->set_layout($layout);

        $GLOBALS['CURRENT_PAGE'] = 'Favoriten';
        Navigation::activateItem('/favorites/favorites');

        if (Request::submitted('favorites')) {
            $template->message = _('Gort! Klaatu barada nikto!');
        }

        echo $template->render();
    }
}
?>
