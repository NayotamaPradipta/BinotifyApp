function setMaxDate() {
    const today = new Date().toISOString().split('T')['0'];
    document.querySelectorAll('input[type="date"]').forEach((dateInput) => {
        dateInput.setAttribute("max", today);
    });
}

window.onload = setMaxDate;