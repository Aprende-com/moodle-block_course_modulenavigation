<?php
// This file is part of The Course Module Navigation Block
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Settings for course navigation.
 * @package    block_aprende_coursenavigation
 * @copyright  2019 Pimenko <contact@pimenko.com> <pimenko.com>
 * @author     Sylvain Revenu | Nick Papoutsis | Bas Brands | Pimenko
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

    // Option: clicking on the downwards arrow 1) displays the menu or 2)goes to that page.
    $name        = 'block_aprende_coursenavigation/toggleclickontitle';
    $title       = get_string(
        'toggleclickontitle',
        'block_aprende_coursenavigation'
    );
    $description = get_string(
        'toggleclickontitle_desc',
        'block_aprende_coursenavigation'
    );
    $default     = 1;
    $choices     = [
        1 => get_string(
            'toggleclickontitle_menu',
            'block_aprende_coursenavigation'
        ),
        2 => get_string(
            'toggleclickontitle_page',
            'block_aprende_coursenavigation'
        ),
    ];
    $settings->add(
        new admin_setting_configselect(
            $name,
            $title,
            $description,
            $default,
            $choices
        )
    );

    // Option: show labels.
    $name        = 'block_aprende_coursenavigation/toggleshowlabels';
    $title       = get_string(
        'toggleshowlabels',
        'block_aprende_coursenavigation'
    );
    $description = get_string(
        'toggleshowlabels_desc',
        'block_aprende_coursenavigation'
    );
    $default     = 1;
    $choices     = [
        1 => new lang_string('no'),
        // No.
        2 => new lang_string('yes')
        // Yes.
    ];
    $settings->add(
        new admin_setting_configselect(
            $name,
            $title,
            $description,
            $default,
            $choices
        )
    );

    // Option: Show all tabs open.
    $name        = 'block_aprende_coursenavigation/togglecollapse';
    $title       = get_string(
        'togglecollapse',
        'block_aprende_coursenavigation'
    );
    $description = get_string(
        'togglecollapse_desc',
        'block_aprende_coursenavigation'
    );
    $default     = 1;
    $choices     = [
        1 => new lang_string('no'),
        // No.
        2 => new lang_string('yes')
        // Yes.
    ];
    $settings->add(
        new admin_setting_configselect(
            $name,
            $title,
            $description,
            $default,
            $choices
        )
    );

    // Option: Show only titles.
    $name        = 'block_aprende_coursenavigation/toggletitles';
    $title       = get_string(
        'toggletitles',
        'block_aprende_coursenavigation'
    );
    $description = get_string(
        'toggletitles_desc',
        'block_aprende_coursenavigation'
    );
    $default     = 1;
    $choices     = [
        1 => new lang_string('no'),
        // No.
        2 => new lang_string('yes')
        // Yes.
    ];
    $settings->add(
        new admin_setting_configselect(
            $name,
            $title,
            $description,
            $default,
            $choices
        )
    );

    // Option: show restricted course modules
    $name        = 'block_aprende_coursenavigation/toggleshowrestricted';
    $title       = get_string(
        'toggleshowrestricted',
        'block_aprende_coursenavigation'
    );
    $description = get_string(
        'toggleshowrestricted_desc',
        'block_aprende_coursenavigation'
    );
    $default     = false;

    $settings->add(
        new admin_setting_configcheckbox_with_advanced(
            $name,
            $title,
            $description,
            $default,
            true,
            false
        )
    );
}
