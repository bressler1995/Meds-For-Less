let ajax_interval_object;
let medsforless_ajaxsearch_input = document.getElementById("medsforless_ajaxsearch_input");
let medsforless_rlvlive = document.getElementById("rlvlive");
let medsforless_ajax_resultsarray = [], arrsize = 3;

function inject_medsforless_ajaxsearch() {
	console.log("Injecting ajax check...");
	medsforless_ajaxsearch_input.addEventListener("input", function(){
        medsforless_ajax_resultsarray = [];
        if(this.value == '') {
            clear_ajax_load();
        } else {
            clear_ajax_load();
		    ajax_interval_object = setInterval(check_ajaxload, 1000);
        }
	});
}

function check_ajaxload() {
	console.log("Checking ajax load status...");
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
                    inject_ajax_controls();
                }
            }
        }
    }

}

function inject_ajax_controls() {
    let medsforless_ajaxpagination = document.getElementById("medsforless_ajaxpagination");
    let medsforless_ajaxresults_resultshidden = document.getElementById("medsforless_ajaxresults_resultshidden");

    if(medsforless_ajaxpagination != null && medsforless_ajaxresults_resultshidden != null) {
        let ajax_page_links = medsforless_ajaxpagination.getElementsByTagName("a");
        let hidden_results = medsforless_ajaxresults_resultshidden.getElementsByClassName("relevanssi-live-search-result");

        if(ajax_page_links != null) {
            if(ajax_page_links.length > 0) {
                for(i = 0; i < ajax_page_links.length; i++) {
                    let current_page_link = ajax_page_links[i];
                    let current_page_index = current_page_link.dataset.ajaxpage;

                    current_page_link.addEventListener("click", function() {
                        console.log("Ajax page click!");
                        ajax_change_page(current_page_index, ajax_page_links.length);
                    });
                }
            }
        }

        if(hidden_results != null) {
            if(hidden_results.length > 0) {
                var hidden_converted = [].slice.call(hidden_results);
                // console.log(hidden_converted);

                for(let x = 0; x < hidden_converted.length; x += arrsize) {
                    medsforless_ajax_resultsarray.push(hidden_converted.slice(x, x + arrsize));
                }

                console.log(medsforless_ajax_resultsarray);
            }
        }

    }

}

function ajax_change_page(indexparam, pagelengthparam) {
    let medsforless_ajaxresults_resultsinner = document.getElementById("medsforless_ajaxresults_resultsinner");
    let medsforless_ajaxpagination = document.getElementById("medsforless_ajaxpagination");
    console.log("Changing ajax page: indexparam(" + indexparam + "), pagelengthparam(" + pagelengthparam + ")");

    if(medsforless_ajaxresults_resultsinner != null && medsforless_ajax_resultsarray != null) {
        if(medsforless_ajax_resultsarray.length == pagelengthparam && indexparam >= 0) {
            if(medsforless_ajax_resultsarray[indexparam] != null) {
                medsforless_ajaxresults_resultsinner.innerHTML = '';
                let target_resultsarray_object = medsforless_ajax_resultsarray[indexparam];
                let target_results_output = '';

                if(target_resultsarray_object.length > 0) {
                    for(y = 0; y < target_resultsarray_object.length; y++) {
                        let current_results_arrayitem = target_resultsarray_object[y];
                        target_results_output = target_results_output + current_results_arrayitem.outerHTML;
                    }

                    console.log(target_results_output);
                    medsforless_ajaxresults_resultsinner.innerHTML = target_results_output;

                    if(medsforless_ajaxpagination != null) {
                        let ajax_page_links = medsforless_ajaxpagination.getElementsByTagName("a");

                        if(ajax_page_links != null) {
                            if(ajax_page_links.length > 0) {
                                for(z = 0; z < ajax_page_links.length; z++) {
                                    let current_page_link = ajax_page_links[z];
                                    let current_page_index = current_page_link.dataset.ajaxpage;

                                    if(current_page_index == indexparam) {
                                        if(current_page_link.classList.contains("mfl_ajaxcurrent") == false) {
                                            current_page_link.classList.add("mfl_ajaxcurrent");
                                        }
                                    } else {
                                        if(current_page_link.classList.contains("mfl_ajaxcurrent")) {
                                            current_page_link.classList.remove("mfl_ajaxcurrent");
                                        }
                                    }
                                }
                            }
                        }

                    }
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