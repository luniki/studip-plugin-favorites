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

class FavoritesPlugin extends StudipPlugin implements StandardPlugin, SystemPlugin {
    /**
     * plugin template factory
     */
    protected $template_factory;

    /**
     * Initialize a new instance of the plugin.
     */
    function __construct() {
        parent::__construct();

        $this->setup_navigation();

        $template_path = $this->getPluginPath().'/templates';
        $this->template_factory = new Flexi_TemplateFactory($template_path);
    }

    function setup_navigation() {

        if ($GLOBALS['user']->id == 'nobody') {
            return;
        }

        $top_nav = new Navigation('Favoriten');
        $top_nav->setURL(PluginEngine::getURL('favoritesplugin', array(), 'index'));
        $top_nav->setImage($this->getPluginURL().'/images/star.png');
        Navigation::addItem('/favorites', $top_nav);

        if (Navigation::hasItem('/course/forum')) {
            $navigation = new Navigation('Favoriten');
            $navigation->setURL(PluginEngine::getURL('favoritesplugin', array(), 'show'));
            Navigation::addItem('/course/forum/favorites', $navigation);
        }
    }

    function index_action() {
        require_once 'lib/forum.inc.php';

        $GLOBALS['CURRENT_PAGE'] = 'Meine Favoriten';
        Navigation::activateItem('/favorites');

        $template = $this->template_factory->open('favorites');
        $layout = $GLOBALS['template_factory']->open('layouts/base_without_infobox');
        $template->set_layout($layout);

        $template->favorites = $this->get_favorites();
        $template->plugin = $this;

        echo $template->render();
    }

    /**
     * Display the plugin view template.
     */
    function show_action() {
        require_once 'lib/forum.inc.php';

        $GLOBALS['CURRENT_PAGE'] =
            $GLOBALS['SessSemName']['header_line'] . ' - ' . 'Forum';
        Navigation::activateItem('/course/forum/favorites');

        $template = $this->template_factory->open('favorites');
        $layout = $GLOBALS['template_factory']->open('layouts/base_without_infobox');
        $template->set_layout($layout);

        $template->favorites = $this->get_course_favorites();
        $template->plugin = $this;

        echo $template->render();
    }

    ############################################################################
    #
    # model functions
    #
    ############################################################################

    function get_favorites() {

        $stmt = DBManager::get()->prepare(
            'SELECT p.*, a.username, o.flag, s.Name as sem_name '.
            'FROM px_topics p '.
            'LEFT JOIN object_user o ON p.topic_id = o.object_id '.
            'LEFT JOIN auth_user_md5 a ON p.user_id = a.user_id '.
            'LEFT JOIN seminare s ON p.Seminar_id = s.Seminar_id '.
            'WHERE flag = "fav" AND o.user_id = ? '.
            'ORDER BY p.chdate DESC');
        $stmt->execute(array($GLOBALS['user']->id));
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function get_course_favorites() {

        $stmt = DBManager::get()->prepare(
            'SELECT p.*, a.username, o.flag '.
            'FROM px_topics p '.
            'LEFT JOIN object_user o ON p.topic_id = o.object_id '.
            'LEFT JOIN auth_user_md5 a ON p.user_id = a.user_id '.
            'WHERE flag = "fav" AND o.user_id = ? AND p.Seminar_id = ? '.
            'ORDER BY o.mkdate DESC');
        $stmt->execute(array($GLOBALS['user']->id, $GLOBALS['SessSemName'][1]));
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    ############################################################################
    #
    # hook of StandardPlugin
    #
    ############################################################################

    function getIconNavigation($course_id, $last_visit) {
        return NULL;
    }

    ############################################################################
    #
    # view helpers
    #
    ############################################################################

    function distance_of_time_in_words($from_time, $to_time = null, $include_seconds = false) {

        $to_time = $to_time ? $to_time: time();

        $distance_in_minutes = floor(abs($to_time - $from_time) / 60);
        $distance_in_seconds = floor(abs($to_time - $from_time));

        $string = '';
        $parameters = array();

        if ($distance_in_minutes <= 1) {
            if (!$include_seconds) {
                $string = $distance_in_minutes == 0 ? _('weniger als 1 Minute') : _('1 Minute');
            } else {
                if ($distance_in_seconds <= 5) {
                    $string = _('weniger als 5 Sekunden');
                } else if ($distance_in_seconds >= 6 && $distance_in_seconds <= 10) {
                    $string = _('weniger als 10 Sekunden');
                } else if ($distance_in_seconds >= 11 && $distance_in_seconds <= 20) {
                    $string = _('weniger als 20 Sekunden');
                } else if ($distance_in_seconds >= 21 && $distance_in_seconds <= 40) {
                    $string = _('eine halbe Minute');
                } else if ($distance_in_seconds >= 41 && $distance_in_seconds <= 59) {
                    $string = _('weniger als 1 Minute');
                } else {
                    $string = _('1 Minute');
                }
            }
        } else if ($distance_in_minutes >= 2 && $distance_in_minutes <= 44) {
            $string = _('%minutes% Minuten');
            $parameters['%minutes%'] = $distance_in_minutes;
        } else if ($distance_in_minutes >= 45 && $distance_in_minutes <= 89) {
            $string = _('ca. 1 Stunde');
        } else if ($distance_in_minutes >= 90 && $distance_in_minutes <= 1439) {
            $string = _('ca. %hours% Stunden');
            $parameters['%hours%'] = round($distance_in_minutes / 60);
        } else if ($distance_in_minutes >= 1440 && $distance_in_minutes <= 2879) {
            $string = _('1 Tag');
        } else if ($distance_in_minutes >= 2880 && $distance_in_minutes <= 43199) {
            $string = _('%days% Tagen');
            $parameters['%days%'] = round($distance_in_minutes / 1440);
        } else if ($distance_in_minutes >= 43200 && $distance_in_minutes <= 86399) {
            $string = _('ca. 1 Monat');
        } else if ($distance_in_minutes >= 86400 && $distance_in_minutes <= 525959) {
            $string = _('%months% Monaten');
            $parameters['%months%'] = round($distance_in_minutes / 43200);
        } else if ($distance_in_minutes >= 525960 && $distance_in_minutes <= 1051919) {
            $string = _('ca. 1 Jahr');
        } else {
            $string = _('über %years% Jahren');
            $parameters['%years%'] = round($distance_in_minutes / 525960);
        }

        return strtr($string, $parameters);
    }
}

