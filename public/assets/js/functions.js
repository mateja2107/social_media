function login(e) {
  e.preventDefault();

  let form = document.querySelector("#login_form");

  let formData = new FormData(form);
  let data = {};

  formData.forEach((value, name) => {
    data[name] = value;
  });

  let errors = [];

  for (let [key, value] of Object.entries(data)) {
    value = value.trim();

    if (value === "" && !errors.some((el) => el === "Field cannot be empty.")) {
      errors.push("Field cannot be empty.");
    }

    let specialCharacters = /[^\w\s]/;
    let emailRegex =
      /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    let HTMLTagRegex = /<\/?[\w\s]*>|<.+[\W]>/;

    // filter HTML tags
    if (HTMLTagRegex.test(value)) errors.push("Cannot submit html tags.");

    // validate username or email
    if (key === "username_email") {
      if (value.length < 3)
        errors.push("username or email must be 3 or more characters long.");
      if (value.includes(" "))
        errors.push("username or email cannot contain spaces.");

      if (value.includes("@")) {
        if (!emailRegex.test(value)) errors.push("Enter a valid email.");
      } else {
        if (specialCharacters.test(value))
          errors.push(
            "username cannot contain any special characters except _"
          );
      }
    }

    if (key === "login_password") {
      // validate password
      if (value.includes(" ")) errors.push("password cannot contain spaces.");
      if (value.length < 5)
        errors.push("password must be 5 of more characters long.");
    }
  }

  // errors = [];
  let errorContainer = document.querySelector("#login_errors");
  errorContainer.innerHTML = "";
  if (errors.length > 0) {
    errors.forEach((err) => {
      errorContainer.innerHTML += `<div class="alert alert-danger" role="alert">${err}</div>`;
    });
  } else {
    loginUser(data);
  }
}

function register(e) {
  e.preventDefault();

  let form = document.querySelector("#register_form");

  let formData = new FormData(form);
  let data = {};

  formData.forEach((value, name) => {
    data[name] = value;
  });

  let errors = [];

  for (let [key, value] of Object.entries(data)) {
    // trim the value "  asd " = "asd";
    value = value.trim();

    // check if all inputs are filled except bio
    if (key != "bio") {
      if (
        value === "" &&
        !errors.some((el) => el === "Field cannot be empty.")
      ) {
        errors.push("Field cannot be empty.");
      }
    }

    let numbers = /\d/;
    let letters = /[a-zA-Z]/;
    let specialCharacters = /[^\w\s]/;
    let emailRegex =
      /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    let HTMLTagRegex = /<\/?[\w\s]*>|<.+[\W]>/;

    // filter HTML tags
    if (HTMLTagRegex.test(value)) errors.push("Cannot submit html tags.");

    // validate first name and last name
    if (key === "first_name" || key === "last_name") {
      let inputName = key.replace("_", " ");
      if (value.includes(" "))
        errors.push(`${inputName} cannot contain spaces.`);
      if (value.length < 3)
        errors.push(`${inputName} must be 3 or more characters long.`);
      if (numbers.test(value))
        errors.push(`${inputName} cannot contain numbers.`);
      if (specialCharacters.test(value) || value.includes("_"))
        errors.push(`${inputName} cannot contain special characters.`);
    }

    // validate date
    if (key === "date_of_birth") {
      if (value.length != 10 || letters.test(value))
        errors.push("Enter a valid date.");
    }

    // validate status
    if (key === "status") {
      if (value != "public" && value != "private")
        errors.push("Status must be public or private.");
    }

    // validate username
    if (key === "username") {
      if (value.includes(" ")) errors.push(`${key} cannot contain spaces.`);
      if (value.length < 3) {
        errors.push(`${key} must be 3 or more characters long.`);
      }

      // all special characters except _
      let validateUsernameRegex = /[^\w\s_]/;
      if (validateUsernameRegex.test(value))
        errors.push("username cannot contain any special character except _");
    }

    // validate email
    if (key === "email") {
      if (!emailRegex.test(value)) errors.push("Enter a valid email.");
    }

    // validate password
    if (key === "register_password") {
      if (value.includes(" ")) errors.push("password cannot contain spaces.");
      if (value.length < 5)
        errors.push("password must be 5 of more characters long.");
    }
  }

  // confirm password
  if (data["confirm_password"] != data["register_password"]) {
    errors.push("passwords must match!");
  }

  // errors = [];
  let errorContainer = document.querySelector("#register_errors");
  errorContainer.innerHTML = "";
  if (errors.length > 0) {
    errors.forEach((err) => {
      errorContainer.innerHTML += `<div class="alert alert-danger" role="alert">${err}</div>`;
    });
  } else {
    registerUser(data);
  }
}

async function registerUser(data) {
  data = JSON.stringify(data);

  let res = await fetch("/register", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: data,
  });
  res = await res.json();

  let errorContainer = document.querySelector("#register_errors");
  if (res?.errors) {
    if (errorContainer.innerHTML === "" && res.errors.length > 0) {
      res.errors.forEach((err) => {
        errorContainer.innerHTML += `<div class="alert alert-danger" role="alert">${err}</div>`;
      });
    }
  }

  if (res?.email) {
    errorContainer.innerHTML = `<div class="alert alert-success" role="alert">Your account is successfully registrated. Please validate your email address <a href="/verify_user_account?email=${res.email}&verification_number=${res.number}" target="_blank">here</a>.</div>`;
    // errorContainer.innerHTML = `<div class="alert alert-success" role="alert">Your account is successfully registrated. Please check your email address.</div>`;
  }

  // console.log(res);
}

async function loginUser(data) {
  data = JSON.stringify(data);

  let res = await fetch("/login", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: data,
  });
  res = await res.json();

  let errorContainer = document.querySelector("#login_errors");
  if (res?.errors) {
    if (errorContainer.innerHTML === "" && res.errors.length > 0) {
      res.errors.forEach((err) => {
        errorContainer.innerHTML += `<div class="alert alert-danger" role="alert">${err}</div>`;
      });
    }
  } else if (res?.success) window.location.href = "/";
}
