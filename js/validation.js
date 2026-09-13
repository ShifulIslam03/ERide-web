
function togglePassword(inputId, btn) {
    var input = document.getElementById(inputId);
    if (!input) return;
    if (input.type === "password") {
        input.type = "text";
        btn.textContent = "🔓";
    } else {
        input.type = "password";
        btn.textContent = "🔒";
    }
}


function showForgotPasswordMsg(e) {
    e.preventDefault();
    var msg = document.getElementById("forgotPasswordMsg");
    if (msg) {
        msg.style.display = "block";
    }
}


function setError(id, message) {
    var el = document.getElementById(id);
    if (el) el.textContent = message;
}

function clearErrors(ids) {
    ids.forEach(function (id) { setError(id, ""); });
}


function validateLoginForm(form) {
    clearErrors(["phoneErrMsg", "passwordErrMsg"]);
    var phone = form.phone.value.trim();
    var password = form.password.value;
    var valid = true;

    if (!/^01[0-9]{9}$/.test(phone)) {
        setError("phoneErrMsg", "Please enter a valid 11-digit phone number");
        valid = false;
    }
    if (password.length === 0) {
        setError("passwordErrMsg", "Please fill up the password properly");
        valid = false;
    }
    return valid;
}


function validateRegisterForm(form) {
    clearErrors(["phoneErrMsg", "nameErrMsg", "dobErrMsg", "genderErrMsg", "areaErrMsg", "roadErrMsg", "blockErrMsg", "passwordErrMsg", "confirmPasswordErrMsg"]);
    var valid = true;

    var phone = form.phone.value.trim();
    if (!/^01[0-9]{9}$/.test(phone)) {
        setError("phoneErrMsg", "Phone number must be 11 digits starting with 01");
        valid = false;
    }

    var name = form.name.value.trim();
    if (!/^[a-zA-Z .]{2,100}$/.test(name)) {
        setError("nameErrMsg", "Please enter a valid name");
        valid = false;
    }

    if (!form.dob.value) {
        setError("dobErrMsg", "Please select a valid date of birth");
        valid = false;
    }

    if (!form.gender.value) {
        setError("genderErrMsg", "Please select a gender");
        valid = false;
    }

    if (!form.area.value.trim()) {
        setError("areaErrMsg", "Please fill up the area properly");
        valid = false;
    }
    if (!form.road.value.trim()) {
        setError("roadErrMsg", "Please fill up the road properly");
        valid = false;
    }
    if (!form.block.value.trim()) {
        setError("blockErrMsg", "Please fill up the block properly");
        valid = false;
    }

    var password = form.password.value;
    if (!/^(?=.*[A-Za-z])(?=.*\d).{6,}$/.test(password)) {
        setError("passwordErrMsg", "Password must be at least 6 characters and include a letter and a number");
        valid = false;
    }
    if (password !== form.confirmPassword.value) {
        setError("confirmPasswordErrMsg", "Passwords do not match");
        valid = false;
    }

    return valid;
}


function validateProfileForm(form) {
    clearErrors(["nameErrMsg", "dobErrMsg", "genderErrMsg", "areaErrMsg", "roadErrMsg", "blockErrMsg"]);
    var valid = true;

    if (!/^[a-zA-Z .]{2,100}$/.test(form.name.value.trim())) {
        setError("nameErrMsg", "Please enter a valid name");
        valid = false;
    }
    if (!form.dob.value) {
        setError("dobErrMsg", "Please select a valid date of birth");
        valid = false;
    }
    if (!form.gender.value) {
        setError("genderErrMsg", "Please select a gender");
        valid = false;
    }
    if (!form.area.value.trim()) {
        setError("areaErrMsg", "Please fill up the area properly");
        valid = false;
    }
    if (!form.road.value.trim()) {
        setError("roadErrMsg", "Please fill up the road properly");
        valid = false;
    }
    if (!form.block.value.trim()) {
        setError("blockErrMsg", "Please fill up the block properly");
        valid = false;
    }
    return valid;
}


function validatePasswordForm(form) {
    clearErrors(["currentPasswordErrMsg", "newPasswordErrMsg", "confirmPasswordErrMsg"]);
    var valid = true;

    if (!form.currentPassword.value) {
        setError("currentPasswordErrMsg", "Please enter your current password");
        valid = false;
    }
    if (!/^(?=.*[A-Za-z])(?=.*\d).{6,}$/.test(form.newPassword.value)) {
        setError("newPasswordErrMsg", "New password must be at least 6 characters and include a letter and a number");
        valid = false;
    }
    if (form.newPassword.value !== form.confirmPassword.value) {
        setError("confirmPasswordErrMsg", "New passwords do not match");
        valid = false;
    }
    return valid;
}


function validateStationForm(form) {
    clearErrors(["nameErrMsg", "areaErrMsg", "blockErrMsg", "roadErrMsg", "capacityErrMsg"]);
    var valid = true;

    if (!form.stationName.value.trim()) {
        setError("nameErrMsg", "Please enter a station name");
        valid = false;
    }
    if (!form.area.value.trim()) {
        setError("areaErrMsg", "Please enter an area");
        valid = false;
    }
    if (!form.block.value.trim()) {
        setError("blockErrMsg", "Please enter a block");
        valid = false;
    }
    if (!form.road.value.trim()) {
        setError("roadErrMsg", "Please enter a road");
        valid = false;
    }
    if (!form.capacity.value || parseInt(form.capacity.value, 10) <= 0) {
        setError("capacityErrMsg", "Capacity must be greater than 0");
        valid = false;
    }
    return valid;
}


(function () {
    var phoneInput = document.getElementById("phone");
    if (!phoneInput || !document.getElementById("registerForm")) return;

    var timer = null;
    phoneInput.addEventListener("input", function () {
        clearTimeout(timer);
        var phone = phoneInput.value.trim();
        if (!/^01[0-9]{9}$/.test(phone)) return;

        timer = setTimeout(function () {
            fetch("../controller/UserExists.php?phone=" + encodeURIComponent(phone))
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    if (data.exists) {
                        setError("phoneErrMsg", data.message);
                    } else {
                        setError("phoneErrMsg", "");
                    }
                })
                .catch(function () {  });
        }, 400);
    });
})();
