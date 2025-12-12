// menu_listener.js

// listen to: 
// radio buttons for price selection
// quantity textboxes for quantity selection


// listen for radio buttons and redirect to the appropriate function

// radio button x 1 for just java (jj)

// get all elements with the specified name
const jj_cost_elements = document.getElementsByName('jj_cost');

// iterate through the collection of elements with the specified name
for (let i = 0; i < jj_cost_elements.length; i++) {
    console.log(jj_cost_elements[i].value);
    jj_cost_elements[i].onclick = validateInput;
}

// radio buttons x 2 for cafe au lait (cal)
const cal_cost_elements = document.getElementsByName('cal_cost');

for (let i = 0; i < cal_cost_elements.length; i++) {
    console.log(cal_cost_elements[i].value);
    cal_cost_elements[i].onclick = validateInput;
}

// radio buttons x 2 for iced cappucino (ic)
const ic_cost_elements = document.getElementsByName('ic_cost');

for (let i = 0; i < ic_cost_elements.length; i++) {
    console.log(ic_cost_elements[i].value);
    ic_cost_elements[i].onclick = validateInput;
}


// listen for quantity textboxes and redirect to the appropriate function
var dom = document.getElementById("jj_quantity");
dom.onchange = validateInput;

var dom = document.getElementById("cal_quantity");
dom.onchange = validateInput;

var dom = document.getElementById("ic_quantity");
dom.onchange = validateInput;


// prevent submission if any inputs are invalid
const form = document.querySelector('#order_form');

form.addEventListener("submit", function (event) {

    if(validateInput() ==  false) {
        event.preventDefault();
        alert("The order cannot be sent due to invalid input(s).");
    }

});