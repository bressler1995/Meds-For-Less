jQuery(document).ready(function($) {

	$('.ct-product-accordion .elementor-accordion-item').click(function(e) {

		$('.elementor-accordion-item').not($(this)).find('.elementor-tab-title').removeClass('elementor-active');
		$('.elementor-accordion-item').not($(this)).find('.elementor-tab-content').removeClass('elementor-active');
		$('.elementor-accordion-item').not($(this)).find('.elementor-tab-content').slideUp();

		$(this).find('.elementor-tab-title').addClass('elementor-active');
		$(this).find('.elementor-tab-content').addClass('elementor-active');
		$(this).find('.elementor-tab-content').slideDown();
	});


	let arr = [];
	let count = 1;
	let total_count = [];
	
	let medsforless_popup_progress = document.getElementById("medsforless_popup_progress");
	let medsforless_accountform = document.getElementById("medsforless_accountform");
	let medsforless_general = document.getElementById("medsforless_general");
	let medsforless_condspec = document.getElementById("medsforless_condspec");
	let medsforless_loginform = document.getElementById("medsforless_loginform");
	let singleproduct_qcheck = document.getElementById("singleproduct_qcheck");
	let yesforms_checkout_wrapper = document.getElementById("yesforms_checkout_wrapper");
	let medsforless_dateBox = document.getElementById("date_box_1655237392_field");
	let medsforless_regForm = document.getElementById("user-registration-form-2385");
	let opentheform = false;
	let variations_isoutofstock = false;
	

	$('.variations tr').each(function() {

		total_count.push(count++);

		let selection_id = $(this).find('.value select').attr('id');


		$('#'+ selection_id).on("change", function() {

			if($(this).val() != "") {

		    	if (!arr.includes(selection_id + 'selected')) {

		    		arr.push(selection_id +'selected');
		    	}

		    } else {

		    	// remove duplicate array values
		    	const index = arr.indexOf(selection_id + 'selected');
				if (index > -1) {
				  arr.splice(index, 1);
				}

		    }

		    // condition in enabling and disable buttons
		    if (arr.length != total_count.length) {
                
                if(variations_isoutofstock == false) {
                    $('.ct-quick-btn button').removeClass('quickview');
		    	    $('.ct-quick-btn-two').removeClass('ct-consultation-popup');
                }

		    } else {
                
                if(variations_isoutofstock == false) {
                    $('.ct-quick-btn button').addClass('quickview');
		    	    $('.ct-quick-btn-two').addClass('ct-consultation-popup');
                }
		    	
		    }
		    
		    //  trigger if quickview display
		    /* 
		    $('.quickview').click(function(e) {
        		$('.ct-qs').hide();
        		$('.ct-product-content-col').addClass('ct-product-content-col-100');
        		$('.single-product .variations').addClass('ct-quickhide');
        		$('.single_variation_wrap').addClass('ct-quickshow');
        	});
        	*/

		});

	});
	
	function inject_progress() {
	    console.log("Injecting progress controls...");
	    let varaddtocart = document.getElementsByClassName("woocommerce-variation-add-to-cart");
	    let theforms = medsforless_condspec.getElementsByClassName("gform_wrapper");
	    let forms_present = false;
	    let outofstock_object = document.getElementsByClassName("out-of-stock");
	    
		//Observe warnings
		observe_warnings(false);
		
		if(outofstock_object != null) {
		    if(outofstock_object.length > 0) {
		        variations_isoutofstock = true;
		        console.log("Out of stock...");
		    }
		}
	    
	    if(theforms != null) {
            if(theforms.length > 0) {
                console.log("Progress Controls: Forms Detected...");
                forms_present = true;
                
                for(w = 0; w < theforms.length; w++) {
                    let thecurrentform = theforms[w];
	                let thecurrentform_button = thecurrentform.getElementsByClassName("gform_button");   
	                
	                if(thecurrentform_button != null) {
	                    if(thecurrentform_button.length > 0) {
	                        for(v = 0; v < thecurrentform_button.length; v++) {
    	                       thecurrentform_button[v].addEventListener("click", function(){
    	                           setTimeout(function(){
    	                               observe_warnings(true);
    	                           }, 2000);
    	                       });
	                        }   
	                    }
	                }
                }
            } else {
                console.log("Progress Controls: Forms Not Detected...");
            }
	   } else {
	        console.log("Progress Controls: Forms Not Detected...");
	   }
	    
	    if(document.body.classList.contains( 'logged-in' )) {
	        
	        if(medsforless_accountform.classList.contains("hidecheckoutstep") == false) {
	            medsforless_accountform.classList.add("hidecheckoutstep");
	        }
	        
	        if(forms_present == false) {
	            //add to cart directly...
	            
	            if(yesforms_checkout_wrapper != null) {
	                yesforms_checkout_wrapper.style = "display: none !important;";
	            }
	        } else {
	            
	            if(varaddtocart != null) {
	                 if(varaddtocart.length == 1) {
	                      varaddtocart[0].style = "display: none !important;";
	                 }
	            }
	            
                opentheform = true;
	        }
	        
	        injectvariation_loggedin();
	        
	    } else {
	        
	        if(medsforless_condspec.classList.contains("hidecheckoutstep") == false) {
	            medsforless_condspec.classList.add("hidecheckoutstep");
	        }
	        
	        if(varaddtocart != null) {
	           if(varaddtocart.length == 1) {
	                varaddtocart[0].style = "display: none !important;";
	           }
	        }
	            
	    }
	    
	    
	    if(medsforless_general.classList.contains("hidecheckoutstep") == false) {
	            medsforless_general.classList.add("hidecheckoutstep");
	    }
	}
	
	if(medsforless_popup_progress != null) {
	    inject_progress();
	}
	
	function inject_variationsave() {
	    console.log("Injecting variation state save...");
	    let login_button = medsforless_loginform.getElementsByClassName("uael-login-form-submit");
	    let variations_form = document.getElementsByClassName("variations_form");
	    let login_user = document.getElementById("user");
	    let login_password = document.getElementById("password");
	    
	    let choicelabels = window.sessionStorage.getItem("choicelabels");
	    let choicevalues = window.sessionStorage.getItem("choicevalues");
	    
	    if(singleproduct_qcheck != null) {
	        let qcheck_buttons = singleproduct_qcheck.getElementsByTagName("button");
	                                                                      
	        if(qcheck_buttons != null) {
	            if(qcheck_buttons.length == 1) {
	               qcheck_buttons[0].addEventListener("click", function(){
	                   let label_storage = [];
    	                let value_storage = [];
    	                window.sessionStorage.clear();
    
    	                if(variations_form != null) {
    	                    if(variations_form.length == 1) {
    	                        let variations_tbody = variations_form[0].getElementsByTagName("tbody");
    	                        
    	                        if(variations_tbody != null) {
    	                          if(variations_tbody.length == 1) {
    	                               //console.log(variations_tbody[0]);
    	                               let variations_tr = variations_tbody[0].getElementsByTagName("tr");
    	                               
    	                               if(variations_tr != null) {
    	                                    if(variations_tr.length > 0) {
    	                                        for(i = 0; i < variations_tr.length; i++) {
    	                                            let current_vartr = variations_tr[i];
    	                                           // console.log(current_vartr);
    	                                            
    	                                            let thelabel = current_vartr.getElementsByTagName("label");
    	                                            let theselected = current_vartr.getElementsByTagName("select");
    	                                            
    	                                            if(thelabel != null && theselected != null) {
    	                                                   if(thelabel.length == 1 && theselected.length == 1) {
                                                               var jelm_val = $(theselected[0]).val();//convert to jQuery Element
    	                                                       //console.log("Label: " + thelabel[0].innerHTML + ", Selected: " + jelm_val);
    	                                                       //console.log(jelm_val);
    	                                                       label_storage.push(thelabel[0].innerHTML);
    	                                                       value_storage.push(jelm_val);
    	                                                   }
    	                                            }
    	                                        }
    	                                        
    	                                        window.sessionStorage.setItem("choicelabels", JSON.stringify(label_storage));
    	                                        window.sessionStorage.setItem("choicevalues", JSON.stringify(value_storage));
    	                                        console.log("Variation State Save: " + window.sessionStorage.getItem("choicelabels"));
    	                                        console.log("Variation State Save: " + window.sessionStorage.getItem("choicevalues"));
    	                                        
    	                                    }
    	                               }
    	                          }
    	                        }
    	                    }
    	                }
	               });
	            }
	        }
	                                                                      
	    }
	    
	    //Inject events for login inputs, set session variables
	   // if(login_user != null && login_password != null) {

	   //         login_user.addEventListener("input", function(e){
	   //             let label_storage = [];
	   //             let value_storage = [];
	   //             window.sessionStorage.clear();

	   //             if(variations_form != null) {
	   //                 if(variations_form.length == 1) {
	   //                     let variations_tbody = variations_form[0].getElementsByTagName("tbody");
	                        
	   //                     if(variations_tbody != null) {
	   //                       if(variations_tbody.length == 1) {
	   //                            console.log(variations_tbody[0]);
	   //                            let variations_tr = variations_tbody[0].getElementsByTagName("tr");
	                               
	   //                            if(variations_tr != null) {
	   //                                 if(variations_tr.length > 0) {
	   //                                     for(i = 0; i < variations_tr.length; i++) {
	   //                                         let current_vartr = variations_tr[i];
	   //                                         console.log(current_vartr);
	   //                                         //figured out how to dynamically change these need to figure out how to get current val now
	   //                                         //jQuery("#pa_pack-size").val("30-tablets").change();
	                                            
	   //                                         let thelabel = current_vartr.getElementsByTagName("label");
	   //                                         let theselected = current_vartr.getElementsByTagName("select");
	                                            
	   //                                         if(thelabel != null && theselected != null) {
	   //                                                if(thelabel.length == 1 && theselected.length == 1) {
    //                                                       var jelm_val = $(theselected[0]).val();//convert to jQuery Element
	   //                                                    //console.log("Label: " + thelabel[0].innerHTML + ", Selected: " + jelm_val);
	   //                                                    //console.log(jelm_val);
	   //                                                    label_storage.push(thelabel[0].innerHTML);
	   //                                                    value_storage.push(jelm_val);
	   //                                                }
	   //                                         }
	   //                                     }
	                                        
	   //                                     window.sessionStorage.setItem("choicelabels", JSON.stringify(label_storage));
	   //                                     window.sessionStorage.setItem("choicevalues", JSON.stringify(value_storage));
	   //                                     console.log(window.sessionStorage.getItem("choicelabels"));
	   //                                     console.log(window.sessionStorage.getItem("choicevalues"));
	                                        
	   //                                 }
	   //                            }
	   //                       }
	   //                     }
	   //                 }
	   //             }
	   //         });   
	            
	   //         login_password.addEventListener("input", function(e){
	   //             let label_storage = [];
	   //             let value_storage = [];
	   //             window.sessionStorage.clear();

	   //             if(variations_form != null) {
	   //                 if(variations_form.length == 1) {
	   //                     let variations_tbody = variations_form[0].getElementsByTagName("tbody");
	                        
	   //                     if(variations_tbody != null) {
	   //                       if(variations_tbody.length == 1) {
	   //                            console.log(variations_tbody[0]);
	   //                            let variations_tr = variations_tbody[0].getElementsByTagName("tr");
	                               
	   //                            if(variations_tr != null) {
	   //                                 if(variations_tr.length > 0) {
	   //                                     for(i = 0; i < variations_tr.length; i++) {
	   //                                         let current_vartr = variations_tr[i];
	   //                                         console.log(current_vartr);
	   //                                         //figured out how to dynamically change these need to figure out how to get current val now
	   //                                         //jQuery("#pa_pack-size").val("30-tablets").change();
	                                            
	   //                                         let thelabel = current_vartr.getElementsByTagName("label");
	   //                                         let theselected = current_vartr.getElementsByTagName("select");
	                                            
	   //                                         if(thelabel != null && theselected != null) {
	   //                                                if(thelabel.length == 1 && theselected.length == 1) {
    //                                                       var jelm_val = $(theselected[0]).val();//convert to jQuery Element
	   //                                                    //console.log("Label: " + thelabel[0].innerHTML + ", Selected: " + jelm_val);
	   //                                                    //console.log(jelm_val);
	   //                                                    label_storage.push(thelabel[0].innerHTML);
	   //                                                    value_storage.push(jelm_val);
	   //                                                }
	   //                                         }
	   //                                     }
	                                        
	   //                                     window.sessionStorage.setItem("choicelabels", JSON.stringify(label_storage));
	   //                                     window.sessionStorage.setItem("choicevalues", JSON.stringify(value_storage));
	   //                                     console.log(window.sessionStorage.getItem("choicelabels"));
	   //                                     console.log(window.sessionStorage.getItem("choicevalues"));

	   //                                 }
	   //                            }
	   //                       }
	   //                     }
	   //                 }
	   //             }
	   //         });
	        
	   // }
	    
	    //Check for existing session variables and inject values
	   injectvariation_loggedin();
	    
	}
	
	if(medsforless_loginform != null) {
	    inject_variationsave();
	}
	
	function injectvariation_loggedin() {
	    let choicelabels = window.sessionStorage.getItem("choicelabels");
	    let choicevalues = window.sessionStorage.getItem("choicevalues");
	    let variations_form = document.getElementsByClassName("variations_form");
	    console.log("Checking if session vars exist...");
	    
	    //Check for existing session variables and inject values
	    if(choicelabels != null && choicevalues != null) {
	        choicelabels = JSON.parse(choicelabels);
	        choicevalues = JSON.parse(choicevalues);
	            
	        if(choicelabels.length > 0 && choicevalues.length > 0) {
	            console.log(choicelabels);
	            console.log(choicevalues);
	            
	            if(variations_form != null) {
	                    if(variations_form.length == 1) {
	                        let variations_tbody = variations_form[0].getElementsByTagName("tbody");
	                        
	                        if(variations_tbody != null) {
	                          if(variations_tbody.length == 1) {
	                               //console.log(variations_tbody[0]);
	                               let variations_tr = variations_tbody[0].getElementsByTagName("tr");
	                               
	                               if(variations_tr != null) {
	                                    if(variations_tr.length > 0) {
	                                        
	                                        for(i = 0; i < variations_tr.length; i++) {
	                                            let current_vartr = variations_tr[i];
	                                           // console.log(current_vartr);
	                                            //figured out how to dynamically change these need to figure out how to get current val now
	                                            //jQuery("#pa_pack-size").val("30-tablets").change();
	                                            
	                                            let thelabel = current_vartr.getElementsByTagName("label");
	                                            let theselected = current_vartr.getElementsByTagName("select");
	                                            
	                                            if(thelabel != null && theselected != null) {
	                                                   if(thelabel.length == 1 && theselected.length == 1) {
                                                           //   var jelm_val = $(theselected[0]).val();//convert to jQuery Element
	                                                       ////console.log("Label: " + thelabel[0].innerHTML + ", Selected: " + jelm_val);
	                                                       ////console.log(jelm_val);
	                                                       //label_storage.push(thelabel[0].innerHTML);
	                                                       //value_storage.push(jelm_val);
	                                                       
	                                                       
	                                                       let currentlabel = thelabel[0].innerHTML;
	                                                       let currentvalobj = $(theselected[0]);
	                                                       
	                                                       
	                                                       //console.log(currentlabel);
	                                                       //console.log(currentvalobj);
	                                                       
	                                                       for(x = 0; x < choicelabels.length; x++) {
	                                                           let currentchoicelabel = choicelabels[x];
	                                                           let currentchoiceval = choicevalues[x];
	                                                           
	                                                           //console.log(currentchoicelabel);
	                                                           //console.log(currentlabel);
	                                                           
	                                                           if(currentchoicelabel == currentlabel) {
	                                                                console.log("Session Var Checker: Setting " + currentlabel + " to " + currentchoiceval + "...");   
	                                                                currentvalobj.val(currentchoiceval).change();
	                                                                
	                                                                setTimeout(function(){
	                                                                    if(singleproduct_qcheck != null) {
	                                                                      let qcheck_buttons = singleproduct_qcheck.getElementsByTagName("button");
	                                                                      
	                                                                      if(qcheck_buttons != null) {
	                                                                          if(qcheck_buttons.length == 1) {
	                                                                              if(opentheform == true) {
	                                                                                  qcheck_buttons[0].click();
	                                                                                  console.log("Session Var Checker: Values Injected! Opening Form...");   
	                                                                              } else {
	                                                                                 console.log("Session Var Checker: Values Injected! Skipping Form Opening...");    
	                                                                              }
	                                                                              
	                                                                              window.sessionStorage.clear();
	                                                                          }
	                                                                      }
	                                                                      
	                                                                    }
	                                                                }, 1000);
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
	            
	        }
	    }
	    
	}
	
	function observe_warnings(findnewbutton = false) {
	  let medsforless_formwarning = document.getElementsByClassName("medsforless_formwarning");
	  let theforms = medsforless_condspec.getElementsByClassName("gform_wrapper");
	    
	    if(medsforless_formwarning != null) {
	        
	        if(medsforless_formwarning.length > 0) {
	           // console.log(medsforless_formwarning);
	            
	            for(z = 0; z < medsforless_formwarning.length; z++) {
	                let current_formwarning = medsforless_formwarning[z];
	                let current_displayatt = medsforless_formwarning[z].style.display;
	                
	                let observer = new MutationObserver(function(mutationsList, observer) {
                        for (let mutation of mutationsList){
                            // console.log('The ' + mutation.attributeName + ' attribute was modified.');
                            // console.log(mutation.target);
                            // console.log(mutation.target.style.display);
                            
                            if(mutation.target.style.display == "block") {
                              console.log("Progress Controls: A Warning Has Been Switched to Visible");
                            } else if(mutation.target.style.display == "none") {
                                console.log("Progress Controls: A Warning Has Been Switched to Invisible");
                            }
                            
                            //then check all of them
                            check_warnings();
                        }
                    });
                    observer.observe(current_formwarning, { attributes: true});
	            }
	            
	        }
	    }   
	    
	    if(findnewbutton == true) {
	        console.log("Progress Controls: Finding New Buttons...");
	        
	        if(theforms != null) {
	            
                if(theforms.length > 0) {
                    
                    for(w = 0; w < theforms.length; w++) {
                        let thecurrentform = theforms[w];
    	                let thecurrentform_button = thecurrentform.getElementsByClassName("gform_button");   
    	                
    	                if(thecurrentform_button != null) {
    	                    if(thecurrentform_button.length > 0) {
    	                        console.log("Progress Controls: Buttons Re-Detected...");
    	                        
    	                        for(v = 0; v < thecurrentform_button.length; v++) {
        	                       thecurrentform_button[v].addEventListener("click", function(){
        	                           setTimeout(function(){
        	                               observe_warnings(true);
        	                           }, 2000);
        	                       });
    	                        }   
    	                    }
    	                }
                    }
                    
                }
                
    	   }
	    }
	    
	}
	
	function check_warnings() {
	    console.log("Checking Warnings...");
	    let medsforless_formwarning = document.getElementsByClassName("medsforless_formwarning");
	    let blockCount = 0;
	    let blockArray = [];
	    
	    if(medsforless_formwarning != null) {
	        
	        if(medsforless_formwarning.length > 0) {
	           // console.log(medsforless_formwarning);
	            
	            for(x = 0; x < medsforless_formwarning.length; x++) {
	                let current_formwarning = medsforless_formwarning[x];
	                
	                if(current_formwarning.style.display == "block") {
	                    if(current_formwarning.previousSibling != null) {
	                        console.log(current_formwarning.previousSibling.id);
	                        blockArray.push(current_formwarning.previousSibling.id);
	                    }
	                    
	                    blockCount++;
	                }
	            }
	        }
	    }
	    
	    console.log("Block Count: " + blockCount);
	    console.log("Block Array: " + blockArray);
	    
	    if(blockCount > 0) {
	        //some warnings visible
	        if(medsforless_condspec != null) {
	            let theforms = medsforless_condspec.getElementsByClassName("gform_wrapper");
	            if(theforms != null) {
	                if(theforms.length > 0) {
	                    
	                  for(y = 0; y < theforms.length; y++) {
	                      let thecurrentform = theforms[y];
	                      let thecurrentform_button = thecurrentform.getElementsByClassName("gform_button");
	                      let thecurrentform_fields = thecurrentform.getElementsByClassName("gfield");
	                      
	                      if(thecurrentform_button != null) {
	                          if(thecurrentform_button.length > 0) {
	                              
	                            for(z = 0; z < thecurrentform_button.length; z++) {
    	                          if(thecurrentform_button[z].classList.contains("medsforless_disablePopupSubmit") == false) {
    	                             thecurrentform_button[z].classList.add("medsforless_disablePopupSubmit");   
    	                          }
	                            }   
	                            
	                          }
	                      }
	                      
	                      if(thecurrentform_fields != null) {
	                          if(thecurrentform_fields.length > 0) {
	                              for(u = 0; u < thecurrentform_fields.length; u++) {
	                                 let thecurrent_gfield = thecurrentform_fields[u];
	                                 let thecurrent_gfield_id = thecurrentform_fields[u].id;
	                                 let blocksmatched = 0;
	                                 let iswarning = false;
	                                 
	                                 if(thecurrent_gfield.classList.contains("medsforless_formwarning") == false) {
	                                     if(blockArray != null) {
    	                                      if(blockArray.length > 0) {
    	                                          //console.log("Checking blocks for " + thecurrent_gfield_id);
    	                                          for(c = 0; c < blockArray.length; c++) {
    	                                              let thecurrent_blockfield_id = blockArray[c];
	                                                  
	                                                  if(thecurrent_blockfield_id === thecurrent_gfield_id) {
	                                                      blocksmatched++;
	                                                  }
    	                                          }
    	                                      }
	                                    }
	                                 } else {
	                                     iswarning = true;
	                                 }
	                                 
	                                 //console.log("Blocks Matched for " + thecurrent_gfield_id + ": " + blocksmatched);
	                                 if(blocksmatched == 0 && iswarning == false) {
	                                     if(thecurrent_gfield.classList.contains("medsforless_disablegfield") == false) {
	                                         thecurrent_gfield.classList.add("medsforless_disablegfield");
	                                     }
	                                 }
	                                 
	                              }
	                          }
	                      }
	                      
	                  }
	                  
	                }
	            }
	        }
	        
	        
	    } else {
	        //no warnings visible
	        if(medsforless_condspec != null) {
	            let theforms = medsforless_condspec.getElementsByClassName("gform_wrapper");
	            if(theforms != null) {
	                if(theforms.length > 0) {
	                    
	                  for(y = 0; y < theforms.length; y++) {
	                      let thecurrentform = theforms[y];
	                      let thecurrentform_button = thecurrentform.getElementsByClassName("gform_button");
	                      let thecurrentform_fields = thecurrentform.getElementsByClassName("gfield");
	                      
	                      if(thecurrentform_button != null) {
	                          if(thecurrentform_button.length > 0) {
	                              
	                            for(z = 0; z < thecurrentform_button.length; z++) {
    	                          if(thecurrentform_button[z].classList.contains("medsforless_disablePopupSubmit") == true) {
    	                             thecurrentform_button[z].classList.remove("medsforless_disablePopupSubmit");   
    	                          }
	                            }   
	                            
	                          }
	                      }
	                      
	                      if(thecurrentform_fields != null) {
	                        if(thecurrentform_fields.length > 0) {
	                            for(u = 0; u < thecurrentform_fields.length; u++) {
	                                 let thecurrent_gfield = thecurrentform_fields[u];
	                                 
	                                 if(thecurrent_gfield.classList.contains("medsforless_disablegfield") == true) {
	                                     thecurrent_gfield.classList.remove("medsforless_disablegfield");
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
	
	function inject_regFormValidation() {
	    let regForm = medsforless_regForm.getElementsByTagName("form");
	    
	    if(regForm != null) {
	      if(regForm.length == 1) {
	        console.log("Reg Form: Detected");
	        
	        let dateObject = document.getElementById("load_flatpickr");
	        let submitOpt = regForm[0].getElementsByClassName("ur-submit-button");
	        let loadPicker = document.getElementById("load_flatpickr");
	        let telObject = document.getElementById("number_box_1657308556");
	        
	        if(loadPicker != null) {
	            loadPicker.addEventListener("keydown", function(e){
	               // alert("I told you not to, why did you do it?");
                     e.preventDefault();
	            });
	        }
	        
	        if(telObject != null) {
	             //  console.log(telObject);
	             
	             telObject.addEventListener("input", function(){
	                 let telValue = this.value;
	                 let first_name = document.getElementById("first_name");
	                 let last_name = document.getElementById("last_name");
	                 let user_email = document.getElementById("user_email");
	                 let user_login = document.getElementById("user_login");
	                 let user_pass = document.getElementById("user_pass");
	                 let user_confirm_password = document.getElementById("user_confirm_password");
	                 let medsforless_ageWarning = document.getElementById("medsforless_ageWarning");
	                 let user_date = document.getElementById("load_flatpickr");
	                 
	                 console.log("Reg Form: " + telValue);
	                 console.log(telValidation(telValue));
	                 
	                 if(telValidation(telValue) == true) {
	                     if(submitOpt != null) {
    	                      if(submitOpt.length == 1) {
    	                          //submitOpt[0].style = "";
	                              submitOpt[0].removeAttribute('disabled');
	                              
	                              if(first_name != null && last_name != null && user_email != null && user_login != null && user_pass != null && user_confirm_password != null && user_date != null) {
                                      first_name.style = "";
                                      last_name.style = "";
                                      user_email.style = "";
                                      user_login.style = "";
                                      user_pass.style = "";
                                      user_confirm_password.style = "";
                                      user_date.style = "";
                                  }
                                  
                                  medsforless_ageWarning.innerHTML = "";
                              
                                  if(medsforless_ageWarning.classList.contains("showAgeWarning")) {
                                      medsforless_ageWarning.classList.remove("showAgeWarning");
                                  }
                              
    	                      }
	                     }
	                 } else {
	                     if(submitOpt != null) {
    	                      if(submitOpt.length == 1) {
    	                          //submitOpt[0].style = "display: none;";
                                  submitOpt[0].setAttribute('disabled', '');
                                  
                                  if(first_name != null && last_name != null && user_email != null && user_login != null && user_pass != null && user_confirm_password != null && user_date != null) {
                                      first_name.style = "pointer-events: none; opacity: 0.5;";
                                      last_name.style = "pointer-events: none; opacity: 0.5;";
                                      user_email.style = "pointer-events: none; opacity: 0.5;";
                                      user_login.style = "pointer-events: none; opacity: 0.5;";
                                      user_pass.style = "pointer-events: none; opacity: 0.5;";
                                      user_confirm_password.style = "pointer-events: none; opacity: 0.5;";
                                      user_date.style = "pointer-events: none; opacity: 0.5;";
                                  }
                                  
                                  medsforless_ageWarning.innerHTML = "You must enter a valid phone number.";
                              
                                  if(medsforless_ageWarning.classList.contains("showAgeWarning") == false) {
                                      medsforless_ageWarning.classList.add("showAgeWarning");
                                  }
                                  
    	                      }
	                     }
	                 }
	                 
	             });
	        }
	        
	        if(dateObject != null) {
	            dateObject.addEventListener("change", function(){
	                 let dateValue = '';
	                 let first_name = document.getElementById("first_name");
	                 let last_name = document.getElementById("last_name");
	                 let user_email = document.getElementById("user_email");
	                 let user_login = document.getElementById("user_login");
	                 let user_pass = document.getElementById("user_pass");
	                 let user_confirm_password = document.getElementById("user_confirm_password");
	                 let medsforless_ageWarning = document.getElementById("medsforless_ageWarning");
	                 let user_tel = document.getElementById("number_box_1657308556");
	        
	                console.log("Reg Form: " + this.value);
	                dateValue = this.value;
	                console.log(underAgeValidate(dateValue));
	               // if(underAgeValidate(dateValue) == true) {
	               //     ofAge = true;
	               // }
	               
	               if(underAgeValidate(dateValue) == true) {
	                 if(submitOpt != null) {
	                      if(submitOpt.length == 1) {
	                          //submitOpt[0].style = "";
	                          submitOpt[0].removeAttribute('disabled');
	                          
	                          if(first_name != null && last_name != null && user_email != null && user_login != null && user_pass != null && user_confirm_password != null && user_tel != null) {
                                  first_name.style = "";
                                  last_name.style = "";
                                  user_email.style = "";
                                  user_login.style = "";
                                  user_pass.style = "";
                                  user_confirm_password.style = "";
                                  user_tel.style = "";
                              }
                              
                              medsforless_ageWarning.innerHTML = "";
                              
                              if(medsforless_ageWarning.classList.contains("showAgeWarning")) {
                                  medsforless_ageWarning.classList.remove("showAgeWarning");
                              }
	                      }
	                   }
	               } else {
	                   if(submitOpt != null) {
	                      if(submitOpt.length == 1) {
	                          //submitOpt[0].style = "display: none;";
                              submitOpt[0].setAttribute('disabled', '');
                              
                              if(first_name != null && last_name != null && user_email != null && user_login != null && user_pass != null && user_confirm_password != null && user_tel != null) {
                                  first_name.style = "pointer-events: none; opacity: 0.5;";
                                  last_name.style = "pointer-events: none; opacity: 0.5;";
                                  user_email.style = "pointer-events: none; opacity: 0.5;";
                                  user_login.style = "pointer-events: none; opacity: 0.5;";
                                  user_pass.style = "pointer-events: none; opacity: 0.5;";
                                  user_confirm_password.style = "pointer-events: none; opacity: 0.5;";
                                  user_tel.style = "pointer-events: none; opacity: 0.5;";
                              }
                              
                              medsforless_ageWarning.innerHTML = "You must be at least 18 years of age to register.";
                              
                              if(medsforless_ageWarning.classList.contains("showAgeWarning") == false) {
                                  medsforless_ageWarning.classList.add("showAgeWarning");
                              }
	                      }
	                   }
	               }
	            });
	        }
	        
	       // regForm[0].addEventListener('submit', function(event){
    	   //        let dateObject_submit = document.getElementById("load_flatpickr");
    	   //        let dateValue = '';
    	        
    	   //        if(dateObject_submit != null) {
    	   //            dateValue = dateObject_submit.value;
    	   //            if(underAgeValidate(dateValue) == false) {
        // 	               alert("You must be at least 18 to register");
        // 	               event.preventDefault();  
        // 	               return false;
    	   //            }
    	   //        }
	       //});
	        
	            console.log(regForm[0]);
	        
	      }
	    }
	}
	
	function underAgeValidate(birthday){
    	// it will accept two types of format yyyy-mm-dd and yyyy/mm/dd
    	var optimizedBirthday = birthday.replace(/-/g, "/");
    
    	//set date based on birthday at 01:00:00 hours GMT+0100 (CET)
    	var myBirthday = new Date(optimizedBirthday);
    
    	// set current day on 01:00:00 hours GMT+0100 (CET)
    	var currentDate = new Date().toJSON().slice(0,10)+' 01:00:00';
    
    	// calculate age comparing current date and borthday
    	var myAge = ~~((Date.now(currentDate) - myBirthday) / (31557600000));
    
    	if(myAge < 18) {
         	    return false;
            }else{
    	    return true;
    	}

    }
    
    function telValidation(telval) {
        var phoneReg = /^[0-9()-.\s]+$/
        var inputStripped = telval.replace(/\D/g,'');

        // Pulls out class for Validation Check

        // Checks type and length
        if (phoneReg.test(telval) && inputStripped.length >= 10) {
            return true;
        } else {
            return false;
        }
        
    }
	
	if(medsforless_regForm != null && medsforless_dateBox != null) {
	    inject_regFormValidation();
	}
	
});


