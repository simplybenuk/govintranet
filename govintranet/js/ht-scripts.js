function markDocumentLinks() {
	jQuery('a[href$=".pdf"]').addClass('pdfdocument').append(' <span class="doc_type_text">(PDF)</span>');
	jQuery('a[href$=".xls"]').addClass('xlsdocument').append(' <span class="doc_type_text">(Excel)</span>');
	jQuery('a[href$=".xlsx"]').addClass('xlsdocument').append(' <span class="doc_type_text">(Excel)</span>');
	jQuery('a[href$=".doc"]').addClass('docdocument').append(' <span class="doc_type_text">(Word)</span>');
	jQuery('a[href$=".docx"]').addClass('docdocument').append(' <span class="doc_type_text">(Word)</span>');
	jQuery('a[href$=".docm"]').addClass('docdocument').append(' <span class="doc_type_text">(Word)</span>');
	jQuery('a[href$=".dot"]').addClass('docdocument').append(' <span class="doc_type_text">(Word)</span>');
	jQuery('a[href$=".ppt"]').addClass('pptdocument').append(' <span class="doc_type_text">(Powerpoint)</span>');
	jQuery('a[href$=".pptx"]').addClass('pptdocument').append(' <span class="doc_type_text">(Powerpoint)</span>');
	jQuery('a[href$=".ppsx"]').addClass('pptdocument').append(' <span class="doc_type_text">(Powerpoint)</span>');
	jQuery('a[href$=".txt"]').addClass('txtdocument').append(' <span class="doc_type_text">(Text)</span>');
	jQuery('a[href$=".csv"]').addClass('xlsdocument').append(' <span class="doc_type_text">(CSV)</span>');
	jQuery('a[href$=".mp3"]').addClass('mp3document').append(' <span class="doc_type_text">(MP3)</span>');
	jQuery('a[href$=".rtf"]').addClass('opendocument').append(' <span class="doc_type_text">(RTF)</span>');
	jQuery('a[href$=".odt"]').addClass('opendocument').append(' <span class="doc_type_text">(OpenDocument)</span>');
	jQuery('a[href$=".fodt"]').addClass('opendocument').append(' <span class="doc_type_text">(OpenDocument)</span>');
	jQuery('a[href$=".odp"]').addClass('opendocument').append(' <span class="doc_type_text">(OpenDocument)</span>');
	jQuery('a[href$=".fodp"]').addClass('opendocument').append(' <span class="doc_type_text">(OpenDocument)</span>');
	jQuery('a[href$=".ods"]').addClass('opendocument').append(' <span class="doc_type_text">(OpenDocument)</span>');
	jQuery('a[href$=".fods"]').addClass('opendocument').append(' <span class="doc_type_text">(OpenDocument)</span>');
	jQuery('a[href$=".odg"]').addClass('opendocument').append(' <span class="doc_type_text">(OpenDocument)</span>');
	jQuery('a[href$=".fodg"]').addClass('opendocument').append(' <span class="doc_type_text">(OpenDocument)</span>');
	jQuery('a[href$=".odf"]').addClass('opendocument').append(' <span class="doc_type_text">(OpenDocument)</span>');
	jQuery('a[href^="mailto:"]').before("<span class='dashicons dashicons-email-alt'></span> ");
	jQuery('.gallery br').remove();
	jQuery('div.gallery').append('<div class="clearfix"></div>');

	return true;	
}

function gaTrackDownloadableFiles() {

	var links = jQuery('a');
	var xlinks = jQuery(".external-link");
	
	if (typeof _gaq == 'function')	{
		for(var i = 0; i < links.length; i++) {
			if (links[i].href.indexOf('.pdf') != "-1") {
			   jQuery(links[i]).attr("onclick","javascript: _gaq.push(['_trackPageview', '"+links[i].href+"']);");
			} else if (links[i].href.indexOf('.csv') != "-1") {
			   jQuery(links[i]).attr("onclick","javascript: _gaq.push(['_trackPageview', '"+links[i].href+"']);");
			} else if (links[i].href.indexOf('.doc') != "-1") {
			   jQuery(links[i]).attr("onclick","javascript: _gaq.push(['_trackPageview', '"+links[i].href+"']);");
			} else if (links[i].href.indexOf('.ppt') != "-1") {
			   jQuery(links[i]).attr("onclick","javascript: _gaq.push(['_trackPageview', '"+links[i].href+"']);");
			} else if (links[i].href.indexOf('.rtf') != "-1") {
			   jQuery(links[i]).attr("onclick","javascript: _gaq.push(['_trackPageview', '"+links[i].href+"']);");
			} else if (links[i].href.indexOf('.xls') != "-1") {
			   jQuery(links[i]).attr("onclick","javascript: _gaq.push(['_trackPageview', '"+links[i].href+"']);");
			} else if (links[i].href.indexOf('.jpg') != "-1") {
			   jQuery(links[i]).attr("onclick","javascript: _gaq.push(['_trackPageview', '"+links[i].href+"']);");
			} else if (links[i].href.indexOf('.gif') != "-1") {
			   jQuery(links[i]).attr("onclick","javascript: _gaq.push(['_trackPageview', '"+links[i].href+"']);");
			} else if (links[i].href.indexOf('.png') != "-1") {
			   jQuery(links[i]).attr("onclick","javascript: _gaq.push(['_trackPageview', '"+links[i].href+"']);");
			} else if (links[i].href.indexOf('.odt') != "-1") {
			   jQuery(links[i]).attr("onclick","javascript: _gaq.push(['_trackPageview', '"+links[i].href+"']);");
			} else if (links[i].href.indexOf('.fodt') != "-1") {
			   jQuery(links[i]).attr("onclick","javascript: _gaq.push(['_trackPageview', '"+links[i].href+"']);");
			} else if (links[i].href.indexOf('.odp') != "-1") {
			   jQuery(links[i]).attr("onclick","javascript: _gaq.push(['_trackPageview', '"+links[i].href+"']);");
			} else if (links[i].href.indexOf('.fodp') != "-1") {
			   jQuery(links[i]).attr("onclick","javascript: _gaq.push(['_trackPageview', '"+links[i].href+"']);");
			} else if (links[i].href.indexOf('.ods') != "-1") {
			   jQuery(links[i]).attr("onclick","javascript: _gaq.push(['_trackPageview', '"+links[i].href+"']);");
			} else if (links[i].href.indexOf('.fods') != "-1") {
			   jQuery(links[i]).attr("onclick","javascript: _gaq.push(['_trackPageview', '"+links[i].href+"']);");
			} else if (links[i].href.indexOf('.odg') != "-1") {
			   jQuery(links[i]).attr("onclick","javascript: _gaq.push(['_trackPageview', '"+links[i].href+"']);");
			} else if (links[i].href.indexOf('.fodg') != "-1") {
			   jQuery(links[i]).attr("onclick","javascript: _gaq.push(['_trackPageview', '"+links[i].href+"']);");
			} else if (links[i].href.indexOf('.odf') != "-1") {
			   jQuery(links[i]).attr("onclick","javascript: _gaq.push(['_trackPageview', '"+links[i].href+"']);");
			} else if (links[i].href.indexOf('.rtf') != "-1") {
			   jQuery(links[i]).attr("onclick","javascript: _gaq.push(['_trackPageview', '"+links[i].href+"']);");
			}           
		}
		for(var i = 0; i < xlinks.length; i++) {
			jQuery(xlinks[i]).attr("onclick","javascript: _gaq.push(['_trackPageview', '"+xlinks[i].href+"']);");
		}
	}

	if (typeof ga == 'function') {
		for(var i = 0; i < links.length; i++) {
			if (links[i].href.indexOf('.pdf') != "-1") {
			   jQuery(links[i]).attr("onclick","javascript: ga('send', 'pageview', '"+links[i].href+"');");
			} else if (links[i].href.indexOf('.csv') != "-1") {
			   jQuery(links[i]).attr("onclick","javascript: ga('send', 'pageview', '"+links[i].href+"');");
			} else if (links[i].href.indexOf('.doc') != "-1") {
			   jQuery(links[i]).attr("onclick","javascript: ga('send', 'pageview', '"+links[i].href+"');");
			} else if (links[i].href.indexOf('.ppt') != "-1") {
			   jQuery(links[i]).attr("onclick","javascript: ga('send', 'pageview', '"+links[i].href+"');");
			} else if (links[i].href.indexOf('.rtf') != "-1") {
			   jQuery(links[i]).attr("onclick","javascript: ga('send', 'pageview', '"+links[i].href+"');");
			} else if (links[i].href.indexOf('.xls') != "-1") {
			   jQuery(links[i]).attr("onclick","javascript: ga('send', 'pageview', '"+links[i].href+"');");
			} else if (links[i].href.indexOf('.jpg') != "-1") {
			   jQuery(links[i]).attr("onclick","javascript: ga('send', 'pageview', '"+links[i].href+"');");
			} else if (links[i].href.indexOf('.gif') != "-1") {
			   jQuery(links[i]).attr("onclick","javascript: ga('send', 'pageview', '"+links[i].href+"');");
			} else if (links[i].href.indexOf('.png') != "-1") {
			   jQuery(links[i]).attr("onclick","javascript: ga('send', 'pageview', '"+links[i].href+"');");
			} else if (links[i].href.indexOf('.png') != "-1") {
			   jQuery(links[i]).attr("onclick","javascript: ga('send', 'pageview', '"+links[i].href+"');");
			} else if (links[i].href.indexOf('.odt') != "-1") {
			   jQuery(links[i]).attr("onclick","javascript: ga('send', 'pageview', '"+links[i].href+"');");
			} else if (links[i].href.indexOf('.fodt') != "-1") {
			   jQuery(links[i]).attr("onclick","javascript: ga('send', 'pageview', '"+links[i].href+"');");
			} else if (links[i].href.indexOf('.odp') != "-1") {
			   jQuery(links[i]).attr("onclick","javascript: ga('send', 'pageview', '"+links[i].href+"');");
			} else if (links[i].href.indexOf('.fodp') != "-1") {
			   jQuery(links[i]).attr("onclick","javascript: ga('send', 'pageview', '"+links[i].href+"');");
			} else if (links[i].href.indexOf('.ods') != "-1") {
			   jQuery(links[i]).attr("onclick","javascript: ga('send', 'pageview', '"+links[i].href+"');");
			} else if (links[i].href.indexOf('.fods') != "-1") {
			   jQuery(links[i]).attr("onclick","javascript: ga('send', 'pageview', '"+links[i].href+"');");
			} else if (links[i].href.indexOf('.odg') != "-1") {
			   jQuery(links[i]).attr("onclick","javascript: ga('send', 'pageview', '"+links[i].href+"');");
			} else if (links[i].href.indexOf('.fodg') != "-1") {
			   jQuery(links[i]).attr("onclick","javascript: ga('send', 'pageview', '"+links[i].href+"');");
			} else if (links[i].href.indexOf('.odf') != "-1") {
			   jQuery(links[i]).attr("onclick","javascript: ga('send', 'pageview', '"+links[i].href+"');");
			} else if (links[i].href.indexOf('.rtf') != "-1") {
			   jQuery(links[i]).attr("onclick","javascript: ga('send', 'pageview', '"+links[i].href+"');");
			}           
		}
		for(var i = 0; i < xlinks.length; i++) {
			jQuery(xlinks[i]).attr("onclick","javascript: ga('send', 'pageview', '"+xlinks[i].href+"');");
		}
	}
    
    return true;    
}

function getCookie(name) {
    var dcookie = document.cookie; 
    var cname = name + "=";
    var clen = dcookie.length;
    var cbegin = 0;
        while (cbegin < clen) {
        var vbegin = cbegin + cname.length;
            if (dcookie.substring(cbegin, vbegin) == cname) { 
            var vend = dcookie.indexOf (";", vbegin);
                if (vend == -1) vend = clen;
            return unescape(dcookie.substring(vbegin, vend));
            }
        cbegin = dcookie.indexOf(" ", cbegin) + 1;
            if (cbegin == 0) break;
        }
    return null;
}

function setCookie(name,value,expires,path,domain,secure) {
	var today = new Date();
	today.setTime( today.getTime() );
	
	if ( expires ) {
		expires = expires * 1000 * 60; // time in minutes
	}
	
	var expires_date = new Date( today.getTime() + (expires) );
	
	document.cookie = name + "=" +escape( value ) +
	( ( expires ) ? ";expires=" + expires_date.toGMTString() : "" ) +
	( ( path ) ? ";path=" + path : "" ) +
	( ( domain ) ? ";domain=" + domain : "" ) +
	( ( secure ) ? ";secure" : "" );

    return null;
}

function gaTrackExternalClicks(blog) {

	var links = jQuery('#content a');

	for(var i = 0; i < links.length; i++) {
		if (links[i].href.indexOf(blog) == "-1" && links[i].href.indexOf('mailto:') == "-1" && links[i].href.indexOf('tel:') == "-1") {
			jQuery(links[i]).prepend("<span class='dashicons dashicons-share-alt2'></span> ");
		}
	}

	return true;	
}

