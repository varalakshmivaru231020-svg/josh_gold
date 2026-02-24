import './bootstrap';

document.querySelectorAll("a, button").forEach(el => {
    if (el.innerText.includes("Become an Agent")) {
        el.innerText = "Become Distributor";
    }
});

