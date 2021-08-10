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
 * @package   tool_carcastc
 * @copyright 2021, Carlos Castillo <carlos.castillo@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import Notification from 'core/notification';
import Pending from "core/pending";
import {get_strings as getStrings} from "core/str";
import Ajax from "core/ajax";
import Templates from "core/templates";

const SELECTORS = {
    DELETE_ROW: '[data-action="deleterow"]',
    ID_ROW: 'data-rowid',
    COURSE_ID: 'data-courseid',
    ID_TEMPLATE: 'tool_carcastc_rows_list',
};

/**
 * Display confirmation dialogue on delete row
 *
 * @param {Object} element
 * @param {Object} reloadElement to reload after template render
 */
const confirmDelete = (element, reloadElement) => {
    const pendingPromise = new Pending('tool_carcastc/carcastc:confirmDelete');
    getStrings([
        {'key': 'confirm'},
        {'key': 'confirmdeleterow', component: 'tool_carcastc'},
        {'key': 'yes'},
        {'key': 'no'},
    ])
    .then(strings => {
        return Notification.confirm(strings[0], strings[1], strings[2], strings[3], function() {
            const pendingPromiseDelete = new Pending('tool_carcastc/carcastc:requestWsDelete');
            const idRow = element.getAttribute(SELECTORS.ID_ROW);
            const courseid = element.getAttribute(SELECTORS.COURSE_ID);
            const requests = [
                { methodname: 'tool_carcastc_delete_row', args: {id: idRow} },
                { methodname: 'tool_carcastc_display_rows', args: {courseid: courseid} }
            ];
            requestWs(requests)[1]
            .then(response => Templates.render('tool_carcastc/rows_list', response))
            .then((html) => {
                reloadElement.innerHTML = html.toString();
            })
            .then(pendingPromiseDelete.resolve)
            .catch(Notification.exception);
        });
    })
    .then(pendingPromise.resolve)
    .catch(Notification.exception);
};

/**
 * Handle ajax requests.
 *
 * @method requestWs
 * @param {{any}} requests The method.
 * @return {promise} Resolved with ajax request
 */
const requestWs = (requests) => {
    return Ajax.call(requests);
};

/**
 * Method called when the confirmation delete event occur
 *
 * @method init
 */
export const init = () => {
    document.addEventListener('click', event => {
        const triggerElement = event.target.closest(SELECTORS.DELETE_ROW);
        const reloadElement = document.getElementById(SELECTORS.ID_TEMPLATE);
        if (triggerElement) {
            event.preventDefault();
            confirmDelete(triggerElement, reloadElement);
        }
    });
};
