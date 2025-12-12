// menu_update.js

/*
functions to validate inputs and compute costs

2 main functions:
validateInput(): form validation (accept only numbers for quantity, ensure price is selected for calculation if quantity is entered?)
computeCost(): calculate subtotal and total price
*/


// function to beautify cost presentation (add $ in front and keep to 2 dp)
function showCost(cost_value) {
    var cost = "$" + cost_value.toFixed(2); // toFixed returns a string (not a number)
    return cost;
}


// calculate subtotal and total price

function computeCost (jj_cost, cal_cost, ic_cost, jj_quantity, cal_quantity, ic_quantity) {
    
    // calculate subtotals and total cost and update textboxes for subtotals and total cost

    var jj_subtotal = jj_cost * jj_quantity;
    var cal_subtotal = cal_cost * cal_quantity;
    var ic_subtotal = ic_cost * ic_quantity;
    var total_price = jj_subtotal + cal_subtotal + ic_subtotal;

    document.getElementById("jj_subtotal").value = showCost(jj_subtotal);
    document.getElementById("cal_subtotal").value = showCost(cal_subtotal);
    document.getElementById("ic_subtotal").value = showCost(ic_subtotal);
    document.getElementById("total_price").value = showCost(total_price);

}

function validateInput () {

    // initialize variables to store inputs from radio buttons and textboxes

    const jj_cost_elements = document.getElementsByName('jj_cost');
    const cal_cost_elements = document.getElementsByName('cal_cost');
    const ic_cost_elements = document.getElementsByName('ic_cost');
    
    var jj_cost = 0;
    var cal_cost = 0;
    var ic_cost = 0;

    var jj_quantity = document.getElementById("jj_quantity").value;
    var cal_quantity = document.getElementById("cal_quantity").value;
    var ic_quantity = document.getElementById("ic_quantity").value;

    // initialize boolean to store validity status of inputs
    let isValid = true;


    // get values of inputs from radio buttons and textboxes

    // get values of radio buttons

    for (let i = 0; i < jj_cost_elements.length; i++) {
        if(jj_cost_elements[i].checked)
        jj_cost = jj_cost_elements[i].value;
    }

    for (let i = 0; i < cal_cost_elements.length; i++) {
        if(cal_cost_elements[i].checked)
        cal_cost = cal_cost_elements[i].value;
    }

    for (let i = 0; i < ic_cost_elements.length; i++) {
        if(ic_cost_elements[i].checked)
        ic_cost = ic_cost_elements[i].value;
    }

    // get values of textboxes
    // values already stored during initialization ^^

    
    // form validation (accept only numbers for quantity, ensure price is selected for calculation if quantity is entered? and vice versa?)

    // accept only numbers for quantity, and the number entered should be an integer and >= 0 (negative quantity not allowed)

    // cc_quantity < 0 || Number.isInteger(Number(cc_quantity)) != true: if quantity < 0 or quantity is not an integer, trigger the alert
    // note that .value of textbox returns a string, so we need to convert the value to a number before checking if it is an integer

    if (jj_quantity < 0 || !(Number.isInteger(Number(jj_quantity)))) {
        alert("Please enter a positive whole number for the quantity you wish to order.");
        
        document.getElementById("jj_quantity").focus();
        document.getElementById("jj_quantity").select();

        //document.getElementById("jj_subtotal").value = showCost(0); // if invalid input, set quantity to 0 for error prevention
        jj_quantity = 0;
        
        isValid = false;
    }

    if (cal_quantity < 0 || !(Number.isInteger(Number(cal_quantity)))) {
        alert("Please enter a positive whole number for the quantity you wish to order.");

        document.getElementById("cal_quantity").focus();
        document.getElementById("cal_quantity").select();

        //document.getElementById("cal_subtotal").value = showCost(0);
        cal_quantity = 0;

        isValid = false;
    }

    if (ic_quantity < 0 || !(Number.isInteger(Number(ic_quantity)))) {
        alert("Please enter a positive whole number for the quantity you wish to order.");

        document.getElementById("ic_quantity").focus();
        document.getElementById("ic_quantity").select();

        //document.getElementById("ic_subtotal").value = showCost(0);
        ic_quantity = 0;

        isValid = false;
    }

    
    // ensure price is selected if quantity > 0 is entered
    if( (jj_quantity > 0 && jj_cost == 0) || (cal_quantity > 0 && cal_cost == 0) || (ic_quantity > 0 && ic_cost == 0) ) {
        
        alert("Please select a price option for the items you wish to order.");

        if(jj_quantity > 0 && jj_cost == 0) {
            jj_cost_elements[0].focus();
            jj_cost_elements[0].select();
        }

        if(cal_quantity > 0 && cal_cost == 0) {
            cal_cost_elements[0].focus();
            cal_cost_elements[0].select();
        }

        if(ic_quantity > 0 && ic_cost == 0) {
            ic_cost_elements[0].focus();
            ic_cost_elements[0].select();
        }
        
    }

    // ensure that at least one quantity is non-zero for order to be valid for submission
    if ( jj_quantity <= 0 && cal_quantity <= 0 &&  ic_quantity <= 0 ) {
        isValid = false;
    }

    computeCost(jj_cost, cal_cost, ic_cost, jj_quantity, cal_quantity, ic_quantity); // compute the cost after validating and correcting inputs
    
    return isValid;
}