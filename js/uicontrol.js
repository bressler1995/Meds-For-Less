let ajax_interval_object;
let medsforless_ajaxsearch_input = document.getElementById("medsforless_ajaxsearch_input");
let medsforless_rlvlive = document.getElementById("rlvlive");

function inject_medsforless_ajaxsearch() {
	console.log("Injecting ajax check...");
	medsforless_ajaxsearch_input.addEventListener("input", function(){
        if(this.value == '') {
            clear_ajax_load();
        } else {
            clear_ajax_load();
		    ajax_interval_object = setInterval(check_ajaxload, 1000);
        }
	});
}

function check_ajaxload() {
	console.log("Checking aria status...");
    let resultswindow = medsforless_rlvlive.getElementsByClassName("relevanssi-live-search-results");
	let resultsobject = medsforless_rlvlive.getElementsByClassName("ajax-results");
    let resultsinnerhtml = '';
    let windowshowing = false;

    if(resultswindow != null) {
        if(resultswindow.length == 1) {
            if(resultswindow[0].classList.contains("relevanssi-live-search-results-showing")) {
                windowshowing = true;
            }
        }
    }

    if(windowshowing == false) {
        clear_ajax_load();
    } else {
        if(resultsobject != null) {
            if(resultsobject.length == 1) {
                resultsinnerhtml = resultsobject[0].innerHTML;
                console.log(resultsinnerhtml);
    
                if(resultsinnerhtml != null && resultsinnerhtml != '') {
                    clear_ajax_load();
                }
            }
        }
    }

}

function clear_ajax_load() {
    clearInterval(ajax_interval_object);
    ajax_interval_object = null;
    console.log("Stopping ajax check...");
}

if(medsforless_ajaxsearch_input != null && medsforless_rlvlive != null) {
	inject_medsforless_ajaxsearch();
}