Newilinks = {
    'remove_select' : function(id) {
        var container = document.getElementById('contains_selects'),
              to_del = document.getElementById('selects_container_' + id);
       
        container.removeChild(to_del);    
    },
    'onClickRemove' : function() {
        var i, remove_links = Array.slice(document.getElementsByClassName('remove_link_seo'), 0);
        for (i=0;i <= remove_links.length-1;i++) {
            remove_links[i].onclick = function() {
                Newilinks.remove_select(this.id.split('_')[1]);
                return false;
            }
        }
    },
    'onClickSelect' : function() {
        var i, select_links = Array.slice(document.getElementsByClassName('select_change_article'), 0);
        for (i=0;i <= select_links.length-1;i++) {
            select_links[i].onclick = function() {
                document.getElementById('last_clicked').value = this.id.split('_')[1];
                return false;
            }
        }
    },
    'onAfterArticleSelect' : function(article_id, article_name) {
        var id = document.getElementById('last_clicked').value;
        document.getElementById('sel_article_' + id).value = article_name;
        document.getElementById('selected_' + id).value = article_id;
        SqueezeBox.close();
    },
    'onAddMore': function() {
        var addmore_link = document.getElementById('addmore_seo');
        addmore_link.onclick = Newilinks.clickAddMore;
    },
    'clickAddMore': function(article) {
        var container = document.getElementById('contains_selects'), newdiv = document.createElement('div');
        if (typeof(article) == 'undefined') { 
            article = false;  
        }
        newdiv.setAttribute('id', 'selects_container_' + Newilinks.memo);
        newdiv.innerHTML = Newilinks.createDivById(Newilinks.memo, article);
        container.appendChild(newdiv);
        Newilinks.memo++;

        // Refresh onClick events, so that the new items 
        // get the proper click handlers
        Newilinks.onClickRemove();
        Newilinks.onClickSelect();
        SqueezeBox.assign($$('a.modal'), {
            parse: 'rel'
        });            
        
        return false;
    },
    'createDivById' : function(id, article) {
        var content = '', translations = Newilinks.default_translations, title = Newilinks.default_translations.select_an_article;
        
        if (article && article.title) {
            title = article.title;
        } else {
            article.id = "";
        }
        content += '<div class="fltlft">';
        content += '<input type="text" value="' + title + '" id="sel_article_' + id + '" disabled="disabled" size="35">';
        content += '<input type="hidden" id="selected_' + id + '" name="selected_articles[]" value="' + article.id + '">';
        content += '</div>';
        content += '<div class="button2-left">';
        content += '<div class="blank">';
        content += '<a title="' + translations.select_change + '"';
        content += ' href="index.php?option=com_content&amp;view=articles&amp;layout=modal&amp;tmpl=component&amp;function=Newilinks.onAfterArticleSelect"';
        content += 'rel="{handler: \'iframe\', size: {x: 800, y: 450}}" id="sc_' + id + '" class="modal select_change_article">';
        content += translations.select_change + '</a></div></div>';
        content += '<a class="remove_link_seo" id="rem_' + id + '" href="#">' + translations.remove + '</a>';
        content += '<div style="clear:both;">&nbsp;</div>';
        
        return content;
    },
    'default_translations': {
        'select_an_article': "Select an Article",
        'select_change': "Select / Change",
        'remove': "Remove"
    },
    'memo': 1,
    'init': function() {
        Newilinks.onClickRemove();
        Newilinks.onClickSelect();
        Newilinks.onAddMore();
    }
}

window.addEvent('domready', Newilinks.init);