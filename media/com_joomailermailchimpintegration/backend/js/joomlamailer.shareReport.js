/**
 * Copyright (C) 2015  freakedout (www.freakedout.de)
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
**/

!function($){
    $(document).ready(function(){

        joomlamailerJS.shareReport = {
            refreshPreview: function () {
                $.ajax({
                    url: joomlamailerJS.misc.adminUrl + 'index.php?option=com_joomailermailchimpintegration&controller=campaigns&task=refreshShareReport&format=raw',
                    type: 'POST',
                    data: {
                        'cid': $('#cid').val(),
                        'title': $('#title').val(),
                        'css': $('#css').val()
                    },
                    dataType: 'json',
                    success: function (response) {
                        $('#reportPreview').html(response.iframe);
                        $('#directLink a').html(response.url).attr('href', response.url);
                    }
                });
            }
        }
    });
}(jQuery);
