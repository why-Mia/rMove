




//document.addEventListener('DOMContentLoaded', function () {
    function isEmpty(str) {
        return (!str || str.length === 0 );
    }


    var elements_with_click_toggle = document.getElementsByClassName('toggle-visibility');
    if(elements_with_click_toggle.length !== 0){
        Object.values(elements_with_click_toggle).forEach(element => {
            if(!isEmpty(element.dataset.clickTarget)){ //multiple click targets
                var targets = document.querySelectorAll(element.dataset.clickTarget);
                console.log(targets);
                targets.forEach(function(target) {
                    element.addEventListener("click", function(){
                        if (target.classList.contains('hidden')) {
                            target.classList.remove('hidden');
                        } else {
                            target.classList.add('hidden');
                        }
                    });
                });
            }
        });
    }
    var elements_with_text_toggle = document.getElementsByClassName('toggle-text');
    if(elements_with_text_toggle.length !== 0){
        Object.values(elements_with_text_toggle).forEach(element => {
            element.addEventListener("click", function(){
                var current_text = element.textContent;
                var new_text = element.dataset.toggleText;
                element.dataset.toggleText = current_text;
                element.textContent = new_text;
            });
        });
    }

    //
    function updateInputText(element){
        var to_change = element.parentElement.querySelector('.text-to-update p');
        var element_with_text = element.parentElement.querySelector('.text-to-update input');
        to_change.textContent = element_with_text.value;
    }

    var edit_buttons = document.querySelectorAll('.update-form-text');
    edit_buttons.forEach(function(button) {
        button.addEventListener("click", function(){
           updateInputText(button);
        });
    });

    var elements_with_external_text_toggle = document.getElementsByClassName('toggle-external-text');
    if(elements_with_external_text_toggle.length !== 0){
        Object.values(elements_with_external_text_toggle).forEach(element => {
            element.addEventListener("click", function(){
                var text = element.dataset.getTextFromElement.textContent;
                var element_to_change = element.dataset.toggleElementText;
                element_to_change.textContent = text;
            });
        });
    }

    var modals = document.querySelectorAll('.modal');
    modals.forEach(function(modal) {
        var modal_overlay = document.getElementById('modal-overlay');
        var toggle_buttons = document.querySelectorAll('[data-modal-toggle="'+modal.id+'"]');
        console.log('[data-modal-toggle="'+modal.id+'"]');
        toggle_buttons.forEach(function(button) {
            button.addEventListener("click", function(){
                if (modal.classList.contains('hidden')) {
                    modal.classList.remove('hidden');
                    modal_overlay.classList.remove('hidden');
                } else {
                    modal.classList.add('hidden');
                    modal_overlay.classList.add('hidden');
                }
            });
        });
    });

    var username_buttons = document.querySelectorAll('#roblox-username-confirm-button');
    username_buttons.forEach(function(button){
        button.addEventListener("click", function(e){
            button.classList.add('loading');
            e.preventDefault();
            fetch('/settings/check-username',{
                method: 'PUT',
                headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: '{"roblox_username":"'+document.getElementById('roblox_username').value+'"}'
            })
            .then((response) => response.json())
            .then((data) => doStuffWithResponse(data,button));
        });
    });
    function doStuffWithResponse(data,button){
        button.classList.remove('loading');
        var error_elements = document.querySelectorAll('.username-error-text');
        if(data.error_in_response === true){
            error_elements.forEach(error_element => {
                error_element.textContent = 'Error checking username, please try again later';
            });
        }
        else if(data.user_exists === false){
            error_elements.forEach(error_element => {
                error_element.textContent = 'Could not find a valid user with this username, please check that the username you entered is correct';
            });
        }
        else{
            error_elements.forEach(error_element => {
                error_element.textContent = '';
            });
            document.getElementById('check-username-form').classList.add('hidden');
            document.getElementById('verify-user-form').classList.remove('hidden');
            document.getElementById('verification-code').value=data.verification_code;
            document.getElementById('roblox-profile-link').href='https://www.roblox.com/users/'+data.id+'/profile';
        }
    }


    var refresh_buttons = document.querySelectorAll('#roblox-refresh-code-button');
    refresh_buttons.forEach(function(button){
        button.addEventListener("click", function(e){
            button.classList.add('loading');
            e.preventDefault();
            fetch('/settings/refresh-code',{
                method: 'PUT',
                headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: ''
            })
            .then((response) => response.json())
            .then((data) => doStuffWithCodeRefreshResponse(data,button));
        });
    });
    function doStuffWithCodeRefreshResponse(data,button){
        button.classList.remove('loading');
        var error_elements = document.querySelectorAll('.username-error-text');
        error_elements.forEach(error_element => {
            error_element.textContent = '';
        });
        document.getElementById('verification-code').value=data.verification_code;
    }



    var complete_verification_buttons = document.querySelectorAll('#complete-verification-button');
    complete_verification_buttons.forEach(function(button){
        button.addEventListener("click", function(e){
            button.classList.add('loading');
            e.preventDefault();
            fetch('/settings/verify-account',{
                method: 'PUT',
                headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: '{"roblox_username":"'+document.getElementById('roblox_username').value+'"}'
            })
            .then((response) => response.json())
            .then((data) => doStuffWithVerifyResponse(data,button));
        });
    });
    function doStuffWithVerifyResponse(data,button){
        
        button.classList.remove('loading');
        var error_elements = document.querySelectorAll('.username-error-text');
        if(data.error_in_response === true){
            error_elements.forEach(error_element => {
                error_element.textContent = 'Error checking username, please try again later';
            });
        }
        else if(data.user_exists === false){
            error_elements.forEach(error_element => {
                error_element.textContent = 'Could not find a valid account with this username, please check that the username you entered previously is correct';
            });
        }
        else if(data.description_string_found === false){
            error_elements.forEach(error_element => {
                error_element.textContent = "Could not find code in account description. This could be because the text is filtered, your about section hasn't updated yet, or the wrong username was entered in the previous step (the 'Roblox profile' link below should lead to your account).";
            });
        }
        else{
            location.reload();
        }
    }
//});


/*
let tabsContainer = document.querySelector("#tabs");

let tabTogglers = tabsContainer.querySelectorAll("a");
console.log(tabTogglers);

tabTogglers.forEach(function(toggler) {
  toggler.addEventListener("click", function(e) {
    e.preventDefault();

    let tabName = this.getAttribute("href");

    let tabContents = document.querySelector("#tabs");

    for (let i = 0; i < tabContents.children.length; i++) {

      tabTogglers[i].parentElement.classList.remove("border-blue-400", "border-b-4",  "-mb-px", "opacity-100");
      tabTogglers[i].parentElement.classList.add("opacity-50");
      /*tabContents.children[i].classList.remove("hidden");
      if ("#" + tabContents.children[i].id === tabName) {
        continue;
      }
      tabContents.children[i].classList.add("hidden");*/
/*
    }
    e.target.parentElement.classList.add("border-blue-400", "border-b-4", "-mb-px", "opacity-100");
    e.target.parentElement.classList.remove("opacity-50");
  });
});

document.getElementById("default-tab").click();*/
function delay(milliseconds){
    return new Promise(resolve => {
        setTimeout(resolve, milliseconds);
    });
}

var filters = document.querySelectorAll('#times-filters input, #times-filters select, #times-advanced-filters input, #times-advanced-filters select');
var table_contents = document.querySelector('.times-table>.table-content');
filters.forEach(function(filter){
    console.log(filter.classList);
    filter.addEventListener("change", function(e){
        e.preventDefault();
        sendFiltersPutRequest(table_contents);
    });
});

function sendFiltersPutRequest(table_contents){
    table_contents.innerHTML = '';
    table_contents.classList.add('loading');
    var all_filters = document.querySelectorAll('#times-filters input, #times-filters select, #times-advanced-filters input, #times-advanced-filters select');
    var all_filters_values = {};
    all_filters.forEach(each_filter => {
        all_filters_values[each_filter.id] = each_filter.value;
    });
    var request_body = JSON.stringify(all_filters_values);
    fetch(window.location.pathname+'/make-table',{
        method: 'PUT',
        headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: request_body
    })
    .then((response) => response.text())
    .then((data) => populateTable(data,table_contents));
}

function populateTable(data, table){
    table.classList.remove('loading');
    table.innerHTML = data;
}