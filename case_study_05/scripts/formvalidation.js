document.addEventListener("DOMContentLoaded", function () {
  const nameInput = document.getElementById("ApplicantName");
  const emailInput = document.getElementById("ApplicantEmail");
  const startDateInput = document.getElementById("StartDate");
  const experienceInput = document.getElementById("Experience");
  const submitButton = document.getElementById("submitButton");
  const form = document.querySelector("form");
  
  
  const nameError = document.getElementById("nameError");
  const emailError = document.getElementById("emailError");
  const startDateError = document.getElementById("startDateError");
  const experienceError = document.getElementById("experienceError");

  function validateForm() {
    const name = nameInput.value.trim();
    const email = emailInput.value.trim();
    const startDate = startDateInput.value;
    const experience = experienceInput.value.trim();

    // Clear previous errors
    nameError.textContent = "";
    emailError.textContent = "";
    startDateError.textContent = "";
    experienceError.textContent = "";

    let isValid = true;

    // Name validation
    if (name === "" || /[\d,;@#%!$^&*()_+={}\[\]\\|<>?/`~"]/.test(name)) {
      nameError.textContent = "Please enter your name properly.";
      isValid = false;
    }

    //hjsjs


    // Email validation 
   const emailPattern = /^[A-Za-z0-9]([A-Za-z0-9._-]*[A-Za-z0-9])?@([A-Za-z0-9-]+\.){1,3}[A-Za-z]{2,3}$/;
   if (email === "" || !emailPattern.test(email)) {
   emailError.textContent = "Please enter a valid email address (e.g. user.name@mail.example.com)";
   isValid = false;
  }

    // Start date validation(Optional)
      const selectedDate = new Date(startDate);
      const today = new Date();
      today.setHours(23, 59, 59, 999);
      if (selectedDate <= today) {
        startDateError.textContent = "Start date must be in the future.";
        isValid = false;
      }
  
    // Experience validation
    if (experience === "") {
      experienceError.textContent = "Please enter your experience details.";
      isValid = false;
    }

    // Additional Validation: Enable/disable submit button
    submitButton.disabled = !isValid;
    submitButton.className = isValid ? "enabled" : "disabled";

    return isValid;
  }

  // Run validation on typing
  nameInput.addEventListener("input", validateForm);
  emailInput.addEventListener("input", validateForm);
  startDateInput.addEventListener("input", validateForm);
  experienceInput.addEventListener("input", validateForm);

  //  On form submit
  form.addEventListener("submit", function (event) {
    event.preventDefault();
   alert("Form submitted successfully!");
   console.log({
       name: nameInput.value.trim(),
       email: emailInput.value.trim(),
       startDate: startDateInput.value,
       experience: experienceInput.value.trim(),
   });
   // Reset Form
    form.reset();
    validateForm(); // reset button state
  });
});
