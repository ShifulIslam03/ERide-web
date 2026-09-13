function validatePaymentForm(form) {
    var errEl = document.getElementById("methodErrMsg");
    if (!form.method.value) {
        if (errEl) errEl.textContent = "Please select a payment method";
        return false;
    }
    if (errEl) errEl.textContent = "";
    return true;
}
