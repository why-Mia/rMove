
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
//});