// This file is part of Moodle - http://moodle.org/
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
 * Javascript module ES6 for tool_carcarstc
 *
 * @package    tool_carcastc
 * @copyright 2021, Carlos Castillo <carlos.castillo@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import Notification from 'core/notification';
import Pending from "core/pending";
import {get_strings as getStrings} from "core/str";
import Log from "core/log";

const SELECTORS = {
    DELETE_ROW: '[data-action="deleterow"]',
};

/**
 * Display confirmation dialogue on delete row
 *
 * @param {Object} element
 */
const confirmDelete = (element) => {
    const pendingPromise = new Pending('tool_carcastc/carcastc:confirmDelete');
    Log.debug(element.href);
    getStrings([
        {'key': 'confirm'},
        {'key': 'confirmdeleterow', component: 'tool_carcastc'},
        {'key': 'yes'},
        {'key': 'no'},
    ])
        .then(strings => {
            return Notification.confirm(strings[0], strings[1], strings[2], strings[3], function() {
                window.location.href = element.href;
            });
        })
        .then(pendingPromise.resolve)
        .catch(Notification.exception);
};


export const init = () => {

    document.addEventListener('click', e => {
        const triggerElement = e.target.closest(SELECTORS.DELETE_ROW);
        if (triggerElement) {
            e.preventDefault();
            confirmDelete(triggerElement);
        }

    });
};